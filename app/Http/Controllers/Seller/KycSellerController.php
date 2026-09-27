<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Interface vendeur du KYC Hosted Session.
 *
 * Aucun fichier CNI/selfie n'est uploadé vers Laravel : Didit héberge la
 * capture et exécute le workflow configuré dans son interface.
 */
class KycSellerController extends Controller
{
    public function __construct(
        protected KycService $kycService,
        protected DashboardService $dashboardService,
    ) {
    }

    public function create()
    {
        $user = Auth::user();
        $kyc = $user->kycDocument;

        if ($kyc?->isApproved()) {
            return $this->dashboardService->dashboard($user);
        }

        return view('seller.kyc.create', compact('user', 'kyc'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'consent' => ['accepted'],
        ], [
            'consent.accepted' => 'Votre consentement est requis pour démarrer la vérification.',
        ]);

        try {
            $result = $this->kycService->startSession(Auth::user());

            // Redirection vers l'URL générée par Didit.
            return redirect()->away($result['url']);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('fail', 'Impossible de démarrer la vérification pour le moment. Vérifiez votre configuration Didit puis réessayez.');
        }
    }

    public function pending()
    {
        $kyc = Auth::user()->kycDocument;

        if (! $kyc) {
            return redirect()->route('seller.kyc.create');
        }

        if ($kyc->isApproved()) {
            return $this->dashboardService->dashboard(Auth::user());
        }

        return view('seller.kyc.pending', compact('kyc'));
    }

    public function rejected()
    {
        $user = Auth::user();
        $kyc = $user->kycDocument;

        if (! $kyc?->isRejected()) {
            return $this->dashboardService->dashboard($user);
        }

        return view('seller.kyc.rejected', compact('user', 'kyc'));
    }
}
