<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    const STATUS_HIDDEN   = 'hidden';
    const STATUS_VISIBLE  = 'visible';
    const STATUS_SOLD_OUT = 'sold_out';
    const STATUS_BANNED   = 'banned';

    protected $fillable = [
        'shop_id',
        'category_id',
        'title',
        'description',
        'city',
        'price',
        'old_price',
        'stock',
        'stock_reserved',
        'min_quantity',
        'shipping_included',
        'shipping_threshold_qty',
        'specifications',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'specifications'    => 'array',
            'shipping_included' => 'boolean',
            'price'             => 'integer',
            'old_price'         => 'integer',
            'stock'             => 'integer',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->where('is_primary', true)
            ->limit(1);
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeVisible($q)
    {
        return $q->where('status', self::STATUS_VISIBLE);
    }

    public function scopeByCity($q, string $city)
    {
        return $q->where('city', $city);
    }

    public function scopeByCategory($q, int $categoryId)
    {
        return $q->where('category_id', $categoryId);
    }

    public function scopeSearch($q, string $term)
    {
        return $q->where(function ($query) use ($term) {
            $query->where('title', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function isVisible(): bool
    {
        return $this->status === self::STATUS_VISIBLE;
    }
    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }

    public function hasDiscount(): bool
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    public function discountPercent(): int
    {
        if (! $this->hasDiscount()) return 0;
        return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    public function availableStock(): int
    {
        return max(0, $this->stock - $this->stock_reserved);
    }
}
