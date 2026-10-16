<?php

namespace App\Services;

use App\Models\AdminLog;
use App\Models\PlatformSetting;
use App\Models\PlatformSettingChange;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class BusinessSettingsService
{
    public function catalog(): array
    {
        return config('business_settings.definitions', []);
    }

    public function groups(): array
    {
        return config('business_settings.groups', []);
    }

    public function definition(string $key): ?array
    {
        return $this->catalog()[$key] ?? null;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $definition = $this->definition($key);
        $fallback = $default ?? ($definition['default'] ?? null);

        $raw = Cache::remember($this->cacheKey($key), (int) config('business_settings.cache_ttl', 3600), function () use ($key) {
            return PlatformSetting::query()->where('key', $key)->first();
        });

        if (! $raw) {
            return $this->cast($definition['type'] ?? 'text', $fallback);
        }

        return $this->cast($raw->type ?: ($definition['type'] ?? 'text'), $raw->value);
    }

    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    public function getBool(string $key, bool $default = false): bool
    {
        return (bool) $this->get($key, $default);
    }

    public function getRate(string $key, float $default = 0): float
    {
        return ((float) $this->get($key, $default)) / 100;
    }

    public function enabled(string $key, bool $default = true): bool
    {
        return $this->getBool($key, $default);
    }

    public function put(string $key, mixed $value, ?User $admin = null, ?string $reason = null): void
    {
        $definition = $this->definition($key);
        if (! $definition) {
            throw ValidationException::withMessages([
                $key => "Paramètre inconnu : {$key}.",
            ]);
        }

        $normalized = $this->normalize($definition['type'], $value);
        $this->assertBounds($key, $definition, $normalized);

        $setting = PlatformSetting::query()->where('key', $key)->first();
        $old = $setting?->value;

        if (! $setting) {
            $setting = new PlatformSetting(['key' => $key]);
        }

        $setting->fill([
            'value'         => (string) $normalized,
            'type'          => $definition['type'],
            'label'         => $definition['label'],
            'description'   => $definition['description'] ?? null,
            'group'         => $definition['group'],
            'sort_order'    => $definition['sort'] ?? 0,
            'is_active'     => true,
            'unit'          => $definition['unit'] ?? null,
            'min_value'     => isset($definition['min']) ? (string) $definition['min'] : null,
            'max_value'     => isset($definition['max']) ? (string) $definition['max'] : null,
            'applies_to'    => $definition['applies_to'] ?? 'new_and_in_progress',
            'is_sensitive'  => (bool) ($definition['sensitive'] ?? false),
            'default_value' => (string) ($definition['default'] ?? ''),
            'updated_by'    => $admin?->id,
        ]);
        $setting->save();

        Cache::forget($this->cacheKey($key));

        if ((string) $old !== (string) $normalized) {
            PlatformSettingChange::create([
                'key'        => $key,
                'old_value'  => $old,
                'new_value'  => (string) $normalized,
                'admin_id'   => $admin?->id,
                'reason'     => $reason,
                'ip_address' => request()->ip(),
            ]);

            if ($admin) {
                AdminLog::record(
                    $admin,
                    'settings.updated',
                    'platform_setting',
                    $setting->id,
                    "{$key}: " . ($old ?? '∅') . ' → ' . $normalized
                );
            }
        }
    }

    public function reset(string $key, ?User $admin = null, ?string $reason = null): void
    {
        $definition = $this->definition($key);
        if (! $definition) {
            return;
        }

        $this->put($key, $definition['default'] ?? '', $admin, $reason ?? 'Réinitialisation');
    }

    public function syncFromCatalog(): int
    {
        $count = 0;

        foreach ($this->catalog() as $key => $definition) {
            $setting = PlatformSetting::query()->firstOrNew(['key' => $key]);
            $isNew = ! $setting->exists;

            $setting->fill([
                'type'          => $definition['type'],
                'label'         => $definition['label'],
                'description'   => $definition['description'] ?? null,
                'group'         => $definition['group'],
                'sort_order'    => $definition['sort'] ?? 0,
                'unit'          => $definition['unit'] ?? null,
                'min_value'     => isset($definition['min']) ? (string) $definition['min'] : null,
                'max_value'     => isset($definition['max']) ? (string) $definition['max'] : null,
                'applies_to'    => $definition['applies_to'] ?? 'new_and_in_progress',
                'is_sensitive'  => (bool) ($definition['sensitive'] ?? false),
                'default_value' => (string) ($definition['default'] ?? ''),
            ]);

            if ($isNew) {
                $setting->value = (string) ($definition['default'] ?? '');
                $setting->is_active = true;
            }

            $setting->save();
            Cache::forget($this->cacheKey($key));
            $count++;
        }

        return $count;
    }

    public function groupedForAdmin(): array
    {
        $this->syncFromCatalog();

        $rows = PlatformSetting::query()
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (PlatformSetting $s) => $this->definition($s->key) !== null);

        $labels = $this->groups();
        $grouped = [];

        foreach ($rows as $row) {
            $group = $row->group;
            if (! isset($grouped[$group])) {
                $grouped[$group] = [
                    'label'    => $labels[$group] ?? $group,
                    'settings' => [],
                ];
            }
            $def = $this->definition($row->key);
            $grouped[$group]['settings'][] = [
                'model'      => $row,
                'definition' => $def,
            ];
        }

        return $grouped;
    }

    private function cacheKey(string $key): string
    {
        return "setting_{$key}";
    }

    private function cast(string $type, mixed $value): mixed
    {
        return match ($type) {
            'percentage' => (float) $value,
            'integer'    => (int) $value,
            'boolean'    => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default      => $value,
        };
    }

    private function normalize(string $type, mixed $value): string|int|float
    {
        return match ($type) {
            'boolean'    => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0',
            'integer'    => (int) $value,
            'percentage' => (float) $value,
            default      => (string) $value,
        };
    }

    private function assertBounds(string $key, array $definition, mixed $value): void
    {
        $type = $definition['type'];

        if (in_array($type, ['integer', 'percentage'], true)) {
            $numeric = (float) $value;
            if (isset($definition['min']) && $numeric < (float) $definition['min']) {
                throw ValidationException::withMessages([
                    $key => "La valeur de « {$definition['label']} » doit être ≥ {$definition['min']}.",
                ]);
            }
            if (isset($definition['max']) && $numeric > (float) $definition['max']) {
                throw ValidationException::withMessages([
                    $key => "La valeur de « {$definition['label']} » doit être ≤ {$definition['max']}.",
                ]);
            }
        }
    }
}
