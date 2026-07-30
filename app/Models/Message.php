<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    // RÈGLE : pas de updated_at — un message est immuable
    // Aucune modification ni suppression possible après envoi
    const UPDATED_AT = null;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'body',
        'attachment_url',
        'attachment_size',
        'is_read',
        'read_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read'  => 'boolean',
            'read_at'  => 'datetime',
            'sent_at'  => 'datetime',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isText(): bool
    {
        return $this->type === 'text';
    }
    public function isImage(): bool
    {
        return $this->type === 'image';
    }
    public function isPdf(): bool
    {
        return $this->type === 'pdf';
    }

    // Taille du fichier formatée pour l'affichage
    public function fileSizeFormatted(): string
    {
        if (! $this->attachment_size) return '';

        $kb = $this->attachment_size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';

        return round($kb / 1024, 1) . ' MB';
    }
}
