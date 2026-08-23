<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // --- Relations ---

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // --- Scopes ---

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeParents($q)
    {
        return $q->whereNull('parent_id');
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    // --- Booting / Auto Slug ---

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // --- Accessors & Helpers ---

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }
}