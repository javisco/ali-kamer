<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'contact_phone',
        'contact_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────

    /**
     * Une agence possède plusieurs guichets (ex: Finexs -> Douala, Yaoundé, Bafoussam)
     */
    public function counters(): HasMany
    {
        return $this->hasMany(AgencyCounter::class);
    }
    // Uniquement les guichets actifs
    public function activeCounters(): HasMany
    {
        return $this->hasMany(AgencyCounter::class)
            ->where('is_active', true);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
