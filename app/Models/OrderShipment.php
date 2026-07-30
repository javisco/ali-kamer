<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'shipping_included',
        'transport_fee',
        'transport_fee_paid',
        'transport_fee_paid_at',
        'origin_counter_id',
        'destination_counter_id',
        'registered_by',
        'validated_by',
        'local_carrier_name',
        'local_carrier_phone',
        'recipient_name',
        'recipient_phone',
        'destination_city',
        'registered_at',
        'departed_at',
        'arrived_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_included'      => 'boolean',
            'transport_fee_paid'     => 'boolean',
            'transport_fee_paid_at'  => 'datetime',
            'registered_at'          => 'datetime',
            'departed_at'            => 'datetime',
            'arrived_at'             => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function originCounter(): BelongsTo
    {
        return $this->belongsTo(AgencyCounter::class, 'origin_counter_id');
    }

    public function destinationCounter(): BelongsTo
    {
        return $this->belongsTo(AgencyCounter::class, 'destination_counter_id');
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
