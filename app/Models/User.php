<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'email_verified_at'
    ];
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kycDocument()
    {
        return $this->hasOne(KycDocument::class);
    }
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function assignedCounters(): BelongsToMany
    {
        return $this->belongsToMany(AgencyCounter::class, 'secretary_counters')
            ->withTimestamps();
    }
    public  function isBuyer()
    {
        return $this->role == 'buyer';
    }
}
