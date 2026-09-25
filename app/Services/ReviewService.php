<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function __construct(
        private ?TrustService $trustService = null
    ) {
        $this->trustService = $trustService ?? app(TrustService::class);
    }

    // ── ACHETEUR NOTE LE PRODUIT ET LA BOUTIQUE ───────────────────────

    public function reviewByBuyer(
        Order $order,
        User $buyer,
        int $productRating,
        int $shopRating,
        ?string $body = null,
        array $categoryScores = []
    ): void {

        // Vérifier que la commande est bien terminée
        abort_unless($order->isCompleted(), 403);

        // Vérifier que c'est bien l'acheteur de la commande
        abort_unless($order->buyer_id === $buyer->id, 403);

        // Anti-fraude : interdire l'auto-évaluation directe
        if ($order->shop && $order->shop->user_id === $buyer->id) {
            abort(403, 'Vous ne pouvez pas évaluer votre propre boutique.');
        }

        // Anti-fraude : détection de collusion / comptes liés
        $isFlagged = false;
        $sellerId = $order->shop?->user_id;
        if ($sellerId) {
            $isLinked = \App\Models\AccountLink::query()
                ->where(function ($q) use ($buyer, $sellerId) {
                    $q->where(fn ($sub) => $sub->where('user_a_id', $buyer->id)->where('user_b_id', $sellerId))
                      ->orWhere(fn ($sub) => $sub->where('user_a_id', $sellerId)->where('user_b_id', $buyer->id));
                })
                ->where('confidence', '>=', 80)
                ->exists();

            if ($isLinked) {
                $isFlagged = true;
            }
        }

        DB::transaction(function () use ($order, $buyer, $productRating, $shopRating, $body, $categoryScores, $isFlagged) {

            $product = $order->items->first()?->product;

            // Note sur le produit
            if ($product) {
                $productReview = Review::firstOrCreate(
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
                        'is_flagged'  => $isFlagged,
                    ]
                );

                $this->attachCategoryScores($productReview, $categoryScores);
            }

            // Note sur la boutique
            $shopReview = Review::firstOrCreate(
                [
                    'order_id'      => $order->id,
                    'reviewer_id'   => $buyer->id,
                    'reviewee_type' => 'shop',
                    'reviewee_id'   => $order->shop_id,
                ],
                [
                    'rating'      => $shopRating,
                    'body'        => $body,
                    'is_verified' => true,
                    'is_flagged'  => $isFlagged,
                ]
            );

            $this->attachCategoryScores($shopReview, $categoryScores);

            // Recalculer le score de la boutique
            $this->recalculateShopScore($order->shop_id);

            // Événement Trust si évaluation positive et non-suspecte
            if (! $isFlagged && $shopRating >= 4 && $order->shop?->user) {
                $this->trustService?->record(
                    user: $order->shop->user,
                    type: 'seller_reliable',
                    roleContext: 'seller',
                    reason: "Avis positif reçu ({$shopRating}/5) — commande {$order->reference}",
                    referenceType: 'Order',
                    referenceId: $order->id
                );
            }
        });
    }

    private function attachCategoryScores(Review $review, array $categoryScores): void
    {
        foreach ($categoryScores as $key => $score) {
            $scoreVal = (int) $score;
            if ($scoreVal < 1 || $scoreVal > 5) {
                continue;
            }

            $category = is_numeric($key)
                ? \App\Models\RatingCategory::find($key)
                : \App\Models\RatingCategory::where('slug', $key)->first();

            if ($category) {
                \App\Models\RatingScore::updateOrCreate(
                    [
                        'review_id'   => $review->id,
                        'category_id' => $category->id,
                    ],
                    ['score' => $scoreVal]
                );
            }
        }
    }

    // ── VENDEUR NOTE L'ACHETEUR ───────────────────────────────────────

    public function reviewBySellerForBuyer(
        Order $order,
        User $seller,
        int $rating,
        ?string $body = null,
        array $categoryScores = []
    ): void {

        abort_unless($order->isCompleted(), 403);
        abort_unless($order->shop->user_id === $seller->id, 403);

        if ($order->buyer_id === $seller->id) {
            abort(403, 'Vous ne pouvez pas vous auto-évaluer.');
        }

        $isFlagged = false;
        $isLinked = \App\Models\AccountLink::query()
            ->where(function ($q) use ($order, $seller) {
                $q->where(fn ($sub) => $sub->where('user_a_id', $order->buyer_id)->where('user_b_id', $seller->id))
                  ->orWhere(fn ($sub) => $sub->where('user_a_id', $seller->id)->where('user_b_id', $order->buyer_id));
            })
            ->where('confidence', '>=', 80)
            ->exists();

        if ($isLinked) {
            $isFlagged = true;
        }

        DB::transaction(function () use ($order, $seller, $rating, $body, $categoryScores, $isFlagged) {

            $review = Review::firstOrCreate(
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
                    'is_flagged'  => $isFlagged,
                ]
            );

            $this->attachCategoryScores($review, $categoryScores);

            // Recalculer le trust_score de l'acheteur
            $this->recalculateBuyerTrustScore($order->buyer_id);

            // Événement Trust si évaluation positive et non-suspecte
            if (! $isFlagged && $rating >= 4 && $order->buyer) {
                $this->trustService?->record(
                    user: $order->buyer,
                    type: 'buyer_reliable',
                    roleContext: 'buyer',
                    reason: "Avis positif reçu ({$rating}/5) — commande {$order->reference}",
                    referenceType: 'Order',
                    referenceId: $order->id
                );
            }
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
