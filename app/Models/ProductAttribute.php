<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = ['product_id', 'name', 'sort_order'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Toutes les valeurs possibles pour cet attribut
    // Ex: pour "Processeur" → [Core i3, Core i5, Core i7]
    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class)
                    ->orderBy('sort_order');
    }
}