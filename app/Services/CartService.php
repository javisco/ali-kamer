<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    // ── Récupérer ou créer le panier de l'utilisateur ─────────────────

    public function getOrCreate(User $user): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id]
        );
    }

    // ── Ajouter un article au panier ──────────────────────────────────

    public function addItem(
        User $user,
        Product $product,
        int $quantity,
        ?ProductVariant $variant = null
    ): CartItem {

        // Vérifier que le produit est visible
        if (! $product->isVisible()) {
            throw ValidationException::withMessages([
                'product' => 'Ce produit n\'est plus disponible.',
            ]);
        }

        // Vérifier le stock selon variante ou produit
        $availableStock = $variant
            ? $variant->availableStock()
            : $product->availableStock();

        if ($quantity > $availableStock) {
            throw ValidationException::withMessages([
                'quantity' => "Stock insuffisant. Disponible : {$availableStock}.",
            ]);
        }

        // Vérifier la quantité minimum
        if ($quantity < $product->min_quantity) {
            throw ValidationException::withMessages([
                'quantity' => "Quantité minimum : {$product->min_quantity}.",
            ]);
        }

        // Prix de référence
        $price = $variant ? $variant->price : $product->price;

        $cart = $this->getOrCreate($user);

        return DB::transaction(function () use (
            $cart, $product, $variant, $quantity, $price
        ) {
            // Si l'article existe déjà dans le panier — mettre à jour la quantité
            $existing = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('product_variant_id', $variant?->id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity'   => $existing->quantity + $quantity,
                    'unit_price' => $price, // Mettre à jour le prix
                ]);
                return $existing->fresh();
            }

            return CartItem::create([
                'cart_id'            => $cart->id,
                'product_id'         => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity'           => $quantity,
                'unit_price'         => $price,
            ]);
        });
    }

    // ── Mettre à jour la quantité ─────────────────────────────────────

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($item);
            return;
        }

        // Vérifier le stock
        $available = $item->variant
            ? $item->variant->availableStock()
            : $item->product->availableStock();

        if ($quantity > $available) {
            throw ValidationException::withMessages([
                'quantity' => "Stock insuffisant. Disponible : {$available}.",
            ]);
        }

        $item->update(['quantity' => $quantity]);
    }

    // ── Supprimer un article ──────────────────────────────────────────

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    // ── Vider le panier ───────────────────────────────────────────────

    public function clear(User $user): void
    {
        $cart = Cart::where('user_id', $user->id)->first();
        $cart?->items()->delete();
    }

    // ── Nombre d'articles (pour le badge navbar) ──────────────────────

    public function count(User $user): int
    {
        $cart = Cart::where('user_id', $user->id)
                    ->with('items')
                    ->first();

        return $cart?->itemsCount() ?? 0;
    }
}