<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'order_id', 'reviewer_id', 'reviewee_type', 'reviewee_id',
        'rating', 'body', 'is_verified', 'is_flagged', 'is_contested',
    ];

    protected function casts(): array
    {
        return [
            'is_verified'  => 'boolean',
            'is_flagged'   => 'boolean',
            'is_contested' => 'boolean',
            'rating'       => 'integer',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    // Avis sur un produit
    public function scopeForProduct($q, int $productId)
    {
        return $q->where('reviewee_type', 'product')
                 ->where('reviewee_id', $productId);
    }

    // Avis sur une boutique
    public function scopeForShop($q, int $shopId)
    {
        return $q->where('reviewee_type', 'shop')
                 ->where('reviewee_id', $shopId);
    }

    // Avis sur un acheteur
    public function scopeForBuyer($q, int $userId)
    {
        return $q->where('reviewee_type', 'buyer')
                 ->where('reviewee_id', $userId);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    // Étoiles sous forme de texte pour l'affichage
    public function stars(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}