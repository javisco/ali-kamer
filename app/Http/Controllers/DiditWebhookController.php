<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDiditWebhookJob;
use App\Models\DiditWebhookEvent;
use App\Services\DiditWebhookSignatureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Endpoint public appelé uniquement par Didit.
 *
 * Il ne doit PAS avoir auth/verified : Didit n'est pas connecté à Ali-Kamer.
 * La sécurité repose sur la signature HMAC + timestamp + idempotence.
 */
class DiditWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        DiditWebhookSignatureService $signatureService
    ): JsonResponse {
        try {
            $verified = $signatureService->verify($request);
            $payload = $verified['body'];

            $eventId = $payload['event_id'] ?? null;
            $webhookType = $payload['webhook_type'] ?? null;

            if (! $eventId || ! Str::isUuid((string) $eventId)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'event_id invalide.',
                ], 422);
            }

            if (! is_string($webhookType) || $webhookType === '') {
                return response()->json([
                    'ok' => false,
                    'message' => 'webhook_type manquant.',
                ], 422);
            }

            // Idempotence : Didit réutilise le même event_id lors des retries.
            // firstOrCreate évite donc de programmer plusieurs fois le même job.
            $event = DiditWebhookEvent::firstOrCreate(
                ['event_id' => $eventId],
                [
                    'webhook_type' => $webhookType,
                    'session_id' => $payload['session_id'] ?? null,
                    'status' => $payload['status'] ?? null,
                    'environment' => $payload['environment'] ?? null,
                    'signature_method' => $verified['method'],
                    'payload' => $payload,
                    'received_at' => now(),
                    'attempts' => 0,
                ]
            );

            // Si l'event existe déjà, Didit est en train de rejouer le même
            // événement : répondre 200 immédiatement est la bonne stratégie.
            if (! $event->wasRecentlyCreated) {
                return response()->json([
                    'ok' => true,
                    'duplicate' => true,
                    'event_id' => $eventId,
                ]);
            }

            // Seuls les événements KYC de session nous intéressent pour ce module.
            // Le job ignore proprement les autres types si jamais la destination
            // reçoit plus tard d'autres événements.
            ProcessDiditWebhookJob::dispatch($event->id);

            return response()->json([
                'ok' => true,
                'queued' => true,
                'event_id' => $eventId,
            ], 202);
        } catch (Throwable $e) {
            // 5xx = Didit peut réessayer. C'est volontaire pour les erreurs
            // techniques (secret absent, signature invalide, etc.).
            Log::warning('Webhook Didit refusé.', [
                'ip' => $request->ip(),
                'reason' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Webhook non accepté.',
            ], 401);
        }
    }
}
