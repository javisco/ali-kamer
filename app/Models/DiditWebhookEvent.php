<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Journal technique des webhooks Didit.
 *
 * Pourquoi cette table ?
 * - Didit peut renvoyer exactement le même event_id plusieurs fois.
 * - L'event_id devient donc notre clé d'idempotence.
 * - Elle permet également de diagnostiquer un webhook sans dépendre des logs.
 */
class DiditWebhookEvent extends Model
{
    protected $fillable = [
        'event_id',
        'webhook_type',
        'session_id',
        'status',
        'environment',
        'signature_method',
        'payload',
        'received_at',
        'processed_at',
        'processing_error',
        'attempts',
    ];

    protected function casts(): array
    {
        return [
            'payload'      => 'array',
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
            'attempts'     => 'integer',
        ];
    }
}
