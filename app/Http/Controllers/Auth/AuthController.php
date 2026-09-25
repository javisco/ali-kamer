<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyerRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SellerRegisterRequest;
use App\Services\AuthService;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private AuthService $authService
    ) {}

    // ═══════════════════════════════════════════════════════════════════
    // PAGE DE CHOIX DU RÔLE
    // ═══════════════════════════════════════════════════════════════════

    // L'utilisateur choisit d'abord "Je veux acheter" ou "Je veux vendre"
    // avant de voir le formulaire d'inscription
    public function showRegisterChoice()
    {
        if (Auth::check()) {
            return $this->dashboardService->dashboard(Auth::user());
        }

        return view('auth.register-choice');
    }

    // ═══════════════════════════════════════════════════════════════════
    // INSCRIPTION ACHETEUR
    // ═══════════════════════════════════════════════════════════════════

    public function showRegisterBuyer()
    {
        if (Auth::check()) {
            return $this->dashboardService->dashboard(Auth::user());
        }

        return view('auth.register-buyer');
    }

    public function registerBuyer(BuyerRegisterRequest $request)
    {
        $request->validated();

        // Vérification blacklist (phone + IP)
        $user = $this->authService->registerBuyer($request->all());

        // Connexion automatique après inscription
        Auth::login($user);

        // Envoyer l'email de vérification via Brevo
        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('verification.notice')
            ->with('message', 'Compte créé avec succès ! Vérifiez votre email pour continuer.');
    }

    // ═══════════════════════════════════════════════════════════════════
    // INSCRIPTION VENDEUR
    // ═══════════════════════════════════════════════════════════════════

    public function showRegisterSeller()
    {
        if (Auth::check()) {
            return $this->dashboardService->dashboard(Auth::user());
        }

        // Catégories pour le select boutique
        $categories = \App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('auth.register-seller', compact('categories'));
    }

    public function registerSeller(SellerRegisterRequest $request)
    {
        $request->validated();

        // Vérification blacklist (phone_momo + phone + IP)
        $user = $this->authService->registerSeller($request->all());

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        // Après vérification email → DashboardService le redirigera vers KYC
        return redirect()
            ->route('verification.notice')
            ->with('message', 'Compte vendeur créé ! Vérifiez votre email puis soumettez votre dossier KYC.');
    }

    // ═══════════════════════════════════════════════════════════════════
    // CONNEXION
    // ═══════════════════════════════════════════════════════════════════

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->dashboardService->dashboard(Auth::user());
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        // ── Rate limiting : 5 tentatives max par 15 minutes ───────────
        // Clé unique par email + IP pour éviter le bruteforce
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('email'))
                ->with('fail', "Trop de tentatives. Réessayez dans {$seconds} secondes.");
        }

        // ── Tentative de connexion ────────────────────────────────────
        if (! Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            // Incrémenter le compteur d'échecs (expire après 15 min)
            RateLimiter::hit($throttleKey, 900);

            return back()
                ->withInput($request->only('email'))
                ->with('fail', 'Email ou mot de passe incorrect.');
        }

        // ── Connexion réussie — réinitialiser le compteur ─────────────
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();

       

        // ── Vérification blacklist à la connexion ─────────────────────
        // Si le numéro a été blacklisté après l'inscription
        try {
            $this->authService->checkBlacklist(
                phone: $user->phone,
                phoneMomo: $user->phone_momo
            );
        } catch (\Exception $e) {
            Auth::logout();
            dd($e);
            $request->session()->invalidate();

            return back()->with('fail', 'Accès refusé. Contactez le support.');
        }

        // ── Email non vérifié → page de vérification ──────────────────
        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice');
        }

        // ── Redirection selon le rôle ─────────────────────────────────
        return $this->dashboardService->dashboard($user);
    }

    // ═══════════════════════════════════════════════════════════════════
    // DÉCONNEXION
    // ═══════════════════════════════════════════════════════════════════

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('buyer.home');
    }
}
