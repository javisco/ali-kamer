<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformWalletTransaction extends Model
{
    protected $fillable = [
        'admin_id',
        'type',
        'amount',
        'phone',
        'operator',
        'reference',
        'note'
    ];

    protected function casts(): array
    {
        return ['amount' => 'integer'];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
