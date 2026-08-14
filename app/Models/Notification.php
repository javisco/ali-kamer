<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'channel', 'type', 'title',
        'body', 'data', 'status', 'retry_count',
        'error_message', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'data'     => 'array',
            'sent_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isFailed(): bool  { return $this->status === 'failed'; }
    public function isSent(): bool    { return $this->status === 'sent'; }
    public function isPending(): bool { return $this->status === 'pending'; }
}