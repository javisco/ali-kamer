<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgencyCounter extends Model
{
    protected $fillable = [
        'agency_id',
        'city',
        'district',
        'landmark',
        'phone',
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
     * L'agence parente du guichet
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Les secrétaires affectés à ce guichet (relation N-N via secretary_counters)
     */
    public function secretaries(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'secretary_counters')
            ->withTimestamps();
    }
    // Colis déposés depuis ce guichet

    public function shipmentsAsOrigin(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'origin_counter_id');
    }
    /**
     * Colis enregistrés au départ de ce guichet
     */
    public function originShipments(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'origin_counter_id');
    }

    /**
     * Colis réceptionnés à l'arrivée dans ce guichet
     */
    public function destinationShipments(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'destination_counter_id');
    }


    // Colis reçus à ce guichet
    public function shipmentsAsDestination(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'destination_counter_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeByCity($q, string $city)
    {
        return $q->where('city', $city);
    }

    // Nom complet lisible : "Finexs Express — Douala Akwa"
    public function getFullNameAttribute(): string
    {
        return $this->agency->name . ' — ' . $this->city
            . ($this->district ? ' ' . $this->district : '');
    }
}
