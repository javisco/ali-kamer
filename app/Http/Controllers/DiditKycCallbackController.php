<?php

namespace App\Http\Controllers;

use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

/**
 * Callback navigateur après le flow Didit.
 *
 * IMPORTANT : le paramètre `status` reçu dans l'URL n'est pas utilisé comme
 * preuve. On relit la décision depuis l'API Didit côté serveur.
 */
class DiditKycCallbackController extends Controller
{
    public function __invoke(Request $request, KycService $kycService): View
    {
        $sessionId = (string) $request->query('verificationSessionId', '');
        $callbackStatus = $request->query('status');

        $kyc = null;
        $error = null;

        if ($sessionId !== '') {
            try {
                $kyc = $kycService->processDiditCallback($sessionId, $callbackStatus);
            } catch (Throwable $e) {
                report($e);
                $error = 'Nous n’avons pas encore pu récupérer le résultat. Votre dossier peut continuer à être traité automatiquement.';
            }
        }

        return view('seller.kyc.callback', compact('kyc', 'error', 'callbackStatus'));
    }
}
