<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'label',
        'description', 'group', 'sort_order',
    ];

    // ── Récupérer une valeur ──────────────────────────────────────────

    // Récupère la valeur castée selon le type
    // Mis en cache 1 heure — invalidé à chaque modification
    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (! $setting) return $default;

            return match($setting->type) {
                'percentage' => (float) $setting->value,
                'integer'    => (int) $setting->value,
                'boolean'    => (bool) $setting->value,
                default      => $setting->value,
            };
        });
    }

    // Récupère le taux en décimal (ex: 2% → 0.02)
    public static function getRate(string $key): float
    {
        return self::getValue($key, 0) / 100;
    }

    // ── Modifier une valeur ───────────────────────────────────────────

    public static function setValue(string $key, mixed $value): void
    {
        self::where('key', $key)->update(['value' => (string) $value]);

        // Invalider le cache
        Cache::forget("setting_{$key}");
    }

    // ── Valeur castée pour l'affichage ───────────────────────────────

    public function getCastedValueAttribute(): mixed
    {
        return match($this->type) {
            'percentage' => (float) $this->value,
            'integer'    => (int) $this->value,
            'boolean'    => (bool) $this->value,
            default      => $this->value,
        };
    }

    // Libellé du type pour l'affichage
    public function typeLabel(): string
    {
        return match($this->type) {
            'percentage' => '%',
            'integer'    => 'FCFA / nombre',
            'boolean'    => 'Oui / Non',
            default      => 'Texte',
        };
    }
}