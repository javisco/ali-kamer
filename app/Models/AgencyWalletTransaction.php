<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgencyWalletTransaction extends Model
{
    protected $fillable = [
        'agency_id', 'type', 'amount',
        'balance_after', 'order_id', 'note',
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
        return $this->type === 'credit_commission';
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'credit_commission'      => 'Commission colis',
            'debit_withdrawal'       => 'Retrait',
            'debit_withdrawal_failed' => 'Retrait échoué (recrédité)',
            default                  => $this->type,
        };
    }
}