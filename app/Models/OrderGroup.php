<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderGroup extends Model
{
    const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    const STATUS_PAID             = 'paid';
    const STATUS_FAILED           = 'failed';
    const STATUS_CANCELLED        = 'cancelled';

    protected $fillable = [
        'reference', 'buyer_id', 'status',
        'subtotal', 'protection_fee', 'gateway_fee', 'total_amount',
        'financial_snapshot', 'paid_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'financial_snapshot' => 'array',
            'paid_at'            => 'datetime',
            'cancelled_at'       => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(OrderGroupPayment::class);
    }

    public static function generateReference(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'AKG-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}