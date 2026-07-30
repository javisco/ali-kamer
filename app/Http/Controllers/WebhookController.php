<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\CampayService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private CampayService $campayService
    ) {}

    // Point d'entrée du webhook Campay
    // Campay appelle cette URL après chaque paiement (réussi ou échoué)
    public function campay(Request $request)
    {
        // 1. Vérifier la signature HMAC pour s'assurer que c'est bien Campay
        $signature = $request->header('Signature');
        $payload   = $request->getContent();

        if (! $this->campayService->verifyWebhookSignature($payload, $signature ?? '')) {
            Log::warning('Webhook Campay : signature invalide', [
                'ip'        => $request->ip(),
                'signature' => $signature,
            ]);

            // Retourner 401 — Campay réessaiera automatiquement
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        // 2. Traiter le webhook
        try {
            $this->paymentService->handleWebhook($request->all());
        } catch (\Exception $e) {
            Log::error('Webhook Campay erreur traitement', [
                'error' => $e->getMessage(),
                'data'  => $request->all(),
            ]);

            // Retourner 500 — Campay réessaiera
            return response()->json(['error' => 'Erreur traitement'], 500);
        }

        // 3. Confirmer la réception à Campay
        return response()->json(['status' => 'ok']);
    }
}