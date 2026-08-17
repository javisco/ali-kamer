<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisputeEvidence extends Model
{
    protected $table = 'dispute_evidences';


    protected $fillable = [
        'dispute_id',
        'submitted_by',
        'type',
        'url',
        'content',
        'description',
    ];

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }
    public function isText(): bool
    {
        return $this->type === 'text';
    }
    public function isDocument(): bool
    {
        return $this->type === 'document';
    }
}
