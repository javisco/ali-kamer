<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanctionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason_code',
        'type',
        'severity',
        'duration_minutes',
        'automatic',
        'conditions',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'automatic' => 'boolean',
            'enabled' => 'boolean',
            'conditions' => 'array',
            'duration_minutes' => 'integer',
        ];
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
