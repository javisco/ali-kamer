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
        'phone_momo',
        'momo_operator',
        'contact_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // Tous les comptoirs de cette agence
    public function counters(): HasMany
    {
        return $this->hasMany(AgencyCounter::class);
    }

    // Comptoirs actifs uniquement
    public function activeCounters(): HasMany
    {
        return $this->hasMany(AgencyCounter::class)
            ->where('is_active', true);
    }

    // Villes desservies par cette agence
    public function cities(): HasMany
    {
        return $this->hasMany(AgencyCity::class);
    }

    // Villes actives uniquement
    public function activeCities(): HasMany
    {
        return $this->hasMany(AgencyCity::class)
            ->where('is_active', true);
    }

    // Comptoirs dans une ville précise
    public function countersInCity(string $city)
    {
        return $this->counters()
            ->where('city', $city)
            ->where('is_active', true)
            ->get();
    }

    // Vérifie si l'agence dessert une ville donnée
    public function servesCity(string $city): bool
    {
        return $this->cities()
            ->where('city', $city)
            ->where('is_active', true)
            ->exists();
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function orderShipments()
    {
        return $this->hasMany(OrderShipment::class);
    }
}
