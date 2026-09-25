<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingScore extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'review_id',
        'category_id',
        'score',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RatingCategory::class, 'category_id');
    }
}
