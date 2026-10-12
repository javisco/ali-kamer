<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class OrderRepurchaseService
{
    public function __construct(private CartService $cartService) {}

    /**
     * Analyse et prépare le rachat d'une commande passée.
     * Vérifie la disponibilité des boutiques, produits, variantes et stocks,
     * et compare les prix passés avec les prix actuels.
     */
    public function prepare(Order $order): array
    {
        if (! $this->isEligible($order)) {
            throw new \InvalidArgumentException('Cette commande n\'est pas éligible au rachat (statut actuel : ' . $order->status . ').');
        }

        if ($order->exists) {
            $order->loadMissing([
                'shop.user',
                'items.product.shop.user',
                'items.product.images',
                'items.product.primaryImage',
                'items.variant.attributeValues.attribute',
                'items.variant.images',
            ]);
        }

        $evaluatedItems = [];
        $availableCount = 0;
        $unavailableCount = 0;
        $hasPriceChanges = false;

        foreach ($order->items as $item) {
            $product = $item->product;
            $shop = $product?->shop ?? $order->shop;
            $seller = $shop?->user;
            $variant = $item->variant;

            $isProductValid = $product && $product->isVisible() && ! $product->isBanned();
            $isShopValid = $shop && $shop->isActive() && $seller && $seller->status !== User::STATUS_BANNED;

            $isVariantValid = true;
            if ($item->product_variant_id) {
                $isVariantValid = $variant && $variant->is_active && $variant->product_id === $product?->id;
            }

            $targetStockable = $variant ?? $product;
            $availableStock = ($isProductValid && $isShopValid && $isVariantValid && $targetStockable)
                ? $targetStockable->availableStock()
                : 0;

            if ($isProductValid && $isShopValid && $isVariantValid) {
                $currentPrice = $variant ? $variant->price : $product->price;
            } else {
                $currentPrice = $item->unit_price;
            }

            $priceDiff = $currentPrice - $item->unit_price;
            if ($priceDiff !== 0 && $isProductValid && $isShopValid && $isVariantValid) {
                $hasPriceChanges = true;
            }

            $stockStatus = 'available';
            $statusMessage = 'En stock';
            $isAvailable = false;
            $availableQty = 0;

            if (! $isProductValid || ! $isShopValid || ! $isVariantValid) {
                $stockStatus = 'unavailable';
                $statusMessage = 'Produit, variante ou boutique indisponible';
                $unavailableCount++;
            } elseif ($availableStock <= 0) {
                $stockStatus = 'out_of_stock';
                $statusMessage = 'Rupture de stock';
                $unavailableCount++;
            } elseif ($availableStock < $item->quantity) {
                $stockStatus = 'partial_stock';
                $statusMessage = "Stock partiel ({$availableStock} restant(s))";
                $isAvailable = true;
                $availableQty = $availableStock;
                $availableCount++;
            } else {
                $stockStatus = 'available';
                $statusMessage = 'En stock';
                $isAvailable = true;
                $availableQty = $item->quantity;
                $availableCount++;
            }

            $itemImage = $item->product_image
                ?? ($variant && $variant->relationLoaded('images') ? $variant->images->first()?->url : null)
                ?? ($product && $product->relationLoaded('primaryImage') ? $product->primaryImage->first()?->url : null)
                ?? ($product && $product->relationLoaded('images') ? $product->images->first()?->url : null)
                ?? null;

            $evaluatedItems[] = [
                'order_item_id'   => $item->id,
                'product_id'      => $product?->id,
                'variant_id'      => $variant?->id,
                'product_title'   => $item->product_title,
                'variant_label'   => $variant?->label() ?: ($item->variant_label ?: null),
                'product_image'   => $itemImage,
                'shop_name'       => $shop?->name ?? 'Boutique indisponible',
                'requested_qty'   => $item->quantity,
                'available_qty'   => $availableQty,
                'max_stock'       => $availableStock,
                'old_unit_price'  => $item->unit_price,
                'current_unit_price' => $currentPrice,
                'price_difference'=> $priceDiff,
                'is_available'    => $isAvailable,
                'stock_status'    => $stockStatus, // 'available' | 'partial_stock' | 'out_of_stock' | 'unavailable'
                'status_message'  => $statusMessage,
                'can_reorder'     => $isAvailable && $availableQty > 0,
            ];
        }

        return [
            'order'             => $order,
            'items'             => $evaluatedItems,
            'available_count'   => $availableCount,
            'unavailable_count' => $unavailableCount,
            'has_price_changes' => $hasPriceChanges,
            'can_reorder_any'   => $availableCount > 0,
        ];
    }

    /**
     * Transfère les articles sélectionnés de la commande passée dans le panier de l'acheteur
     * avec recalcul immédiat des prix actuels et vérification du stock.
     */
    public function reorder(User $buyer, Order $order, array $selectedItemIds): array
    {
        if ($order->buyer_id !== $buyer->id) {
            abort(403, 'Cette commande ne vous appartient pas.');
        }

        $prepared = $this->prepare($order);
        $itemsLookup = collect($prepared['items'])->keyBy('order_item_id');

        $addedCount = 0;
        $skippedCount = 0;
        $warnings = [];

        foreach ($selectedItemIds as $orderItemId) {
            $eval = $itemsLookup->get($orderItemId);
            if (! $eval) {
                continue;
            }

            if (! $eval['can_reorder']) {
                $skippedCount++;
                $warnings[] = "« {$eval['product_title']} » n'a pas pu être ajouté : {$eval['status_message']}.";
                continue;
            }

            $product = \App\Models\Product::find($eval['product_id']);
            $variant = $eval['variant_id'] ? \App\Models\ProductVariant::find($eval['variant_id']) : null;

            if (! $product) {
                $skippedCount++;
                continue;
            }

            $qtyToAdd = min($eval['requested_qty'], $eval['available_qty']);

            try {
                $cartItem = $this->cartService->addItem($buyer, $product, $qtyToAdd, $variant);
                $this->cartService->toggleSelection($cartItem, true);
                $addedCount++;

                if ($eval['price_difference'] !== 0) {
                    $diffText = $eval['price_difference'] > 0 ? "+{$eval['price_difference']}" : "{$eval['price_difference']}";
                    $warnings[] = "Prix actualisé pour « {$eval['product_title']} » ({$eval['current_unit_price']} FCFA, {$diffText} FCFA).";
                }

                if ($qtyToAdd < $eval['requested_qty']) {
                    $warnings[] = "Quantité ajustée à {$qtyToAdd} pour « {$eval['product_title']} » en raison du stock disponible.";
                }
            } catch (\Throwable $e) {
                $skippedCount++;
                $warnings[] = "Erreur lors de l'ajout de « {$eval['product_title']} » : " . $e->getMessage();
            }
        }

        return [
            'added_count'   => $addedCount,
            'skipped_count' => $skippedCount,
            'warnings'      => $warnings,
            'success'       => $addedCount > 0,
        ];
    }

    /**
     * Vérifie si une commande est éligible au rachat.
     */
    public function isEligible(Order $order): bool
    {
        return in_array($order->status, [
            Order::STATUS_CANCELLED,
            Order::STATUS_FAILED,
            Order::STATUS_COMPLETED,
            Order::STATUS_AUTO_COMPLETED,
        ]);
    }
}
