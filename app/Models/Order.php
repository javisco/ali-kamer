<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    const STATUS_PENDING                    = 'pending';
    const STATUS_AWAITING_PAYMENT           = 'awaiting_payment';
    const STATUS_PAID                       = 'paid';
    const STATUS_PREPARING                  = 'preparing';
    const STATUS_REGISTERED_ORIGIN          = 'registered_origin';
    const STATUS_IN_TRANSIT                 = 'in_transit';
    const STATUS_ARRIVED_DESTINATION        = 'arrived_destination';
    const STATUS_AWAITING_BUYER_CONFIRMATION = 'awaiting_buyer_confirmation';
    const STATUS_COMPLETED                  = 'completed';
    const STATUS_AUTO_COMPLETED             = 'auto_completed';
    const STATUS_DISPUTED                   = 'disputed';
    const STATUS_CANCELLED                  = 'cancelled';
    const STATUS_FAILED                     = 'failed';

    protected $fillable = [
        'reference',
        'buyer_id',
        'shop_id',
        'status',
        'subtotal',
        'shipping_fee',
        'protection_fee',
        'gateway_fee',
        'total_amount',
        'platform_commission',
        'agency_commission',
        'gateway_payout_fee',
        'net_amount',
        'financial_snapshot',
        'deposit_code',
        'otp_code',
        'otp_expires_at',
        'otp_used_at',
        'timer_deadline',
        'paid_at',
        'preparing_at',
        'shipped_at',
        'arrived_at',
        'completed_at',
        'cancelled_at',
        'buyer_note',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'financial_snapshot' => 'array',
            'otp_expires_at'     => 'datetime',
            'otp_used_at'        => 'datetime',
            'timer_deadline'     => 'datetime',
            'paid_at'            => 'datetime',
            'completed_at'       => 'datetime',
            'cancelled_at'       => 'datetime',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(OrderShipment::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return in_array($this->status, [
            self::STATUS_PAID,
            self::STATUS_PREPARING,
            self::STATUS_REGISTERED_ORIGIN,
            self::STATUS_IN_TRANSIT,
            self::STATUS_ARRIVED_DESTINATION,
            self::STATUS_AWAITING_BUYER_CONFIRMATION,
        ]);
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_AUTO_COMPLETED,
        ]);
    }

    public function canBeDisputed(): bool
    {
        return $this->status === self::STATUS_AWAITING_BUYER_CONFIRMATION
            || $this->status === self::STATUS_ARRIVED_DESTINATION;
    }

    public function isTimerExpired(): bool
    {
        return $this->timer_deadline && now()->isAfter($this->timer_deadline);
    }

    // Génère la référence : ALK-2026-00001
    public static function generateReference(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'ALK-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
