<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Identifiants protégés associés à une blacklist V2.
 *
 * Les valeurs sensibles ne sont jamais stockées en clair : seul le hash
 * dérivé du pepper serveur est conservé.
 */
class BlacklistIdentifier extends Model
{
    protected $fillable = [
        'blacklist_id',
        'type',
        'value_hash',
        'value_masked',
        'signal_strength',
    ];

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(BlacklistV2::class, 'blacklist_id');
    }
}
