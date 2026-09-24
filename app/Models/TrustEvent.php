<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustEvent extends Model
{
    protected $fillable = [
        'user_id',
        'role_context',
        'type',
        'direction',
        'points_raw',
        'points_applied',
        'trust_before',
        'trust_after',
        'weight',
        'reason',
        'description',
        'reference_type',
        'reference_id',
        'is_critical',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_critical' => 'boolean',
            'weight'      => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
