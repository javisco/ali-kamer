<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductVariantService
{
    // ── Créer les attributs d'un produit ─────────────────────────────

    // $attributesData = [
    //   ['name' => 'Processeur', 'values' => ['Core i3', 'Core i5', 'Core i7']],
    //   ['name' => 'RAM',        'values' => ['4GB', '8GB']],
    // ]
    public function createAttributes(Product $product, array $attributesData): void
    {
        DB::transaction(function () use ($product, $attributesData) {

            // Supprimer les anciens attributs
            $product->attributes()->delete();

            foreach ($attributesData as $order => $attrData) {
                $attribute = ProductAttribute::create([
                    'product_id' => $product->id,
                    'name'       => $attrData['name'],
                    'sort_order' => $order,
                ]);

                foreach ($attrData['values'] as $valueOrder => $value) {
                    ProductAttributeValue::create([
                        'product_attribute_id' => $attribute->id,
                        'value'                => $value,
                        'sort_order'           => $valueOrder,
                    ]);
                }
            }
        });
    }

    // ── Créer une variante ────────────────────────────────────────────

    // $valueIds = IDs des ProductAttributeValue sélectionnés
    // Ex: [id_core_i5, id_8gb]
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
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'price'      => $price,
                'old_price'  => $oldPrice,
                'stock'      => $stock,
                'sku'        => $sku,
                'is_active'  => true,
            ]);

            // Associer les valeurs d'attributs
            $variant->attributeValues()->sync($valueIds);

            return $variant;
        });
    }

    // ── Mettre à jour une variante ────────────────────────────────────

    public function updateVariant(
        ProductVariant $variant,
        int $price,
        int $stock,
        ?int $oldPrice = null
    ): void {
        $variant->update([
            'price'     => $price,
            'old_price' => $oldPrice,
            'stock'     => $stock,
        ]);
    }

    // ── Récupérer les données variantes pour la fiche produit ─────────

    // Retourne les données formatées pour le JS de sélection des variantes
    public function getVariantsForDisplay(Product $product): array
    {
        $variants = $product->activeVariants()
                            ->with('attributeValues.attribute')
                            ->get();

        return $variants->map(function (ProductVariant $variant) {
            return [
                'id'          => $variant->id,
                'price'       => $variant->price,
                'old_price'   => $variant->old_price,
                'stock'       => $variant->availableStock(),
                'label'       => $variant->label(),
                'value_ids'   => $variant->attributeValues->pluck('id')->toArray(),
            ];
        })->toArray();
    }

    // ── Trouver une variante par ses valeurs d'attributs ─────────────

    public function findVariantByValues(Product $product, array $valueIds): ?ProductVariant
    {
        sort($valueIds);

        return $product->activeVariants()
            ->whereHas('attributeValues', function ($q) use ($valueIds) {
                $q->whereIn('product_attribute_values.id', $valueIds);
            }, '=', count($valueIds))
            ->get()
            ->first(function (ProductVariant $variant) use ($valueIds) {
                $variantValueIds = $variant->attributeValues->pluck('id')->sort()->values()->toArray();
                return $variantValueIds === $valueIds;
            });
    }
}