<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisputeMessage extends Model
{
    use HasFactory;

    const TYPE_MESSAGE    = 'MESSAGE';
    const TYPE_SYSTEM     = 'SYSTEM';
    const TYPE_ADMIN      = 'ADMIN';
    const TYPE_RESOLUTION = 'RESOLUTION';

    protected $fillable = [
        'dispute_id',
        'sender_id',
        'message',
        'type',
    ];

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
