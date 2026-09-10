<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Dispute;
use App\Models\KycDocument;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\CampayService;
use App\Services\ElgiopayService;
use App\Services\KycService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AdminDashboardController extends Controller
{
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

        // Solde Campay en temps réel
        $elgiopayBalance = null;
        try {
            $elgiopayBalance = app(ElgiopayService::class)->getBalance();
        } catch (\Exception $e) {
            \Log::warning('Campay balance unavailable', ['error' => $e->getMessage()]);
        }

        return view('admin.dashboard', compact(
            'kpis',
            'pendingKyc',
            'openDisputes',
            'recentOrders',
            'elgiopayBalance'
        ));
    }

    // Gestion des utilisateurs
    public function users()
    {

        $query = User::with('shop')->latest();

        // Filtre par rôle si demandé
        if (request('role')) {
            $query->where('role', request('role'));
        }

        $users = $query->paginate(30);

        return view('admin.users.index', compact('users'));
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
            ->where('trust_score', '<', $threshold)
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

    // Retrait des fonds de la plateforme vers Mobile Money (Admin)
    public function withdraw(Request $request, ElgiopayService $elgiopay)
    {
        $request->validate([
            'amount'         => ['required', 'integer', 'min:1000'],
            'phone'          => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'operator'       => ['required', 'in:mtn,orange'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
        ], [
            'amount.min'     => 'Le montant minimum de retrait est de 1 000 FCFA.',
            'phone.regex'    => 'Le numéro doit être au format camerounais à 9 chiffres (6XXXXXXXX).',
            'operator.in'    => 'L\'opérateur doit être MTN ou Orange.',
        ]);

        try {
            // Vérifier le solde disponible en temps réel
            $balance = $elgiopay->getBalance();
            $availableBalance = (int) ($balance['available_balance'] ?? 0);

            if ($request->amount > $availableBalance) {
                return back()->withErrors([
                    'amount' => 'Solde disponible insuffisant sur le compte Elgiopay (' . number_format($availableBalance, 0, ',', ' ') . ' FCFA disponibles).'
                ]);
            }

            $phone = '237' . ltrim($request->phone, '0');
            $reference = 'ADMIN-WITHDRAWAL-' . auth()->id() . '-' . Str::uuid();
            $recipientName = $request->recipient_name ?: auth()->user()->name;

            $elgiopay->disburse(
                phone: $phone,
                grossAmount: $request->amount,
                reference: $reference,
                description: "Retrait plateforme Ali-Kamer — Admin " . auth()->user()->name,
                operator: $request->operator,
                recipientName: $recipientName
            );

            AdminLog::record(
                auth()->user(),
                'admin.withdrawal',
                'platform',
                null,
                "Retrait de " . number_format($request->amount, 0, ',', ' ') . " FCFA vers {$request->operator} {$phone} ({$recipientName})"
            );

            return back()->with('success', 'Retrait de ' . number_format($request->amount, 0, ',', ' ') . ' FCFA effectué avec succès vers votre compte Mobile Money.');
        } catch (\Exception $e) {
            \Log::error('Admin withdrawal failed', ['error' => $e->getMessage()]);
            return back()->withErrors([
                'amount' => 'Échec du retrait : ' . $e->getMessage(),
            ]);
        }
    }
}
