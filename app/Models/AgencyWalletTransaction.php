<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgencyWalletTransaction extends Model
{
    protected $fillable = [
        'agency_id',
        'type',
        'amount',
        'balance_after',
        'order_id',
        'note',
    ];

    protected function casts(): array
    {
        return ['amount' => 'integer', 'balance_after' => 'integer'];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function isCredit(): bool
    {
        return in_array($this->type, [
            'credit_commission_pending',
            'credit_commission',
        ]);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'credit_commission_pending' => 'Commission (en attente — colis déposé)',
            'credit_commission'         => 'Commission libérée (colis livré)',
            'debit_withdrawal'          => 'Retrait',
            'debit_withdrawal_failed'   => 'Retrait échoué (recrédité)',
            default                     => $this->type,
        };
    }
    // public function isCredit(): bool
    // {
    //     return $this->type === 'credit_commission';
    // }

    // public function typeLabel(): string
    // {
    //     return match ($this->type) {
    //         'credit_commission'      => 'Commission colis',
    //         'debit_withdrawal'       => 'Retrait',
    //         'debit_withdrawal_failed' => 'Retrait échoué (recrédité)',
    //         default                  => $this->type,
    //     };
    // }
    public function displayMeta(): array
    {
        return match ($this->type) {
            'credit_commission_pending' => [
                'sign' => '+',
                'text' => 'text-accent-500',
                'bg' => 'bg-accent-500/10',
            ],
            'credit_commission',
            'debit_withdrawal_failed' => [
                'sign' => '+',
                'text' => 'text-primary-600',
                'bg' => 'bg-primary-600/10',
            ],
            'debit_withdrawal' => [
                'sign' => '-',
                'text' => 'text-danger',
                'bg' => 'bg-danger/10',
            ],
            default => ['sign' => '', 'text' => 'text-slate-500', 'bg' => 'bg-slate-100'],
        };
    }
}
