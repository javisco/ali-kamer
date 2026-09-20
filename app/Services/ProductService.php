<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    // Créer un produit
    public function create(Shop $shop, array $data): Product
    {
        return DB::transaction(function () use ($shop, $data) {

            $product = Product::create([
                'shop_id'                => $shop->id,
                'category_id'            => $data['category_id'],
                'title'                  => $data['title'],
                'description'            => $data['description'],
                'city'                   => $shop->city,
                'price'                  => $data['price'],
                'old_price'              => $data['old_price'] ?? null,
                'stock'                  => $data['stock'],
                'min_quantity'           => $data['min_quantity'],
                'shipping_included'      => $data['shipping_included'],
                'shipping_threshold_qty' => $data['shipping_threshold_qty'] ?? null,
                'specifications'         => $data['specifications'] ?? null,
                'status'                 => 'hidden', // toujours caché à la création
            ]);

            // Upload des images
            if (isset($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    $url = $image->store("products/{$product->id}", 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'url'        => $url,
                        'position'   => $index + 1,
                        'is_primary' => $index === 0, // première image = principale
                    ]);
                }
            }

            $this->syncVariantsIfRequested($product, $data);

            return $product;
        });
    }

    private function syncVariantsIfRequested(Product $product, array $data): void
    {
        $variantService = app(ProductVariantService::class);

        if (empty($data['has_variants'])) {
            if ($product->hasVariants()) {
                $variantService->disableVariants($product);
            }
            return;
        }

        $variantService->sync(
            $product,
            $data['attributes'] ?? [],
            $data['variants'] ?? [],
            $data['value_images'] ?? []
        );
    }

    // Modifier un produit
    public function update(Product $product, array $data): Product
    {
        DB::transaction(function () use ($product, $data) {

            $product->update([
                'category_id'            => $data['category_id'],
                'title'                  => $data['title'],
                'description'            => $data['description'],
                'price'                  => $data['price'],
                'old_price'              => $data['old_price'] ?? null,
                'stock'                  => $data['stock'],
                'min_quantity'           => $data['min_quantity'],
                'shipping_included'      => $data['shipping_included'],
                'shipping_threshold_qty' => $data['shipping_threshold_qty'] ?? null,
                'specifications'         => $data['specifications'] ?? null,
            ]);

            // Nouvelles images uploadées
            if (isset($data['images'])) {
                $lastPosition = $product->images()->max('position') ?? 0;

                foreach ($data['images'] as $index => $image) {
                    $url = $image->store("products/{$product->id}", 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'url'        => $url,
                        'position'   => $lastPosition + $index + 1 ,
                        'is_primary' => false,
                    ]);
                }
            }

            $this->syncVariantsIfRequested($product, $data);
        });

        return $product->fresh();
    }

    // Supprimer une image
    public function deleteImage(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->url);
        $image->delete();
    }

    // Changer le statut visible/hidden
    public function toggleVisibility(Product $product): void
    {
        $newStatus = $product->status === 'visible' ? 'hidden' : 'visible';
        $product->update(['status' => $newStatus]);
    }

    // Supprimer un produit
    public function delete(Product $product): void
    {
        // Supprimer les images du stockage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->url);
        }
        $product->delete();
    }
}