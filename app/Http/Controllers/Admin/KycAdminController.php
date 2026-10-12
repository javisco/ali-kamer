<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountLink;
use App\Models\AdminLog;
use App\Models\BlacklistV2;
use App\Models\Dispute;
use App\Models\KycDocument;
use App\Models\Order;
use App\Services\DiditService;
use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class KycAdminController extends Controller
{
    public function __construct(
        protected KycService $kycService,
        protected DiditService $diditService
    ) {
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $filter = $request->get('filter');
        $search = $request->get('q');

        $query = KycDocument::with(['user.shop'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'reviewing', 'approved', 'rejected'])) {
            $query->where('status', $status);
        } elseif ($status === 'expired') {
            $query->whereIn('didit_session_status', ['Expired', 'Kyc Expired']);
        } elseif ($status === 'challenge') {
            $query->where('admin_decision', 'CHALLENGE');
        } elseif ($status === 'restricted') {
            $query->where('admin_decision', 'RESTRICT');
        } elseif ($status === 'blocked') {
            $query->where('admin_decision', 'BLOCK');
        }

        // Filtres de risque et anomalies (spécification section 4)
        if ($filter === 'low_face_match') {
            $query->where(function ($q) {
                $q->where('face_match_score', '<', 70)
                  ->orWhere(function ($sub) {
                      $sub->whereNotNull('face_match_status')->where('face_match_status', '!=', 'Approved');
                  });
            });
        } elseif ($filter === 'low_liveness') {
            $query->where(function ($q) {
                $q->where('liveness_score', '<', 70)
                  ->orWhere(function ($sub) {
                      $sub->whereNotNull('liveness_status')->where('liveness_status', '!=', 'Approved');
                  });
            });
        } elseif ($filter === 'doc_issue') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('document_status')->where('document_status', '!=', 'Approved');
                })->orWhereRaw('JSON_LENGTH(warnings) > 0');
            });
        } elseif ($filter === 'high_risk') {
            $query->whereHas('user', fn ($q) => $q->whereIn('risk_profile', ['HIGH', 'CRITICAL']));
        } elseif ($filter === 'low_trust') {
            $query->whereHas('user', fn ($q) => $q->where('trust_score', '<', 50));
        } elseif ($filter === 'identity_matched') {
            $query->whereIn('identity_resolution_status', ['matched', 'linked', 'CHALLENGE', 'RESTRICT', 'BLOCK']);
        } elseif ($filter === 'warnings') {
            $query->whereNotNull('warnings')->whereRaw('JSON_LENGTH(warnings) > 0');
        } elseif ($filter === 'anomalies') {
            $query->where(function ($q) {
                $q->where('ip_analysis_status', '!=', 'Approved')
                  ->orWhere('face_match_score', '<', 70)
                  ->orWhere('liveness_score', '<', 70);
            });
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('document_number_masked', 'like', "%{$search}%")
                  ->orWhere('didit_session_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $dossiers = $query->paginate(20)->withQueryString();

        $counts = [
            'all'       => KycDocument::count(),
            'pending'   => KycDocument::where('status', 'pending')->count(),
            'reviewing' => KycDocument::where('status', 'reviewing')->count(),
            'approved'  => KycDocument::where('status', 'approved')->count(),
            'rejected'  => KycDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.kyc.index', compact('dossiers', 'counts', 'status', 'filter', 'search'));
    }

    public function show(KycDocument $kyc): View
    {
        if ($kyc->isPending()) {
            $kyc->update(['status' => 'reviewing']);
        }

        // Journalisation de la consultation administrative (obligation de sécurité)
        AdminLog::record(
            Auth::user(),
            'kyc.viewed',
            'kyc_document',
            $kyc->id,
            "Consultation du dossier KYC complet de {$kyc->user->name}"
        );

        $kyc->load([
            'user.shop',
            'user.trustEvents' => fn ($q) => $q->latest()->limit(15),
            'user.activeSanctions',
            'user.sanctions' => fn ($q) => $q->latest()->limit(10),
            'user.decisionLogs' => fn ($q) => $q->latest()->limit(10),
            'decisionLogs' => fn ($q) => $q->latest()->limit(10),
            'adminReviews' => fn ($q) => $q->latest(),
        ]);

        $user = $kyc->user;
        $shop = $user->shop;

        $stats = [
            'orders_count'           => $shop ? Order::where('shop_id', $shop->id)->count() : 0,
            'completed_orders_count' => $shop ? Order::where('shop_id', $shop->id)->whereIn('status', ['completed', 'auto_completed'])->count() : 0,
            'cancelled_orders_count' => $shop ? Order::where('shop_id', $shop->id)->where('status', 'cancelled')->count() : 0,
            'disputes_count'         => $shop ? Dispute::whereHas('order', fn ($q) => $q->where('shop_id', $shop->id))->count() : 0,
            'sanctions_count'        => $user->sanctions()->count(),
            'reviews_count'          => $shop ? $shop->reviews()->count() : 0,
            'avg_rating'             => $shop ? round((float) ($shop->reviews()->avg('rating') ?? 0), 2) : 0,
        ];

        $linkedAccounts = AccountLink::where('user_a_id', $user->id)
            ->orWhere('user_b_id', $user->id)
            ->with(['userA', 'userB'])
            ->get();

        $blacklist = BlacklistV2::where('user_id', $user->id)->first();

        return view('admin.kyc.show', compact('kyc', 'user', 'shop', 'stats', 'linkedAccounts', 'blacklist'));
    }

    public function approve(Request $request, KycDocument $kyc)
    {
        $notes = $request->input('notes');
        $this->kycService->approve($kyc, Auth::user(), $notes);

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', "Le dossier de {$kyc->user->name} a été validé avec succès. Boutique et compte activés.");
    }

    public function reject(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|string|min:5|max:2000']);

        $this->kycService->reject($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', 'Dossier rejeté. Le vendeur a été notifié.');
    }

    public function review(Request $request, KycDocument $kyc)
    {
        $this->kycService->review($kyc, Auth::user(), $request->input('reason'));

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', 'Le dossier a été placé en revue.');
    }

    public function challenge(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|string|min:5|max:2000']);

        $this->kycService->challenge($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', 'Dossier challengé. Pré-paiement forcé appliqué.');
    }

    public function restrict(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|string|min:5|max:2000']);

        $this->kycService->restrict($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', 'Restrictions appliquées sur le compte du vendeur.');
    }

    public function block(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|string|min:5|max:2000']);

        $this->kycService->block($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.show', $kyc)
            ->with('success', 'Vendeur bloqué et inscrit sur la liste noire Ali-Kamer.');
    }

    /**
     * Accès sécurisé aux médias Didit pour l'administration (proxy / redirect frais).
     */
    public function media(KycDocument $kyc, string $type)
    {
        abort_unless(in_array($type, ['front', 'back', 'selfie', 'video']), 404);

        $mediaKey = match ($type) {
            'front'  => 'cni_front',
            'back'   => 'cni_back',
            'selfie' => 'selfie',
            'video'  => 'video',
        };

        $url = $kyc->didit_media[$mediaKey] ?? null;

        if (! $url) {
            $url = match ($type) {
                'front'  => $kyc->cni_front_url,
                'back'   => $kyc->cni_back_url,
                'selfie' => $kyc->selfie_url,
                default  => null,
            };
        }

        // Si l'URL est introuvable ou expirée et qu'une session Didit existe, on rafraîchit
        if (! $url && $kyc->didit_session_id) {
            try {
                $decision = $this->diditService->getSessionDecision($kyc->didit_session_id);
                $this->kycService->processDiditCallback($kyc->didit_session_id);
                $kyc->refresh();
                $url = $kyc->didit_media[$mediaKey] ?? null;
            } catch (Throwable $e) {
                report($e);
            }
        }

        if (! $url) {
            abort(404, 'Média Didit non disponible ou expiré.');
        }

        // Redirection vers l'URL signée sans exposer la clé API Didit
        return redirect()->away($url);
    }
}
