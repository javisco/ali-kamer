<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Review;
use App\Models\TrustEvent;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Sous-ensemble d'événements de confiance affichables à l'acheteur.
     * On exclut volontairement tout signal antifraude / motif interne
     * (fraud_*, cni_*, multiple_accounts, fake_information, ...).
     */
    private const DISPLAYABLE_TRUST_EVENTS = [
        'order_completed',
        'order_completed_fast',
        'dispute_won',
        'dispute_lost',
        'kyc_approved',
        'buyer_reliable',
        'order_cancelled',
        'order_cancelled_repeat',
        'milestone_10_orders',
        'milestone_20_orders',
        'milestone_50_orders',
        'blacklist_lifted',
    ];

    /**
     * Espace Acheteur : Mon profil.
     * L'utilisateur est toujours résolu via Auth::user() — aucun identifiant
     * n'est accepté dans l'URL, ce qui élimine tout risque d'IDOR.
     */
    public function index(): View
    {
        $user = $this->currentBuyer();

        // ── Activité : agrégations SQL (pas de chargement complet) ────────
        $orders = Order::where('buyer_id', $user->id);

        $stats = [
            'orders_total'       => (clone $orders)->count(),
            'orders_completed'   => (clone $orders)
                ->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_AUTO_COMPLETED])
                ->count(),
            'orders_in_progress' => (clone $orders)
                ->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_AWAITING_PAYMENT,
                    Order::STATUS_PAID,
                    Order::STATUS_PREPARING,
                    Order::STATUS_REGISTERED_ORIGIN,
                    Order::STATUS_IN_TRANSIT,
                    Order::STATUS_ARRIVED_DESTINATION,
                    Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                ])
                ->count(),
            'orders_cancelled'   => (clone $orders)
                ->whereIn('status', [Order::STATUS_CANCELLED, Order::STATUS_FAILED])
                ->count(),
            'orders_disputed'    => (clone $orders)
                ->where('status', Order::STATUS_DISPUTED)
                ->count(),
        ];

        // ── Avis publiés par l'acheteur ───────────────────────────────────
        $stats['reviews_published'] = Review::where('reviewer_id', $user->id)->count();

        // ── Évaluations reçues (vendeur → acheteur) ───────────────────────
        $receivedBase = Review::forBuyer($user->id);
        $stats['reviews_received']   = (clone $receivedBase)->count();
        $stats['avg_rating_received'] = $stats['reviews_received'] > 0
            ? round((float) (clone $receivedBase)->avg('rating'), 2)
            : 0.0;

        // ── Litiges déclarés par l'acheteur ───────────────────────────────
        $stats['disputes_total'] = Dispute::whereHas(
            'order',
            fn ($q) => $q->where('buyer_id', $user->id)
        )->count();

        // ── Réputation : le TrustScore/RiskProfile sont calculés par
        //    TrustService — on ne fait ici que les LIRE. ─────────────────
        $lastTrustUpdate = TrustEvent::where('user_id', $user->id)->latest()->value('created_at');

        $trustEvents = TrustEvent::where('user_id', $user->id)
            ->whereIn('type', self::DISPLAYABLE_TRUST_EVENTS)
            ->latest()
            ->limit(8)
            ->get();

        // ── Commandes récentes (eager loading, pas de N+1) ────────────────
        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['shop', 'payment', 'shipment'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Évaluations reçues (détail paginé) ────────────────────────────
        $reviewsReceived = Review::forBuyer($user->id)
            ->with(['reviewer', 'order.shop'])
            ->latest()
            ->paginate(5)
            ->withQueryString();

        // ── Restrictions éventuelles (statut réel du compte) ──────────────
        $restrictions = $user->getActiveRestrictions();

        return view('buyer.profile.index', compact(
            'user',
            'stats',
            'lastTrustUpdate',
            'trustEvents',
            'recentOrders',
            'reviewsReceived',
            'restrictions',
        ));
    }

    /**
     * Formulaire de modification des informations autorisées.
     */
    public function edit(): View
    {
        $user = $this->currentBuyer();

        return view('buyer.profile.edit', compact('user'));
    }

    /**
     * Mise à jour des informations modifiables par l'acheteur.
     * Le TrustScore, le RiskProfile et le statut de sanction ne sont
     * jamais modifiables ici (non exposés dans la validation).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $this->currentBuyer();

        $data = $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone,' . $user->id],
        ]);

        $emailChanged = $user->email !== $data['email'];
        $phoneChanged = ($user->phone ?? null) !== ($data['phone'] ?? null);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        // Changement sensible → la vérification doit être refaite.
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        if ($phoneChanged) {
            $user->phone_verified_at = null;
        }

        $user->save();

        if ($emailChanged && $user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        if ($emailChanged || $phoneChanged) {
            Log::info('buyer.profile.sensitive_update', [
                'user_id'       => $user->id,
                'email_changed' => $emailChanged,
                'phone_changed' => $phoneChanged,
                'ip'            => $request->ip(),
            ]);
        }

        $message = 'Vos informations ont été mises à jour.';
        if ($emailChanged) {
            $message .= ' Un email de vérification vous a été envoyé pour votre nouvelle adresse.';
        }
        if ($phoneChanged) {
            $message .= ' Votre numéro de téléphone devra être revérifié.';
        }

        return redirect()->route('buyer.profile')->with('success', $message);
    }

    /**
     * Changement de mot de passe (vérifie le mot de passe actuel).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $this->currentBuyer();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->input('password'))]);

        Log::info('buyer.profile.password_changed', [
            'user_id' => $user->id,
            'ip'      => $request->ip(),
        ]);

        return redirect()->route('buyer.profile')->with('success', 'Votre mot de passe a été mis à jour.');
    }

    /**
     * Résout l'acheteur connecté. Les routes garantissent déjà le rôle,
     * ce garde-fou protège les appels directs au contrôleur.
     */
    private function currentBuyer(): User
    {
        $user = Auth::user();

        abort_unless($user instanceof User && $user->isBuyer(), 403, 'Accès réservé aux acheteurs.');

        return $user;
    }
}
