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
use App\Services\KycService;
use App\Services\WalletService;
use Illuminate\Http\Request;


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
        $campayBalance = null;
        try {
            $campayBalance = app(CampayService::class)->getBalance();
        } catch (\Exception $e) {
            \Log::warning('Campay balance unavailable', ['error' => $e->getMessage()]);
        }

        return view('admin.dashboard', compact(
            'kpis',
            'pendingKyc',
            'openDisputes',
            'recentOrders',
            'campayBalance'
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
}
