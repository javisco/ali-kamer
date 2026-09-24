<?php

namespace App\Jobs;

use App\Models\DiditWebhookEvent;
use App\Services\KycService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Traitement asynchrone du webhook.
 *
 * Le contrôleur HTTP doit répondre rapidement à Didit. Le traitement métier
 * (appel API Didit, TrustEngine, activation boutique...) se fait ici.
 */
class ProcessDiditWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $eventId)
    {
    }

    public function handle(KycService $kycService): void
    {
        $event = DiditWebhookEvent::find($this->eventId);

        if (! $event) {
            return;
        }

        // Idempotence supplémentaire côté job : si le job est relancé après
        // une erreur réseau, on ne traite pas deux fois un événement terminé.
        if ($event->processed_at) {
            return;
        }

        $event->increment('attempts');

        try {
            $kycService->processDiditWebhookEvent(
                payload: $event->payload ?? [],
                signatureMethod: $event->signature_method ?? 'v2'
            );

            $event->forceFill([
                'processed_at' => now(),
                'processing_error' => null,
            ])->save();
        } catch (Throwable $e) {
            $event->forceFill([
                'processing_error' => mb_substr($e->getMessage(), 0, 2000),
            ])->save();

            Log::error('Erreur traitement webhook Didit.', [
                'event_id' => $event->event_id,
                'attempts' => $event->attempts,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
