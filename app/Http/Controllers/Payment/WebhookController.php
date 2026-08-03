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

    public function campay(Request $request)
    {
        Log::info('Campay webhook reçu', $request->all());

        // Campay envoie "signature" dans le body JSON (pas dans le header)
        $signature = $request->input('signature', '');

        //Vérification de la signature JWT
        if (! $this->campayService->verifyWebhookSignature($signature)) {
            Log::warning('Campay webhook : signature invalide');
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        try {
            $this->paymentService->handleWebhook($request->all());
        } catch (\Exception $e) {
            Log::error('Campay webhook erreur', [
                'error' => $e->getMessage(),
                'data'  => $request->all(),
            ]);
            return response()->json(['error' => 'Erreur traitement'], 500);
        }

        // Campay attend un 200 pour confirmer la réception
        return response()->json(['status' => 'ok']);
    }
}
