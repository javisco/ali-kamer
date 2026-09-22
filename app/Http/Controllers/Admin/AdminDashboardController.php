<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Dispute;
use App\Models\KycDocument;
use App\Models\Order;
use App\Models\User;
use App\Services\KycService;
use App\Services\TreasuryService;
use App\Services\WalletService;
use Illuminate\Http\Request;


class AdminDashboardController extends Controller
{
    public function __construct(private TreasuryService $treasury) {}

    public function index()
    {
        // ── KPIs du jour ──────────────────────────────────────────────
        $today = now()->startOfDay();

        $kpis = [
            // Commandes du jour
            'orders_today'    => Order::whereDate('created_at', today())->count(),

            // CA du jour (total payé)
            'revenue_today'   => Order::whereDate('paid_at', today())
                ->where('status', '!=', 'failed')
                ->sum('total_amount'),

            // Litiges ouverts en attente
            'disputes_open'   => Dispute::whereIn('status', ['open', 'seller_replied'])->count(),

            // Dossiers KYC en attente
            'kyc_pending'     => KycDocument::where('status', 'pending')->count(),

            // Nouveaux vendeurs aujourd'hui
            'sellers_today'   => User::where('role', 'seller')
                ->whereDate('created_at', today())->count(),

            // Total séquestré
            'total_escrow'    => User::sum('wallet_pending'),

            // Total disponible tous vendeurs
            'total_available' => User::sum('wallet_available'),
        ];

        // ── File de modération ────────────────────────────────────────
        $pendingKyc = KycDocument::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $openDisputes = Dispute::with(['order.buyer', 'order.shop'])
            ->whereIn('status', ['open', 'seller_replied'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Commandes récentes ────────────────────────────────────────
        $recentOrders = Order::with(['buyer', 'shop'])
            ->latest()
            ->limit(10)
            ->get();

        // Solde Elgiopay et instantané trésorerie
        $treasurySnapshot = null;
        try {
            $treasurySnapshot = $this->treasury->getSnapshot();
        } catch (\Throwable $e) {
            \Log::warning('Treasury snapshot unavailable', ['error' => $e->getMessage()]);
        }

        $elgiopayBalance = $treasurySnapshot ? ['balance' => $treasurySnapshot['live_balance']] : null;

        return view('admin.dashboard', compact(
            'kpis',
            'pendingKyc',
            'openDisputes',
            'recentOrders',
            'elgiopayBalance',
            'treasurySnapshot'
        ));
    }

    // Gestion des utilisateurs
    // Gestion des utilisateurs
    public function users(Request $request)
    {
        $query = User::with('shop');

        /*
    |--------------------------------------------------------------------------
    | RECHERCHE GÉNÉRALE
    |--------------------------------------------------------------------------
    | Recherche simultanément dans :
    | - nom
    | - email
    | - téléphone
    | - téléphone Mobile Money
    */
        if ($request->filled('q')) {
            $search = trim($request->input('q'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('phone_momo', 'like', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE PAR RÔLE
    |--------------------------------------------------------------------------
    */
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE PAR STATUT
    |--------------------------------------------------------------------------
    */
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE PAR VILLE
    |--------------------------------------------------------------------------
    */
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE OPÉRATEUR MOBILE MONEY
    |--------------------------------------------------------------------------
    */
        if ($request->filled('momo_operator')) {
            $query->where(
                'momo_operator',
                $request->input('momo_operator')
            );
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE EMAIL
    |--------------------------------------------------------------------------
    */
        if ($request->filled('email_status')) {

            if ($request->input('email_status') === 'verified') {
                $query->whereNotNull('email_verified_at');
            }

            if ($request->input('email_status') === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE TRUST SCORE
    |--------------------------------------------------------------------------
    */
        if ($request->filled('trust_level')) {

            match ($request->input('trust_level')) {

                'critical' => $query->where('trust_score', '<', 20),

                'low' => $query->whereBetween('trust_score', [20, 39]),

                'medium' => $query->whereBetween('trust_score', [40, 69]),

                'good' => $query->where('trust_score', '>=', 70),

                default => null,
            };
        }

        /*
    |--------------------------------------------------------------------------
    | TRUST SCORE PERSONNALISÉ
    |--------------------------------------------------------------------------
    */
        if ($request->filled('trust_min')) {
            $query->where(
                'trust_score',
                '>=',
                (int) $request->input('trust_min')
            );
        }

        if ($request->filled('trust_max')) {
            $query->where(
                'trust_score',
                '<=',
                (int) $request->input('trust_max')
            );
        }

        /*
    |--------------------------------------------------------------------------
    | UTILISATEURS AVEC / SANS BOUTIQUE
    |--------------------------------------------------------------------------
    */
        if ($request->filled('shop')) {

            if ($request->input('shop') === 'yes') {
                $query->has('shop');
            }

            if ($request->input('shop') === 'no') {
                $query->doesntHave('shop');
            }
        }

        /*
    |--------------------------------------------------------------------------
    | FILTRE PAR DATE D'INSCRIPTION
    |--------------------------------------------------------------------------
    */
        if ($request->filled('registered_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->input('registered_from')
            );
        }

        if ($request->filled('registered_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->input('registered_to')
            );
        }

        /*
    |--------------------------------------------------------------------------
    | TRI
    |--------------------------------------------------------------------------
    */
        match ($request->input('sort', 'recent')) {

            'oldest' => $query->oldest('created_at'),

            'name_asc' => $query->orderBy('name', 'asc'),

            'name_desc' => $query->orderBy('name', 'desc'),

            'trust_high' => $query->orderByDesc('trust_score'),

            'trust_low' => $query->orderBy('trust_score'),

            default => $query->latest('created_at'),
        };

        /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
        $users = $query
            ->paginate(30)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | DONNÉES POUR LES FILTRES
    |--------------------------------------------------------------------------
    */
        $roles = [
            'buyer' => 'Acheteur',
            'seller' => 'Vendeur',
            'secretary' => 'Secrétaire',
            'admin' => 'Administrateur',
            'agency_manager' => 'Gestionnaire agence',
        ];

        $statuses = [
            'candidate' => 'Candidat',
            'active' => 'Actif',
            'suspended' => 'Suspendu',
            'banned' => 'Banni',
        ];

        $cities = [
            'Douala',
            'Yaoundé',
            'Bafoussam',
            'Bamenda',
            'Buea',
            'Limbé',
            'Garoua',
            'Maroua',
            'Ngaoundéré',
            'Bertoua',
            'Ebolowa',
            'Kribi',
        ];

        return view('admin.users.index', compact(
            'users',
            'roles',
            'statuses',
            'cities'
        ));
    }
    // Bannir un utilisateur
    public function banUser(User $user)
    {
        $user->update(['status' => 'banned']);

        AdminLog::record(
            auth()->user(),
            'user.banned',
            'user',
            $user->id
        );

        return back()->with('success', "{$user->name} a été banni.");
    }

    // Réactiver un utilisateur
    public function unbanUser(User $user)
    {
        $user->update(['status' => 'active']);

        AdminLog::record(
            auth()->user(),
            'user.unbanned',
            'user',
            $user->id
        );

        return back()->with('success', "{$user->name} a été réactivé.");
    }


    public function blacklistUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['required', 'string', 'min:10'],
        ]);

        app(KycService::class)->blacklist($user, auth()->user(), $request->reason);

        return back()->with('success', "{$user->name} a été blacklisté définitivement.");
    }


    public function userHistory(User $user)
    {
        $transactions = app(WalletService::class)->history($user, 50);

        return view('admin.users.history', compact('user', 'transactions'));
    }


    // Liste des agences avec leurs gains
    public function agencies()
    {
        $agencies = \App\Models\Agency::with('cities')
            ->withCount('counters')
            ->latest()
            ->paginate(20);

        // Stats globales agences
        $totalAgencyPending   = \App\Models\Agency::sum('wallet_pending');
        $totalAgencyAvailable = \App\Models\Agency::sum('wallet_available');

        return view('admin.agencies.earnings', compact(
            'agencies',
            'totalAgencyPending',
            'totalAgencyAvailable'
        ));
    }

    // Historique transactions d'une agence
    public function agencyHistory(\App\Models\Agency $agency)
    {
        $agencyService = app(\App\Services\AgencyManagerService::class);

        $history       = $agencyService->getHistory($agency);
        $stats         = $agencyService->getStats($agency);
        $deliveryStats = $agencyService->getDeliveryStats($agency);

        return view('admin.agencies.history', compact(
            'agency',
            'history',
            'stats',
            'deliveryStats'
        ));
    }


    // Utilisateurs avec notes basses à surveiller
    public function lowScoreUsers(Request $request)
    {
        $threshold = $request->get('threshold', 40);

        $users = User::where('role', 'buyer')
            ->where('trust_score', '<=', $threshold)
            ->orderBy('trust_score')
            ->with('shop')
            ->paginate(30);

        // Répartition des scores
        $scoreDistribution = [
            'critical'  => User::where('role', 'buyer')->where('trust_score', '<', 20)->count(),
            'low'       => User::where('role', 'buyer')->whereBetween('trust_score', [20, 39])->count(),
            'medium'    => User::where('role', 'buyer')->whereBetween('trust_score', [40, 69])->count(),
            'good'      => User::where('role', 'buyer')->where('trust_score', '>=', 70)->count(),
        ];

        return view('admin.users.low-scores', compact(
            'users',
            'threshold',
            'scoreDistribution'
        ));
    }
}
