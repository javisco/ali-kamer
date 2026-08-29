<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycRequest;
use App\Services\KycService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class KycSellerController extends Controller
{
    public function __construct(
        protected KycService $kycService,
        protected DashboardService $dashboardService
    ) {}

    // Formulaire d'upload du dossier KYC
    // Accessible uniquement si l'email est vérifié et le KYC pas encore approuvé
    public function create()
    {
        $user = Auth::user();
        $kyc  = $user->kycDocument;

        // Si déjà approuvé → rediriger vers le dashboard
        if ($kyc?->isApproved()) {
            return $this->dashboardService->dashboard($user);
        }

        return view('seller.kyc.create');
    }

    // Soumission du dossier KYC
    public function store(KycRequest $request)
    {
        $this->kycService->submitDossier(Auth::user(), $request->validated());

        return redirect()
            ->route('seller.kyc.pending')
            ->with('success', 'Dossier soumis avec succès ! Traitement sous 24 à 48h ouvrables.');
    }

    // Page d'attente après soumission
    public function pending()
    {
        $kyc = Auth::user()->kycDocument;

        // S'il n'y a pas de dossier → retour au formulaire
        if (! $kyc) {
            return redirect()->route('seller.kyc.create');
        }

        return view('seller.kyc.pending', compact('kyc'));
    }

    // Page de rejet — vendeur peut resoumettre
    public function rejected()
    {
        $user = Auth::user();
        $kyc  = $user->kycDocument;

        if (! $kyc?->isRejected()) {
            return $this->dashboardService->dashboard($user);
        }

        return view('seller.kyc.rejected', compact('user', 'kyc'));
    }
}