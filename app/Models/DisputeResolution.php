<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisputeResolution extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dispute_id',
        'resolved_by',
        'decision',
        'reason',
        'buyer_amount',
        'seller_amount',
        'platform_amount',
        'refund_amount',
        'seller_compensation',
        'shipping_decision',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'buyer_amount' => 'integer',
            'seller_amount' => 'integer',
            'platform_amount' => 'integer',
            'refund_amount' => 'integer',
            'seller_compensation' => 'integer',
        ];
    }

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
