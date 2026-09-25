<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sanction extends Model
{
    use HasFactory;

    const TYPE_RESTRICTION = 'restriction';
    const TYPE_SUSPENSION  = 'suspension';
    const TYPE_BAN         = 'ban';

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ACTIVE    = 'active';
    const STATUS_EXPIRED   = 'expired';
    const STATUS_LIFTED    = 'lifted';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'reason_code',
        'reason',
        'severity',
        'starts_at',
        'expires_at',
        'created_by',
        'lifted_by',
        'lifted_at',
        'lift_reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'lifted_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suspension(): HasOne
    {
        return $this->hasOne(Suspension::class);
    }

    public function restrictions(): HasMany
    {
        return $this->hasMany(SanctionRestriction::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lifter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lifted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeSuspensions($query)
    {
        return $query->where('type', self::TYPE_SUSPENSION);
    }

    public function scopeBans($query)
    {
        return $query->where('type', self::TYPE_BAN);
    }

    public function scopeRestrictions($query)
    {
        return $query->where('type', self::TYPE_RESTRICTION);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->expires_at !== null && $this->expires_at->isPast());
    }

    public function isLifted(): bool
    {
        return $this->status === self::STATUS_LIFTED;
    }
}
