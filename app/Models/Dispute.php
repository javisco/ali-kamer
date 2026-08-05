<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispute extends Model
{
    // Motifs disponibles avec leurs libellés
    const TYPES = [
        'not_received'        => 'Produit non reçu',
        'not_conform'         => 'Produit non conforme',
        'damaged'             => 'Produit endommagé',
        'incorrect'           => 'Produit incorrect',
        'incomplete'          => 'Produit incomplet',
        'empty_package'       => 'Colis vide',
        'seller_unresponsive' => 'Vendeur non réactif',
    ];

    // Décisions possibles avec leurs libellés
    const RESOLUTIONS = [
        'refund_buyer'    => 'Remboursement intégral acheteur',
        'pay_seller'      => 'Paiement vendeur',
        'partial_refund'  => 'Remboursement partiel',
        'return_required' => 'Retour produit obligatoire',
        'buyer_bad_faith' => 'Acheteur de mauvaise foi',
    ];

    protected $fillable = [
        'order_id', 'initiator_id', 'type', 'description',
        'status', 'resolution', 'resolution_amount',
        'resolution_note', 'resolver_id',
        'seller_reply_deadline', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'seller_reply_deadline' => 'datetime',
            'resolved_at'           => 'datetime',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────

    // La commande concernée
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Qui a ouvert le litige
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    // Admin qui a tranché
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolver_id');
    }

    // Toutes les preuves soumises
    public function evidences(): HasMany
    {
        return $this->hasMany(DisputeEvidence::class);
    }

    // Preuves de l'acheteur uniquement
    public function buyerEvidences(): HasMany
    {
        return $this->hasMany(DisputeEvidence::class)
                    ->where('submitted_by', $this->order->buyer_id);
    }

    // Preuves du vendeur uniquement
    public function sellerEvidences(): HasMany
    {
        return $this->hasMany(DisputeEvidence::class)
                    ->where('submitted_by', $this->order->shop->user_id);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isOpen(): bool     { return $this->status === 'open'; }
    public function isResolved(): bool { return $this->status === 'resolved'; }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function resolutionLabel(): string
    {
        return self::RESOLUTIONS[$this->resolution] ?? '';
    }

    // Le vendeur a dépassé son délai de réponse
    public function sellerReplyExpired(): bool
    {
        return $this->seller_reply_deadline
            && now()->isAfter($this->seller_reply_deadline);
    }
}