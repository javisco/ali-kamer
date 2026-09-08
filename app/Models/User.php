<?php

namespace App\Models;

use App\Notifications\Concerns\HasDatabasePayload;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    const ROLE_BUYER     = 'buyer';
    const ROLE_SELLER    = 'seller';
    const ROLE_SECRETARY = 'secretary';
    const ROLE_ADMIN     = 'admin';
    const ROLE_AGENCY_MANAGER = 'agency_manager';

    // ── Statuts ───────────────────────────────────────────────────────
    const STATUS_CANDIDATE = 'candidate';
    const STATUS_ACTIVE    = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_BANNED    = 'banned';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'agency_id',
        'phone_momo',
        'momo_operator',
        'wallet_pending',
        'wallet_available',
        'trust_score',
        'dispute_count',
        'abuse_count',
        'prepayment_required',
        'purchase_restricted',
        'referral_code',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'            => 'hashed',
            'email_verified_at'   => 'datetime',
            'phone_verified_at'   => 'datetime',
            'prepayment_required' => 'boolean',
            'purchase_restricted' => 'boolean',
            'wallet_pending'      => 'integer',
            'wallet_available'    => 'integer',
            'trust_score'         => 'integer',
        ];
    }

    // ────────────────────────────────────────────────────────────────
    // RELATIONS
    // ────────────────────────────────────────────────────────────────

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }

    public function kycDocument(): HasOne
    {
        return $this->hasOne(KycDocument::class);
    }

    public function ordersAsBuyer(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function agencyCounters()
    {
        return $this->belongsToMany(AgencyCounter::class, 'secretary_counters')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    // ────────────────────────────────────────────────────────────────
    // SCOPES
    // ────────────────────────────────────────────────────────────────

    public function scopeBuyers($q)
    {
        return $q->where('role', self::ROLE_BUYER);
    }
    public function scopeSellers($q)
    {
        return $q->where('role', self::ROLE_SELLER);
    }
    public function scopeSecretaries($q)
    {
        return $q->where('role', self::ROLE_SECRETARY);
    }
    public function scopeAdmins($q)
    {
        return $q->where('role', self::ROLE_ADMIN);
    }
    public function scopeActive($q)
    {
        return $q->where('status', self::STATUS_ACTIVE);
    }
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }


    // ────────────────────────────────────────────────────────────────
    // HELPERS
    // ────────────────────────────────────────────────────────────────

    public function isBuyer(): bool
    {
        return $this->role === self::ROLE_BUYER;
    }
    public function isSeller(): bool
    {
        return $this->role === self::ROLE_SELLER;
    }
    public function isSecretary(): bool
    {
        return $this->role === self::ROLE_SECRETARY;
    }
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }
    public function isCandidate(): bool
    {
        return $this->status === self::STATUS_CANDIDATE;
    }

    public function hasActiveShop(): bool
    {
        return $this->isSeller()
            && $this->isActive()
            && $this->shop?->status === 'active';
    }


    // Ajouter dans isSeller(), etc.
    public function isAgencyManager(): bool
    {
        return $this->role === self::ROLE_AGENCY_MANAGER;
    }

    // Relation agence (pour les agency_managers)
    public function managedAgency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    // Relation agence directe (pour les secrétaires et managers)
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }


    // Redirect après login selon le rôle — utilisé dans AuthService
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN         => 'admin.dashboard',
            self::ROLE_SELLER        => 'seller.dashboard',
            self::ROLE_SECRETARY     => 'secretary.dashboard',
            self::ROLE_AGENCY_MANAGER => 'agency.dashboard',
            default                  => 'buyer.home',
        };
    }
    // Comptoirs assignés au secrétaire
    public function assignedCounters()
    {
        return $this->belongsToMany(
            AgencyCounter::class,
            'secretary_counters',
            'user_id',
            'agency_counter_id'
        )->withPivot('is_primary')->withTimestamps();
    }
    public function routeNotificationForVonage($notification): string
    {
        return '237' . ltrim($this->phone_momo ?? $this->phone, '0');
    }
    public function routeNotificationForWhatsApp($notification): string
    {
        return '237' . ltrim($this->phone_momo ?? $this->phone, '0');
    }
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }
}
