<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ElgiopayWebhookEvent;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function elgiopay(Request $request)
    {
        $rawBody = $request->getContent();
        $header  = $request->header('X-Elgiopay-Signature', '');

        // Vérification cryptographique RÉELLE (HMAC-SHA256 + anti-rejeu 5 min).
        // La vérification Campay ("verifyWebhookSignature") ne faisait que
        // compter les segments séparés par des points — ce n'était pas une
        // vérification cryptographique. Ici c'en est une vraie.
        if (! $this->signatureIsValid($rawBody, $header)) {
            Log::warning('Elgiopay webhook : signature invalide ou expirée');
            return response()->json(['error' => 'Signature invalide'], 400);
        }

        $eventId   = $request->header('X-Elgiopay-Event-Id');
        $eventType = $request->header('X-Elgiopay-Event');
        $payload   = json_decode($rawBody, true) ?? [];

        if (! $eventId) {
            return response()->json(['error' => 'event id manquant'], 400);
        }

        // Déduplication obligatoire — le même event_id peut être livré
        // plusieurs fois (retries + renvoi manuel dashboard Elgiopay).
        // Persisté en BDD (table elgiopay_webhook_events), pas juste en cache.
        if (ElgiopayWebhookEvent::where('event_id', $eventId)->exists()) {
            return response()->json(['status' => 'already processed']);
        }

        try {
            $this->paymentService->handleWebhook($eventType, $payload['data'] ?? []);
        } catch (\Exception $e) {
            Log::error('Elgiopay webhook erreur', [
                'error' => $e->getMessage(),
                'data'  => $payload,
            ]);
            return response()->json(['error' => 'Erreur traitement'], 500);
        }

        ElgiopayWebhookEvent::create([
            'event_id'     => $eventId,
            'event_type'   => $eventType,
            'processed_at' => now(),
        ]);

        // Elgiopay attend un 2xx pour confirmer la réception, sous 30 secondes.
        return response()->json(['status' => 'ok']);
    }

    private function signatureIsValid(string $rawBody, string $header): bool
    {
        if (! $header) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $header) as $kv) {
            if (str_contains($kv, '=')) {
                [$k, $v] = explode('=', $kv, 2);
                $parts[$k] = $v;
            }
        }

        if (empty($parts['t']) || empty($parts['v1'])) {
            return false;
        }

        // Anti-rejeu : refuser tout ce qui dépasse 5 minutes
        if (abs(time() - (int) $parts['t']) > 300) {
            return false;
        }

        $expected = hash_hmac(
            'sha256',
            $parts['t'] . '.' . $rawBody,
            config('services.elgiopay.webhook_secret')
        );

        return hash_equals($expected, $parts['v1']);
    }
}
