<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecretaryCounter extends Model
{
    protected $fillable = [
        'user_id', 'agency_counter_id', 'is_primary',
    ];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    // Le secrétaire
    public function secretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Le guichet
    public function counter(): BelongsTo
    {
        return $this->belongsTo(AgencyCounter::class, 'agency_counter_id');
    }

}