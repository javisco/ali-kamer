<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    // Types de transactions disponibles

    const TYPE_CREDIT_BUY        = 'buy';
    const TYPE_CREDIT_ESCROW        = 'credit_escrow';
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
            self::TYPE_CREDIT_AVAILABLE     => 'Fonds libérés (transfert interne)',
            self::TYPE_DEBIT_WITHDRAWAL     => 'Retrait MoMo',
            self::TYPE_DEBIT_COMMISSION     => 'Commission plateforme',
            self::TYPE_CREDIT_REFUND        => 'Remboursement',
            self::TYPE_CREDIT_SECRETARY     => 'Commission secrétaire',
            self::TYPE_DEBIT_TRANSPORT_FEE  => 'Frais transport',
            self::TYPE_CREDIT_TRANSPORT_FEE => 'Remboursement transport',
            self::TYPE_CREDIT_BUY => 'Commande passée',   // au lieu de "commande passer"
            default                         => $this->type,
        };
    }
    // ── AFFICHAGE COHÉRENT (couleur/signe indépendants du préfixe brut) ──
    // isCredit() reste pour la logique comptable interne ; displayMeta()
    // est LA source de vérité pour tout affichage dans les vues.
    public function displayMeta(): array
    {
        return match ($this->type) {
            // Argent réel qui vient d'entrer, mais VERROUILLÉ (séquestre).
            self::TYPE_CREDIT_ESCROW => [
                'sign' => '+',
                'text' => 'text-accent-500',
                'bg' => 'bg-accent-500/10',
            ],
            // Pas de nouvel argent : simple déplacement séquestre → disponible.
            self::TYPE_CREDIT_AVAILABLE => [
                'sign' => '→',
                'text' => 'text-primary-600',
                'bg' => 'bg-primary-50',
            ],
            self::TYPE_CREDIT_TRANSPORT_FEE,
            self::TYPE_CREDIT_REFUND,
            self::TYPE_CREDIT_SECRETARY => [
                'sign' => '+',
                'text' => 'text-primary-600',
                'bg' => 'bg-primary-600/10',
            ],
            // TYPE_CREDIT_BUY : l'acheteur DÉPENSE — jamais vert malgré le préfixe.
            self::TYPE_CREDIT_BUY,
            self::TYPE_DEBIT_WITHDRAWAL,
            self::TYPE_DEBIT_COMMISSION,
            self::TYPE_DEBIT_TRANSPORT_FEE => [
                'sign' => '-',
                'text' => 'text-danger',
                'bg' => 'bg-danger/10',
            ],
            default => ['sign' => '', 'text' => 'text-slate-500', 'bg' => 'bg-slate-100'],
        };
    }

    // Cache le mouvement interne "sortie de séquestre" — pur bruit comptable
    // pour l'utilisateur, déjà représenté par la ligne credit_available juste après.
    public function isInternalNoise(): bool
    {
        return $this->type === self::TYPE_DEBIT_ESCROW;
    }
}
