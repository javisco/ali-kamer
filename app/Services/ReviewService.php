<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    // ── ACHETEUR NOTE LE PRODUIT ET LA BOUTIQUE ───────────────────────

    public function reviewByBuyer(
        Order $order,
        User $buyer,
        int $productRating,
        int $shopRating,
        ?string $body = null
    ): void {

        // Vérifier que la commande est bien terminée
        abort_unless($order->isCompleted(), 403);

        // Vérifier que c'est bien l'acheteur de la commande
        abort_unless($order->buyer_id === $buyer->id, 403);

        DB::transaction(function () use ($order, $buyer, $productRating, $shopRating, $body) {

            $product = $order->items->first()->product;

            // Note sur le produit
            Review::firstOrCreate(
                [
                    'order_id'      => $order->id,
                    'reviewer_id'   => $buyer->id,
                    'reviewee_type' => 'product',
                    'reviewee_id'   => $product->id,
                ],
                [
                    'rating'      => $productRating,
                    'body'        => $body,
                    'is_verified' => true,
                ]
            );

            // Note sur la boutique
            Review::firstOrCreate(
                [
                    'order_id'      => $order->id,
                    'reviewer_id'   => $buyer->id,
                    'reviewee_type' => 'shop',
                    'reviewee_id'   => $order->shop_id,
                ],
                [
                    'rating'      => $shopRating,
                    'is_verified' => true,
                ]
            );

            // Recalculer le score de la boutique
            $this->recalculateShopScore($order->shop_id);
        });
    }

    // ── VENDEUR NOTE L'ACHETEUR ───────────────────────────────────────

    public function reviewBySellerForBuyer(
        Order $order,
        User $seller,
        int $rating,
        ?string $body = null
    ): void {

        abort_unless($order->isCompleted(), 403);
        abort_unless($order->shop->user_id === $seller->id, 403);

        DB::transaction(function () use ($order, $seller, $rating, $body) {

            Review::firstOrCreate(
                [
                    'order_id'      => $order->id,
                    'reviewer_id'   => $seller->id,
                    'reviewee_type' => 'buyer',
                    'reviewee_id'   => $order->buyer_id,
                ],
                [
                    'rating'      => $rating,
                    'body'        => $body,
                    'is_verified' => true,
                ]
            );

            // Recalculer le trust_score de l'acheteur
            $this->recalculateBuyerTrustScore($order->buyer_id);
        });
    }

    // ── RECALCULER LE SCORE DE LA BOUTIQUE ────────────────────────────

    // Score basé sur la moyenne des notes reçues (0-100)
    public function recalculateShopScore(int $shopId): void
    {
        $avg = Review::forShop($shopId)
            ->where('is_flagged', false)
            ->avg('rating');

        // Convertir la moyenne (1-5) en score (0-100)
        $score = $avg ? (int) round(($avg / 5) * 100) : 100;

        \App\Models\Shop::find($shopId)?->update(['score' => $score]);
    }

    // ── RECALCULER LE TRUST SCORE DE L'ACHETEUR ──────────────────────

    // Score de fiabilité acheteur basé sur ses avis reçus (0-100)
    public function recalculateBuyerTrustScore(int $userId): void
    {
        $avg = Review::forBuyer($userId)
            ->where('is_flagged', false)
            ->avg('rating');

        $score = $avg ? (int) round(($avg / 5) * 100) : 100;

        $user = User::find($userId);
        if (! $user) return;

        $user->update(['trust_score' => $score]);

        // Si score < 30 : prépaiement obligatoire
        $user->update([
            'prepayment_required' => $score < 30,
        ]);
    }
}
