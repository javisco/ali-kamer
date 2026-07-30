<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycRequest;
use App\Services\KycService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class KycSellerController extends Controller
{
    public function __construct(protected KycService $kycService, protected DashboardService $dashboard_service) {}



    // Formulaire upload
    public function create()
    {
        $user = Auth::user();

        // Si déjà approuvé, pas besoin de revenir ici
        $this->dashboard_service->dashboard($user);

        return view('seller.kyc.create');
    }

    // Soumission
    public function store(KycRequest $request)
    {
        $this->kycService->submitDossier(Auth::user(), $request->validated());

        return redirect()->route('seller.kyc.pending')
            ->with('success', 'Dossier soumis ! Traitement sous 24 à 48h.');
    }

    // Page d'attente
    public function pending()
    {
        $kyc = Auth::user()->kycDocument;
        return view('seller.kyc.pending', compact('kyc'));
    }
    public function rejected()
    {
        $user = Auth::user();
        $kyc = $user->kycDocument;
        return view('seller.kyc.rejected', compact('user', "kyc"))->with('fail', "votre document a ete rejeter");
    }
}
