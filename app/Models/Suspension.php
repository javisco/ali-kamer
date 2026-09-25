<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suspension extends Model
{
    use HasFactory;

    const STATUS_ACTIVE  = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_LIFTED  = 'lifted';

    protected $fillable = [
        'sanction_id',
        'starts_at',
        'expires_at',
        'status',
        'lifted_at',
        'lifted_by',
        'lift_reason',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'lifted_at' => 'datetime',
        ];
    }

    public function sanction(): BelongsTo
    {
        return $this->belongsTo(Sanction::class);
    }

    public function lifter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lifted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('expires_at', '>', now());
    }

    public function scopeDue($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('expires_at', '<=', now());
    }

    public function isDue(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->expires_at->isPast();
    }

    public function remainingSeconds(): int
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }

        return max(0, now()->diffInSeconds($this->expires_at, false));
    }

    public function formattedExpiry(): string
    {
        return $this->expires_at->translatedFormat('d F Y à H:i');
    }
}
