<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VelocityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'ip_address',
        'action',
        'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }
}
