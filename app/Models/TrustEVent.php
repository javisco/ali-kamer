<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/TrustEvent.php
class TrustEvent extends Model
{
    protected $fillable = [
        'user_id', 'role_context', 'type', 'direction',
        'points_raw', 'points_applied', 'trust_before', 'trust_after',
        'weight', 'reason', 'description', 'reference_type',
        'reference_id', 'is_critical', 'created_by',
    ];

    protected function casts(): array
    {
        return ['is_critical' => 'boolean', 'weight' => 'float'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/BlacklistIdentifier.php
class BlacklistIdentifier extends Model
{
    protected $fillable = [
        'blacklist_id', 'type', 'value_hash', 'value_masked', 'signal_strength'
    ];

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(Blacklist::class);
    }
}

// app/Models/AccountLink.php
class AccountLink extends Model
{
    protected $fillable = [
        'user_a_id', 'user_b_id', 'link_type', 'confidence',
        'detected_at', 'reviewed', 'reviewed_by', 'review_note', 'reviewed_at'
    ];

    protected function casts(): array
    {
        return ['reviewed' => 'boolean', 'detected_at' => 'datetime'];
    }

    public function userA(): BelongsTo { return $this->belongsTo(User::class, 'user_a_id'); }
    public function userB(): BelongsTo { return $this->belongsTo(User::class, 'user_b_id'); }

    // Le compte "autre" par rapport à un userId donné
    public function linkedUser(): BelongsTo
    {
        $otherId = auth()->id() === $this->user_a_id ? $this->user_b_id : $this->user_a_id;
        return $this->belongsTo(User::class, 'user_' . ($this->user_a_id === $otherId ? 'a' : 'b') . '_id');
    }
}

// app/Models/VelocityLog.php
class VelocityLog extends Model
{
    const UPDATED_AT = null; // Immuable

    protected $fillable = ['user_id', 'ip_address', 'action', 'metadata'];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }
}
