<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'kyc_document_id',
        'decision',
        'risk_score',
        'trust_score',
        'reason_code',
        'signals',
        'source',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'signals' => 'array',
            'created_at' => 'datetime',
            'risk_score' => 'float',
            'trust_score' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kycDocument(): BelongsTo
    {
        return $this->belongsTo(KycDocument::class);
    }
}
