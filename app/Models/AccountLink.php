<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountLink extends Model
{
    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'link_type',
        'confidence',
        'detected_at',
        'reviewed',
        'reviewed_by',
        'review_note',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed'    => 'boolean',
            'detected_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    /**
     * Retourne explicitement l'autre compte, sans dépendre de auth()->id().
     * Le moteur KYC pourra donc être utilisé depuis un job/CLI plus tard.
     */
    public function otherUser(int $userId): ?User
    {
        $otherId = $this->user_a_id === $userId
            ? $this->user_b_id
            : ($this->user_b_id === $userId ? $this->user_a_id : null);

        return $otherId ? User::find($otherId) : null;
    }
}
