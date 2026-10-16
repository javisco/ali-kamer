<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    public const STATUS_PENDING   = 'pending';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED    = 'failed';

    public const KIND_LATE_CANCEL = 'late_cancel';
    public const KIND_DISPUTE     = 'dispute';

    protected $fillable = [
        'order_id',
        'kind',
        'paid_amount',
        'protection_fee_retained',
        'refund_amount',
        'status',
        'idempotency_key',
        'provider_reference',
        'error_message',
        'initiated_by',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function isSucceeded(): bool
    {
        return $this->status === self::STATUS_SUCCEEDED;
    }
}
