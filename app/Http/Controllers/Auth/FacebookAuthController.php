<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FacebookAuthService;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class FacebookAuthController extends Controller
{
    /**
     * Connexion avec Facebook.
     *
     * Aucun rôle n'est fourni.
     * Le rôle vient du compte Ali-Kamer existant.
     */
    public function loginRedirect()
    {
        session([
            'facebook_auth_mode' => 'login',
        ]);

        session()->forget('facebook_pending');

        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Inscription avec Facebook.
     *
     * Le rôle est choisi avant d'aller chez Facebook.
     */
    public function registerRedirect(string $role)
    {
        abort_unless(
            in_array($role, [
                User::ROLE_BUYER,
                User::ROLE_SELLER,
            ], true),
            404
        );

        session([
            'facebook_auth_mode' => 'register',
            'facebook_registration_role' => $role,
        ]);

        session()->forget('facebook_pending');

        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Callback Facebook.
     */
    public function callback(
        FacebookAuthService $facebookAuthService
    ) {
        try {
            $mode = session(
                'facebook_auth_mode',
                'login'
            );

            $role = session(
                'facebook_registration_role'
            );

            $socialUser = Socialite::driver('facebook')
                ->user();

            $user = $facebookAuthService->authenticate(
                socialUser: $socialUser,
                mode: $mode,
                role: $role
            );

            /*
             * Nouveau compte :
             * les informations obligatoires seront demandées
             * dans la page de complétion.
             */
            if (! $user) {
                return redirect()->route(
                    'facebook.complete'
                );
            }

            /*
             * Compte existant.
             */
            Auth::login($user, true);

            request()
                ->session()
                ->regenerate();

            session()->forget('facebook_auth_mode');
            session()->forget('facebook_registration_role');

            return app(DashboardService::class)
                ->dashboard($user);

        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => $e->getMessage()
                        ?: 'Impossible de se connecter avec Facebook.',
                ]);
        }
    }

    /**
     * Page intermédiaire de complétion.
     */
    public function showComplete()
    {
        $pending = session('facebook_pending');

        if (! $pending) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'social' =>
                        'Votre session d’inscription Facebook a expiré.',
                ]);
        }

        if (
            ($pending['provider'] ?? null) !== 'facebook'
        ) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => 'Session Facebook invalide.',
                ]);
        }

        if (
            ($pending['role'] ?? null) === User::ROLE_SELLER
        ) {
            return redirect()->route(
                'facebook.complete.seller'
            );
        }

        return redirect()->route(
            'facebook.complete.buyer'
        );
    }

    /**
     * Affiche le formulaire acheteur.
     */
    public function showBuyerComplete()
    {
        $this->ensurePendingRole(User::ROLE_BUYER);

        return view('auth.facebook-complete');
    }

    /**
     * Finalise l'inscription acheteur.
     */
    public function completeBuyer(
        Request $request,
        FacebookAuthService $facebookAuthService
    ) {
        $validated = $request->validate([
            'phone_momo' => [
                'required',
                'string',
                'regex:/^(6\d{8}|2376\d{8})$/',
            ],

            'momo_operator' => [
                'required',
                'in:mtn,orange',
            ],
        ], [
            'phone_momo.required' =>
                'Votre numéro Mobile Money est obligatoire.',

            'phone_momo.regex' =>
                'Entrez un numéro camerounais valide.',

            'momo_operator.required' =>
                'Veuillez sélectionner votre opérateur.',

            'momo_operator.in' =>
                'Opérateur Mobile Money invalide.',
        ]);

        $phoneMomo = preg_replace(
            '/^237/',
            '',
            $validated['phone_momo']
        );

        try {
            $user = $facebookAuthService
                ->completeBuyerRegistration(
                    phoneMomo: $phoneMomo,
                    momoOperator: $validated['momo_operator']
                );

            Auth::login($user, true);

            $request
                ->session()
                ->regenerate();

            return app(DashboardService::class)
                ->dashboard($user);

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'phone_momo' => $e->getMessage(),
                ]);
        }
    }

    /**
     * Affiche le formulaire vendeur.
     */
    public function showSellerComplete()
    {
        $this->ensurePendingRole(User::ROLE_SELLER);

        return view('auth.facebook-seller-complete');
    }

    /**
     * Finalise l'inscription vendeur.
     */
    public function completeSeller(
        Request $request,
        FacebookAuthService $facebookAuthService
    ) {
        $validated = $request->validate([
            'phone_momo' => [
                'required',
                'string',
                'regex:/^(6\d{8}|2376\d{8})$/',
            ],

            'momo_operator' => [
                'required',
                'in:mtn,orange',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'shop_name' => [
                'required',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'phone_momo.required' =>
                'Votre numéro Mobile Money est obligatoire.',

            'phone_momo.regex' =>
                'Entrez un numéro camerounais valide.',

            'momo_operator.required' =>
                'Veuillez sélectionner votre opérateur.',

            'momo_operator.in' =>
                'Opérateur Mobile Money invalide.',

            'shop_name.required' =>
                'Le nom de votre boutique est obligatoire.',

            'city.required' =>
                'La ville de votre boutique est obligatoire.',

            'category.required' =>
                'La catégorie de votre boutique est obligatoire.',
        ]);

        $phoneMomo = preg_replace(
            '/^237/',
            '',
            $validated['phone_momo']
        );

        try {
            $user = $facebookAuthService
                ->completeSellerRegistration(
                    phoneMomo: $phoneMomo,
                    momoOperator: $validated['momo_operator'],
                    phone: $validated['phone'] ?? null,
                    shopName: $validated['shop_name'],
                    city: $validated['city'],
                    category: $validated['category'],
                    description: $validated['description'] ?? null,
                );

            Auth::login($user, true);

            $request
                ->session()
                ->regenerate();

            /*
             * Pas de KYC dans FacebookAuthService.
             *
             * Le vendeur arrive simplement sur le formulaire KYC.
             */
            return redirect()->route(
                'seller.kyc.create'
            );

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'phone_momo' => $e->getMessage(),
                ]);
        }
    }

    /**
     * Vérifie le rôle présent dans la session.
     */
    protected function ensurePendingRole(string $role): void
    {
        $pending = session('facebook_pending');

        abort_unless(
            $pending &&
            ($pending['provider'] ?? null) === 'facebook' &&
            ($pending['role'] ?? null) === $role,
            404
        );
    }
}
