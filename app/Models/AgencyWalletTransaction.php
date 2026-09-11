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
                'text' => 'text-[#F9A01B]',
                'bg' => 'bg-[#F9A01B]/10',
            ],
            'credit_commission',
            'debit_withdrawal_failed' => [
                'sign' => '+',
                'text' => 'text-[#016837]',
                'bg' => 'bg-[#016837]/10',
            ],
            'debit_withdrawal' => [
                'sign' => '-',
                'text' => 'text-[#E30613]',
                'bg' => 'bg-[#E30613]/10',
            ],
            default => ['sign' => '', 'text' => 'text-gray-500', 'bg' => 'bg-gray-100'],
        };
    }
}
