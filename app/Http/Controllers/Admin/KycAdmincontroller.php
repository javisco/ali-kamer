<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use App\Services\ElgiopayService;
use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;

class KycAdminController extends Controller
{
    public function __construct(protected KycService $kycService) {}

    // Liste des dossiers — INCHANGÉ.
    public function index(Request $request)
    {
        $status   = $request->get('status', 'pending');
        $dossiers = KycDocument::with('user')
            ->where('status', $status)
            ->latest()->paginate(20);

        $counts = [
            'pending'  => KycDocument::where('status', 'pending')->count(),
            'approved' => KycDocument::where('status', 'approved')->count(),
            'rejected' => KycDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.kyc.index', compact('dossiers', 'counts', 'status'));
    }

    // Détail d'un dossier
    public function show(KycDocument $kyc): View
    {
        if ($kyc->isPending()) {
            $kyc->update(['status' => 'reviewing']);
        }

        $urls = [
            'cni_front_url' => $this->kycService->getTemporaryUrl($kyc->cni_front_url),
            'cni_back_url'  => $this->kycService->getTemporaryUrl($kyc->cni_back_url),
            'selfie_url'    => $this->kycService->getTemporaryUrl($kyc->selfie_url),
            'rccm_url'      => $kyc->rccm_url
                ? $this->kycService->getTemporaryUrl($kyc->rccm_url)
                : null,
        ];

        // Vérification MoMo en temps réel via Elgiopay (remplace Campay).
        // Le format de retour change : ['valid' => bool, 'recipient_name' => ?string]
        // au lieu du holder info brut Campay — adapte la vue admin.kyc.show
        // si elle lit un champ spécifique de l'ancien format.
        $holderInfo = null;
        try {
            $holderInfo = app(ElgiopayService::class)->getHolderInfo($kyc->user->phone ?? $kyc->user->phone_momo);
        } catch (\Exception $e) {
            // Ne pas bloquer si Elgiopay est indisponible
            Log::warning('HolderInfo unavailable', ['error' => $e->getMessage()]);
        }

        return view('admin.kyc.show', compact('kyc', 'urls', 'holderInfo'));
    }

    // Approuver — INCHANGÉ.
    public function approve(KycDocument $kyc)
    {
        $this->kycService->approve($kyc, Auth::user());

        return redirect()->route('admin.kyc.index')
            ->with('success', "{$kyc->user->name} approuvé. Boutique activée.");
    }

    // Rejeter — INCHANGÉ.
    public function reject(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|min:10']);

        $this->kycService->reject($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.index')
            ->with('success', "Dossier rejeté.");
    }

    // Servir un fichier privé à l'admin — INCHANGÉ.
    public function serveFile(Request $request)
    {
        $path = decrypt($request->path);
        abort_unless(Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->response($path);
    }
}
