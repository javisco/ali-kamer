<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = [
        'buyer_id',
        'shop_id',
        'product_id',
        'last_message_at',
        'is_archived',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'is_archived'     => 'boolean',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────

    // L'acheteur qui a initié la conversation
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // La boutique concernée
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Le produit de départ (si la conversation a démarré depuis une fiche)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Tous les messages de la conversation
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('sent_at');
    }

    // Dernier message — pour l'aperçu dans la liste
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany('sent_at');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    // Conversations actives uniquement
    public function scopeActive($q)
    {
        return $q->where('is_archived', false);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    // Nombre de messages non lus pour un utilisateur donné
    public function unreadCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
