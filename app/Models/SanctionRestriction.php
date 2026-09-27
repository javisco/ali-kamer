<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanctionRestriction extends Model
{
    use HasFactory;

    // Codes de restriction officiels
    const CANNOT_BUY          = 'cannot_buy';
    const CANNOT_SELL         = 'cannot_sell';
    const CANNOT_PUBLISH      = 'cannot_publish';
    const CANNOT_WITHDRAW     = 'cannot_withdraw';
    const CANNOT_CREATE_ORDER = 'cannot_create_order';
    const CANNOT_MESSAGE      = 'cannot_message';

    protected $fillable = [
        'sanction_id',
        'restriction_code',
    ];

    public function sanction(): BelongsTo
    {
        return $this->belongsTo(Sanction::class);
    }
}
