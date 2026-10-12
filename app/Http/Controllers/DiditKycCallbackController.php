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
    public function __invoke(Request $request, KycService $kycService)
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
                $error = 'Nous n’avons pas encore pu récupérer le résultat. Votre dossier continue à être traité automatiquement.';
            }
        }

        if ($kyc) {
            if ($kyc->isApproved()) {
                return redirect()->route('seller.dashboard')
                    ->with('success', 'Votre identité a été validée avec succès. Votre boutique est active !');
            }

            if ($kyc->isRejected()) {
                return redirect()->route('seller.kyc.rejected')
                    ->with('fail', $kyc->rejection_reason ?: 'La vérification d’identité n’a pas abouti.');
            }

            return redirect()->route('seller.kyc.pending');
        }

        return view('seller.kyc.callback', compact('kyc', 'error', 'callbackStatus'));
    }
}
