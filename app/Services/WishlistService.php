<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wishlist;

class WishlistService
{
    // Ajouter ou retirer des favoris (toggle)
    public function toggle(User $user, int $productId): bool
    {
        $existing = Wishlist::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($existing) {
            $existing->delete();
            return false; // Retiré des favoris
        }

        Wishlist::create([
            'user_id'    => $user->id,
            'product_id' => $productId,
        ]);

        return true; // Ajouté aux favoris
    }

    // Vérifie si un produit est en favori
    public function isWishlisted(User $user, int $productId): bool
    {
        return Wishlist::where('user_id', $user->id)
                       ->where('product_id', $productId)
                       ->exists();
    }

    // Liste des favoris de l'utilisateur
    public function getWishlist(User $user)
    {
        return Wishlist::where('user_id', $user->id)
                       ->with(['product.images', 'product.shop'])
                       ->latest()
                       ->paginate(20);
    }

    // Nombre de favoris (pour le badge navbar)
    public function count(User $user): int
    {
        return Wishlist::where('user_id', $user->id)->count();
    }
}