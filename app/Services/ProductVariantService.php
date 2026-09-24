<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\PlatformSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductVariantService
{
    public function presets(): array
    {
        return config('product_attributes.presets', []);
    }

    public function builderPayload(Product $product): array
    {
        $product->load(['attributes.values', 'variants.attributeValues.attribute']);

        return [
            'has_variants' => $product->hasVariants(),
            'presets'      => $this->presets(),
            'max_attributes' => $this->maxAttributes(),
            'max_values'     => $this->maxValuesPerAttribute(),
            'max_total_values' => $this->maxTotalValues(),
            'max_combinations' => $this->maxCombinations(),
            'max_active_variants' => $this->maxActiveVariants(),
            'attributes'   => $product->attributes->map(function (ProductAttribute $attribute) {
                return [
                    'name'   => $attribute->name,
                    'values' => $attribute->values->pluck('value')->values()->all(),
                    'images' => $attribute->values->map(fn ($value) => $value->image_path
                        ? Storage::url($value->image_path)
                        : null)->values()->all(),
                ];
            })->values()->all(),
            'variants' => $product->variants->map(function (ProductVariant $variant) {
                return [
                    'id'        => $variant->id,
                    'key'       => $this->combinationKey($variant->attributeValues->pluck('value')->all()),
                    'label'     => $variant->label(),
                    'values'    => $variant->attributeValues
                        ->sortBy('attribute.sort_order')
                        ->pluck('value')
                        ->values()
                        ->all(),
                    'price'     => $variant->price,
                    'old_price' => $variant->old_price,
                    'stock'     => $variant->stock,
                    'sku'       => $variant->sku,
                    'is_active' => $variant->is_active,
                ];
            })->values()->all(),
        ];
    }

    // $attributesData = [
    //   ['name' => 'Couleur', 'values' => ['Rouge', 'Bleu']],
    // ]
    public function createAttributes(Product $product, array $attributesData): void
    {
        $this->sync($product, $attributesData, []);
    }

    public function createVariant(
        Product $product,
        array $valueIds,
        int $price,
        int $stock,
        ?int $oldPrice = null,
        ?string $sku = null
    ): ProductVariant {
        return DB::transaction(function () use (
            $product, $valueIds, $price, $stock, $oldPrice, $sku
        ) {
            if (ProductVariant::where('product_id', $product->id)->where('is_active', true)->count() >= $this->maxActiveVariants()) {
                throw ValidationException::withMessages([
                    'variants' => 'La limite de variantes actives pour ce produit est atteinte.',
                ]);
            }

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'price'      => $price,
                'old_price'  => $oldPrice,
                'stock'      => $stock,
                'sku'        => $sku,
                'is_active'  => true,
            ]);

            $variant->attributeValues()->sync($valueIds);

            return $variant;
        });
    }

    public function updateVariant(
        ProductVariant $variant,
        int $price,
        int $stock,
        ?int $oldPrice = null,
        ?string $sku = null,
        ?bool $isActive = null
    ): void {
        if ($price < 1) {
            throw ValidationException::withMessages(['variants' => 'Le prix de la variante doit être supérieur à 0.']);
        }

        $maxPrice = (int) PlatformSetting::getValue('max_product_price', 10000000);
        if ($price > $maxPrice) {
            throw ValidationException::withMessages(['variants' => "Le prix de la variante ne peut pas dépasser {$maxPrice} FCFA."]);
        }

        if ($oldPrice !== null && $oldPrice <= $price) {
            throw ValidationException::withMessages(['variants' => "L'ancien prix doit être supérieur au prix actuel."]);
        }

        if ($isActive === true && ! $variant->is_active) {
            $activeCount = ProductVariant::where('product_id', $variant->product_id)
                ->where('is_active', true)
                ->where('id', '!=', $variant->id)
                ->count();

            if ($activeCount >= $this->maxActiveVariants()) {
                throw ValidationException::withMessages([
                    'variants' => 'La limite de variantes actives pour ce produit est atteinte.',
                ]);
            }
        }

        $variant->update([
            'price'     => $price,
            'old_price' => $oldPrice,
            'stock'     => max(0, $stock),
            'sku'       => $sku,
            ...($isActive === null ? [] : ['is_active' => $isActive]),
        ]);

        $this->syncCatalogFields($variant->product->fresh());
    }

    public function sync(
        Product $product,
        array $attributesData,
        array $variantsData,
        array $valueImages = []
    ): void {
        $attributesData = $this->normalizeAttributes($attributesData);
        $this->assertLimits($attributesData);

        $expected = $this->cartesian($attributesData);

        $maxActiveVariants = $this->maxActiveVariants();
        if (count($expected) > $maxActiveVariants) {
            throw ValidationException::withMessages([
                'variants' => "Maximum {$maxActiveVariants} variantes actives autorisées.",
            ]);
        }

        $variantsByKey = [];
        foreach ($variantsData as $row) {
            $key = $this->combinationKey($row['values'] ?? []);
            if ($key === '') {
                continue;
            }
            $variantsByKey[$key] = $row;
        }

        DB::transaction(function () use (
            $product, $attributesData, $expected, $variantsByKey, $valueImages
        ) {
            $existingByKey = [];
            foreach ($product->variants()->with('attributeValues.attribute')->get() as $variant) {
                $key = $this->combinationKey(
                    $variant->attributeValues
                        ->sortBy('attribute.sort_order')
                        ->pluck('value')
                        ->all()
                );
                if ($key !== '') {
                    $existingByKey[$key] = $variant;
                }
            }

            $valueMap = $this->rebuildAttributes($product, $attributesData, $valueImages);

            $keepIds = [];

            foreach ($expected as $combo) {
                $key = $this->combinationKey($combo);
                $row = $variantsByKey[$key] ?? [];
                $valueIds = [];
                foreach ($combo as $index => $value) {
                    $valueIds[] = $valueMap[$attributesData[$index]['name'] . '|' . $value];
                }
                $submittedId = isset($row['id']) ? (int) $row['id'] : 0;
                $existing = $submittedId > 0
                    ? $product->variants()->whereKey($submittedId)->first()
                    : ($existingByKey[$key] ?? null);

                $payload = [
                    'price'     => (int) ($row['price'] ?? $product->price),
                    'old_price' => $this->nullableInt($row['old_price'] ?? null),
                    'stock'     => (int) ($row['stock'] ?? 0),
                    'sku'       => $row['sku'] ?? null,
                    'is_active' => array_key_exists('is_active', $row)
                        ? filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                        : true,
                ];

                if ($existing) {
                    $existing->update($payload);
                    $existing->attributeValues()->sync($valueIds);
                    $keepIds[] = $existing->id;
                } else {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        ...$payload,
                    ]);
                    $variant->attributeValues()->sync($valueIds);
                    $keepIds[] = $variant->id;
                }
            }

            $product->variants()
                ->whereNotIn('id', $keepIds ?: [0])
                ->get()
                ->each(function (ProductVariant $variant) {
                    if ($this->variantIsReferenced($variant)) {
                        $variant->update(['is_active' => false]);
                        return;
                    }
                    $variant->attributeValues()->detach();
                    $variant->delete();
                });
        });

        $this->syncCatalogFields($product->fresh());
    }

    public function maxAttributes(): int
    {
        return max(1, (int) PlatformSetting::getValue('variant_max_attributes', 3));
    }

    public function maxValuesPerAttribute(): int
    {
        return max(1, (int) PlatformSetting::getValue('variant_max_values_per_attribute', 12));
    }

    public function maxTotalValues(): int
    {
        return max(1, (int) PlatformSetting::getValue('variant_max_total_values', 30));
    }

    public function maxCombinations(): int
    {
        return max(1, (int) PlatformSetting::getValue('variant_max_combinations', 36));
    }

    public function maxActiveVariants(): int
    {
        return max(1, (int) PlatformSetting::getValue('variant_max_active', 36));
    }

    public function disableVariants(Product $product): void
    {
        $product->variants()->update(['is_active' => false]);
    }

    public function getVariantsForDisplay(Product $product): array
    {
        $variants = $product->activeVariants()
            ->with('attributeValues.attribute')
            ->get();

        return $variants->map(function (ProductVariant $variant) {
            $values = $variant->attributeValues;

            return [
                'id'        => $variant->id,
                'price'     => $variant->price,
                'old_price' => $variant->old_price,
                'stock'     => $variant->availableStock(),
                'sku'       => $variant->sku,
                'label'     => $variant->label(),
                'value_ids' => $values->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                'options'   => $values->mapWithKeys(fn ($value) => [
                    (string) $value->product_attribute_id => (int) $value->id,
                ])->all(),
                'images'    => $values
                    ->filter(fn ($value) => $value->image_path)
                    ->map(fn ($value) => Storage::url($value->image_path))
                    ->values()
                    ->all(),
            ];
        })->values()->all();
    }

    public function findVariantByValues(Product $product, array $valueIds): ?ProductVariant
    {
        sort($valueIds);
        $valueIds = array_map('intval', $valueIds);

        return $product->activeVariants()
            ->with('attributeValues')
            ->get()
            ->first(function (ProductVariant $variant) use ($valueIds) {
                $variantValueIds = $variant->attributeValues->pluck('id')->map(fn ($id) => (int) $id)->sort()->values()->all();

                return $variantValueIds === $valueIds;
            });
    }

    public function snapshot(ProductVariant $variant): array
    {
        $variant->loadMissing('attributeValues.attribute');

        return [
            'sku'        => $variant->sku,
            'label'      => $variant->label(),
            'attributes' => $variant->attributeValues
                ->sortBy('attribute.sort_order')
                ->map(fn ($value) => [
                    'name'  => $value->attribute?->name,
                    'value' => $value->value,
                ])
                ->values()
                ->all(),
        ];
    }

    public function syncCatalogFields(Product $product): void
    {
        if (! $product->hasVariants()) {
            return;
        }

        $active = $product->activeVariants();

        $minPrice = $active->min('price');
        $maxOld   = $active->max('old_price');
        $stock    = (int) $active->sum('stock');

        $product->update([
            'price'     => $minPrice ?: $product->price,
            'old_price' => $maxOld && $maxOld > ($minPrice ?: $product->price) ? $maxOld : $product->old_price,
            'stock'     => $stock,
        ]);
    }

    private function rebuildAttributes(Product $product, array $attributesData, array $valueImages): array
    {
        $keptImages = [];
        $product->loadMissing('attributes.values');

        foreach ($product->attributes as $attribute) {
            foreach ($attribute->values as $value) {
                if ($value->image_path) {
                    $keptImages[$attribute->name . '|' . $value->value] = $value->image_path;
                }
            }
        }

        $product->attributes()->delete();

        $valueMap = [];

        foreach ($attributesData as $order => $attrData) {
            $attribute = ProductAttribute::create([
                'product_id' => $product->id,
                'name'       => $attrData['name'],
                'sort_order' => $order,
            ]);

            foreach ($attrData['values'] as $valueOrder => $valueName) {
                $imageKey = $attrData['name'] . '|' . $valueName;
                $imagePath = $keptImages[$imageKey] ?? null;
                unset($keptImages[$imageKey]);

                $upload = $valueImages[$order][$valueOrder] ?? null;
                if ($upload instanceof UploadedFile) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $upload->store("products/{$product->id}/attributes", 'public');
                }

                $value = ProductAttributeValue::create([
                    'product_attribute_id' => $attribute->id,
                    'value'                => $valueName,
                    'sort_order'           => $valueOrder,
                    'image_path'           => $imagePath,
                ]);

                $valueMap[$attrData['name'] . '|' . $valueName] = $value->id;
            }
        }

        foreach ($keptImages as $unusedPath) {
            Storage::disk('public')->delete($unusedPath);
        }

        return $valueMap;
    }

    private function variantIsReferenced(ProductVariant $variant): bool
    {
        return OrderItem::where('product_variant_id', $variant->id)->exists()
            || CartItem::where('product_variant_id', $variant->id)->exists();
    }

    private function normalizeAttributes(array $attributesData): array
    {
        $normalized = [];

        foreach ($attributesData as $attrData) {
            $name = trim((string) ($attrData['name'] ?? ''));
            $values = collect($attrData['values'] ?? [])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique(fn ($value) => mb_strtolower($value))
                ->values()
                ->all();

            if ($name === '' || $values === []) {
                continue;
            }

            $normalized[] = ['name' => $name, 'values' => $values];
        }

        return $normalized;
    }

    private function assertLimits(array $attributesData): void
    {
        $maxAttr = $this->maxAttributes();
        $maxVal = $this->maxValuesPerAttribute();
        $maxTotal = $this->maxTotalValues();
        $maxCombo = $this->maxCombinations();

        if (count($attributesData) === 0) {
            throw ValidationException::withMessages([
                'attributes' => 'Ajoutez au moins un attribut avec des valeurs.',
            ]);
        }

        if (count($attributesData) > $maxAttr) {
            throw ValidationException::withMessages([
                'attributes' => "Maximum {$maxAttr} attributs par produit.",
            ]);
        }

        $totalValues = 0;
        foreach ($attributesData as $attr) {
            $count = count($attr['values']);
            $totalValues += $count;

            if ($count > $maxVal) {
                throw ValidationException::withMessages([
                    'attributes' => "Maximum {$maxVal} valeurs pour « {$attr['name']} ».",
                ]);
            }
        }

        if ($totalValues > $maxTotal) {
            throw ValidationException::withMessages([
                'attributes' => "Maximum {$maxTotal} valeurs au total pour un produit.",
            ]);
        }

        if ($this->combinationCount($attributesData) > $maxCombo) {
            throw ValidationException::withMessages([
                'variants' => "Trop de combinaisons (max {$maxCombo}). Réduisez le nombre de valeurs.",
            ]);
        }
    }

    private function combinationCount(array $attributesData): int
    {
        $count = 1;

        foreach ($attributesData as $attr) {
            $count *= max(1, count($attr['values']));

            if ($count > $this->maxCombinations()) {
                return $count;
            }
        }

        return $count;
    }

    private function cartesian(array $attributesData): array
    {
        $sets = array_map(fn ($attr) => $attr['values'], $attributesData);
        $result = [[]];

        foreach ($sets as $set) {
            $next = [];
            foreach ($result as $prefix) {
                foreach ($set as $value) {
                    $next[] = [...$prefix, $value];
                }
            }
            $result = $next;
        }

        return $result;
    }

    private function combinationKey(array $values): string
    {
        $values = collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->all();

        return implode('||', $values);
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}