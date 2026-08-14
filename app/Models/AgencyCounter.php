<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return ['is_active' => 'boolean'];
    }

    // L'agence parente
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    // Le secrétaire affecté à ce comptoir (un seul)
    public function secretary()
    {
        return $this->hasOne(SecretaryCounter::class)
            ->with('secretary');
    }

    // Colis déposés depuis ce comptoir
    public function shipmentsAsOrigin(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'origin_counter_id');
    }

    // Colis reçus à ce comptoir
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

    // Nom complet lisible
    // Ex: "General Express — Yaoundé Carrière"
    public function getFullNameAttribute(): string
    {
        return $this->agency->name . ' — '
            . $this->city
            . ($this->district ? ' ' . $this->district : '');
    }
}
