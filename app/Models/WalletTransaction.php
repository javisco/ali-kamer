<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    // Types de transactions disponibles

    const TYPE_CREDIT_BUY           = 'buy';
    const TYPE_CREDIT_ESCROW        = 'credit_escrow';
    const TYPE_DEBIT_ESCROW         = 'debit_escrow';
    const TYPE_CREDIT_AVAILABLE     = 'credit_available';
    const TYPE_DEBIT_WITHDRAWAL     = 'debit_withdrawal';
    const TYPE_DEBIT_COMMISSION     = 'debit_commission';
    const TYPE_CREDIT_REFUND        = 'credit_refund';
    const TYPE_DEBIT_REFUND         = 'debit_refund';
    const TYPE_CREDIT_SECRETARY     = 'credit_secretary';
    const TYPE_DEBIT_TRANSPORT_FEE  = 'debit_transport_fee';
    const TYPE_CREDIT_TRANSPORT_FEE = 'credit_transport_fee';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'ref_type',
        'ref_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'integer',
            'balance_after' => 'integer',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────

    // L'utilisateur concerné par cette transaction
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    // Vérifie si c'est un crédit (entrée d'argent)
    public function isCredit(): bool
    {
        return str_starts_with($this->type, 'credit_') || $this->type === self::TYPE_CREDIT_BUY;
    }

    // Vérifie si c'est un débit réel (sortie d'argent)
    public function isDebit(): bool
    {
        return str_starts_with($this->type, 'debit_') && $this->type !== self::TYPE_DEBIT_ESCROW;
    }

    // Transfert interne de séquestre vers disponible
    public function isEscrowTransfer(): bool
    {
        return $this->type === self::TYPE_DEBIT_ESCROW;
    }

    // Concerne le compte de séquestre
    public function isEscrow(): bool
    {
        return in_array($this->type, [self::TYPE_CREDIT_ESCROW, self::TYPE_DEBIT_ESCROW]);
    }

    // Libellé du type de solde concerné
    public function balanceLabel(): string
    {
        return match ($this->type) {
            self::TYPE_CREDIT_ESCROW, self::TYPE_DEBIT_ESCROW => 'Séquestre restant',
            default => 'Solde disponible',
        };
    }

    // Libellé lisible du type de transaction pour l'affichage
    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_CREDIT_ESCROW        => 'Paiement reçu (séquestre)',
            self::TYPE_DEBIT_ESCROW         => 'Transfert vers solde disponible',
            self::TYPE_CREDIT_AVAILABLE     => 'Fonds débloqués (disponible)',
            self::TYPE_DEBIT_WITHDRAWAL     => 'Retrait Mobile Money',
            self::TYPE_DEBIT_COMMISSION     => 'Commission plateforme',
            self::TYPE_CREDIT_REFUND        => 'Remboursement acheteur',
            self::TYPE_DEBIT_REFUND         => 'Débit litige / remboursement',
            self::TYPE_CREDIT_SECRETARY     => 'Commission secrétaire',
            self::TYPE_DEBIT_TRANSPORT_FEE  => 'Frais transport',
            self::TYPE_CREDIT_TRANSPORT_FEE => 'Remboursement transport',
            self::TYPE_CREDIT_BUY           => 'Paiement commande',
            default                         => $this->type,
        };
    }
}
