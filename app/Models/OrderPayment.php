<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id', 'method', 'status',
        'provider_reference', 'idempotency_key',
        'payer_phone', 'payer_operator',
        'manual_transaction_id', 'manual_validated_by',
        'manual_validated_at', 'provider_response', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response'    => 'array',
            'manual_validated_at'  => 'datetime',
            'paid_at'              => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manual_validated_by');
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    // Génère un UUID idempotent unique
    public static function generateIdempotencyKey(): string
    {
        return (string) Str::uuid();
    }
}