<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    // Types de transactions disponibles
    const TYPE_CREDIT_ESCROW        = 'credit_escrow';
    const TYPE_CREDIT_BUY        = 'buy';
    const TYPE_DEBIT_ESCROW         = 'debit_escrow';
    const TYPE_CREDIT_AVAILABLE     = 'credit_available';
    const TYPE_DEBIT_WITHDRAWAL     = 'debit_withdrawal';
    const TYPE_DEBIT_COMMISSION     = 'debit_commission';
    const TYPE_CREDIT_REFUND        = 'credit_refund';
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
        return str_starts_with($this->type, 'credit_');
    }

    // Vérifie si c'est un débit (sortie d'argent)
    public function isDebit(): bool
    {
        return str_starts_with($this->type, 'debit_');
    }

    // Libellé lisible du type de transaction pour l'affichage
    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_CREDIT_ESCROW        => 'Paiement reçu (séquestre)',
            self::TYPE_DEBIT_ESCROW         => 'Libération séquestre',
            self::TYPE_CREDIT_AVAILABLE     => 'Fonds disponibles',
            self::TYPE_DEBIT_WITHDRAWAL     => 'Retrait MoMo',
            self::TYPE_DEBIT_COMMISSION     => 'Commission plateforme',
            self::TYPE_CREDIT_REFUND        => 'Remboursement',
            self::TYPE_CREDIT_SECRETARY     => 'Commission secrétaire',
            self::TYPE_DEBIT_TRANSPORT_FEE  => 'Frais transport',
            self::TYPE_CREDIT_TRANSPORT_FEE => 'Remboursement transport',
            self::TYPE_CREDIT_BUY => 'commande passer',
            default                         => $this->type,
        };
    }
}
