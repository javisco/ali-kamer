<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderGroupPayment extends Model
{
    protected $fillable = [
        'order_group_id',
        'method',
        'status',
        'provider_reference',
        'idempotency_key',
        'payer_phone',
        'payer_operator',
        'provider_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'paid_at'            => 'datetime',
        ];
    }

    public function orderGroup(): BelongsTo
    {
        return $this->belongsTo(OrderGroup::class);
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    public static function generateIdempotencyKey(): string
    {
        return (string) Str::uuid();
    }
}
