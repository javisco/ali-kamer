<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'price', 'old_price',
        'stock', 'stock_reserved', 'sku', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price'     => 'integer',
            'old_price' => 'integer',
            'stock'     => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Les valeurs d'attributs de cette variante
    // Ex: [Core i5, 8GB]
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variant_attribute_values'
        )->with('attribute');
    }

    // Stock disponible (total - réservé)
    public function availableStock(): int
    {
        return max(0, $this->stock - $this->stock_reserved);
    }

    // Label lisible de la variante
    // Ex: "Core i5 / 8GB"
    public function label(): string
    {
        return $this->attributeValues
            ->sortBy('attribute.sort_order')
            ->pluck('value')
            ->join(' / ');
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
}