<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Nouvelle blacklist utilisée par IdentityResolver / TrustService.
 *
 * L'ancienne table `blacklist` reste volontairement intacte pour ne pas
 * casser l'ancien AuthService. Le moteur de confiance travaille désormais
 * sur `blacklists_v2`, conformément à la conception V3.
 */
class BlacklistV2 extends Model
{
    use HasFactory;

    protected $table = 'blacklists_v2';

    protected $fillable = [
        'user_id',
        'status',
        'severity',
        'reason_code',
        'reason',
        'description',
        'trigger_type',
        'blocked_at',
        'expires_at',
        'lifted_at',
        'created_by',
        'lifted_by',
        'lift_reason',
    ];

    protected function casts(): array
    {
        return [
            'blocked_at' => 'datetime',
            'expires_at' => 'datetime',
            'lifted_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lifter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lifted_by');
    }

    public function identifiers(): HasMany
    {
        return $this->hasMany(BlacklistIdentifier::class, 'blacklist_id');
    }
}
