<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    /**
     * Redirection vers Google/Facebook.
     *
     * Le rôle est conservé en session afin de savoir
     * si l'utilisateur vient de l'inscription Buyer
     * ou de l'inscription Seller.
     */
    public function redirect(string $provider, string $role)
    {
        $this->validateProvider($provider);
        $this->validateRole($role);

        session([
            'social_registration_role' => $role,
        ]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Callback du fournisseur social.
     */
    public function callback(
        string $provider,
        SocialAuthService $socialAuthService,
        DashboardService $dashboardService
    ) {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = $socialAuthService->authenticate(
                $provider,
                $socialUser
            );

            /*
             * Nouvel utilisateur :
             *
             * Les données du fournisseur sont conservées
             * en session et l'utilisateur doit compléter
             * son inscription.
             */
            if (! $user) {
              
                return redirect()->route('social.complete');
            }

            /*
             * Utilisateur existant :
             * connexion immédiate.
             */
            Auth::login($user, true);

            request()->session()->regenerate();

            return $dashboardService->dashboard($user);
        } catch (Throwable $e) {

            report($e);

            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => $e->getMessage()
                        ?: 'Impossible de se connecter avec ce compte social.',
                ]);
        }
    }

    /**
     * Affiche le formulaire de complétion.
     *
     * Le formulaire sera différent selon le rôle :
     * Buyer ou Seller.
     */
    public function showComplete()
    {
        $pending = session('social_pending');

        if (! $pending) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => 'Votre session d’inscription a expiré.',
                ]);
        }

        $role = $pending['role'] ?? session('social_registration_role');

        if ($role === 'seller') {
            return view('auth.social-seller-complete', [
                'socialPending' => $pending,
            ]);
        }

        return view('auth.social-complete', [
            'socialPending' => $pending,
        ]);
    }

    /**
     * Finalise l'inscription Buyer.
     */
    public function completeBuyer(
        Request $request,
        SocialAuthService $socialAuthService,
        DashboardService $dashboardService
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

            $user = $socialAuthService->completeBuyerRegistration(
                $phoneMomo,
                $validated['momo_operator']
            );

            Auth::login($user, true);

            $request->session()->regenerate();

            return $dashboardService->dashboard($user);
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
     * Finalise l'inscription Seller.
     */
    public function completeSeller(
        Request $request,
        SocialAuthService $socialAuthService
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
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
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

            $user = $socialAuthService->completeSellerRegistration(
                phoneMomo: $phoneMomo,
                momoOperator: $validated['momo_operator'],
                phone: $validated['phone'] ?? null,
                shopName: $validated['shop_name'],
                city: $validated['city'],
                category: $validated['category'],
                description: $validated['description'] ?? null,
            );

            Auth::login($user, true);

            $request->session()->regenerate();

            /*
             * Nouveau vendeur :
             * après inscription complète,
             * direction KYC.
             */
            return redirect()->route('seller.kyc.create');
        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'social' => $e->getMessage(),
                ]);
        }
    }

    protected function validateProvider(string $provider): void
    {
        abort_unless(
            in_array($provider, ['google', 'facebook'], true),
            404
        );
    }

    protected function validateRole(string $role): void
    {
        abort_unless(
            in_array($role, ['buyer', 'seller'], true),
            404
        );
    }
    public function loginRedirect(string $provider)
    {
        $this->validateProvider($provider);

        /*
     * Aucun rôle ici.
     *
     * Si le compte existe, SocialAuthService retrouvera
     * son rôle depuis la base de données.
     */
        session()->forget('social_registration_role');

        return Socialite::driver($provider)->redirect();
    }
}
