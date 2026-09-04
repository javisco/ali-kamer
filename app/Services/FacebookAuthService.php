<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use RuntimeException;

class FacebookAuthService
{
    /**
     * Traite l'utilisateur Facebook après le callback.
     *
     * Ne crée pas directement un compte incomplet.
     * Si l'utilisateur est nouveau, on place ses informations
     * en session afin de demander les informations obligatoires.
     */
    public function authenticate(
        SocialUser $socialUser,
        string $mode,
        ?string $role = null
    ): ?User {
        $providerId = $socialUser->getId();

        if (! $providerId) {
            throw new RuntimeException(
                'Impossible de récupérer votre identité auprès de Facebook.'
            );
        }

        if (! in_array($mode, ['login', 'register'], true)) {
            throw new RuntimeException(
                'Mode d’authentification Facebook invalide.'
            );
        }

        if (
            $mode === 'register' &&
            ! in_array($role, [
                User::ROLE_BUYER,
                User::ROLE_SELLER,
            ], true)
        ) {
            throw new RuntimeException(
                'Rôle d’inscription invalide.'
            );
        }

        $email = $socialUser->getEmail();

        /*
         * ---------------------------------------------------------
         * 1. Le compte Facebook est déjà lié à Ali-Kamer
         * ---------------------------------------------------------
         */
        $socialAccount = SocialAccount::where(
            'provider',
            'facebook'
        )
            ->where('provider_id', $providerId)
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;

            $this->ensureUserCanLogin($user);

            $socialAccount->update([
                'provider_email' => $email,
                'avatar' => $socialUser->getAvatar(),
            ]);

            return $user;
        }

        /*
         * ---------------------------------------------------------
         * 2. Un compte Ali-Kamer existe déjà avec cet email
         * ---------------------------------------------------------
         */
        if ($email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $this->ensureUserCanLogin($user);

                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider' => 'facebook',
                    'provider_id' => $providerId,
                    'provider_email' => $email,
                    'avatar' => $socialUser->getAvatar(),
                ]);

                return $user;
            }
        }

        /*
         * ---------------------------------------------------------
         * 3. NOUVEL UTILISATEUR
         * ---------------------------------------------------------
         *
         * En mode LOGIN :
         * on ne crée surtout pas automatiquement un compte.
         *
         * En mode REGISTER :
         * on prépare la suite de l'inscription.
         */
        if ($mode === 'login') {
            throw new RuntimeException(
                'Aucun compte Ali-Kamer n’est associé à ce compte Facebook. '
                . 'Veuillez utiliser l’inscription avec Facebook.'
            );
        }

        Session::put('facebook_pending', [
            'provider' => 'facebook',
            'provider_id' => $providerId,
            'provider_email' => $email,
            'name' => $socialUser->getName()
                ?: $socialUser->getNickname()
                ?: 'Utilisateur Ali-Kamer',
            'avatar' => $socialUser->getAvatar(),
            'role' => $role,
        ]);

        return null;
    }

    /**
     * Création d'un acheteur après récupération
     * des informations obligatoires.
     */
    public function completeBuyerRegistration(
        string $phoneMomo,
        string $momoOperator
    ): User {
        $pending = Session::get('facebook_pending');

        $this->ensurePendingRegistration(
            $pending,
            User::ROLE_BUYER
        );

        app(AuthService::class)->checkBlacklist(
            phoneMomo: $phoneMomo,
            email: $pending['provider_email'] ?? null
        );

        if (User::where('phone_momo', $phoneMomo)->exists()) {
            throw new RuntimeException(
                'Ce numéro Mobile Money est déjà associé à un compte Ali-Kamer.'
            );
        }

        return DB::transaction(function () use (
            $pending,
            $phoneMomo,
            $momoOperator
        ) {
            if (
                ! empty($pending['provider_email']) &&
                User::where(
                    'email',
                    $pending['provider_email']
                )->exists()
            ) {
                throw new RuntimeException(
                    'Un compte Ali-Kamer existe déjà avec cette adresse email.'
                );
            }

            /*
             * On utilise AuthService uniquement pour la création
             * métier du compte.
             */
            $user = app(AuthService::class)->registerBuyer([
                'name' => $pending['name'],
                'phone_momo' => $phoneMomo,
                'email' => $pending['provider_email'] ?? null,
                'password' => Str::random(64),
            ]);

            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => 'facebook',
                'provider_id' => $pending['provider_id'],
                'provider_email' => $pending['provider_email'] ?? null,
                'avatar' => $pending['avatar'] ?? null,
            ]);

            Session::forget('facebook_pending');
            Session::forget('facebook_auth_mode');

            return $user;
        });
    }

    /**
     * Création d'un vendeur.
     *
     * La création du vendeur et de sa boutique reste entièrement
     * dans AuthService::registerSeller().
     */
    public function completeSellerRegistration(
        string $phoneMomo,
        string $momoOperator,
        ?string $phone,
        string $shopName,
        string $city,
        string $category,
        ?string $description
    ): User {
        $pending = Session::get('facebook_pending');

        $this->ensurePendingRegistration(
            $pending,
            User::ROLE_SELLER
        );

        app(AuthService::class)->checkBlacklist(
            phone: $phone,
            phoneMomo: $phoneMomo,
            email: $pending['provider_email'] ?? null
        );

        if (User::where('phone_momo', $phoneMomo)->exists()) {
            throw new RuntimeException(
                'Ce numéro Mobile Money est déjà associé à un compte Ali-Kamer.'
            );
        }

        if (
            ! empty($pending['provider_email']) &&
            User::where(
                'email',
                $pending['provider_email']
            )->exists()
        ) {
            throw new RuntimeException(
                'Un compte Ali-Kamer existe déjà avec cette adresse email.'
            );
        }

        return DB::transaction(function () use (
            $pending,
            $phoneMomo,
            $momoOperator,
            $phone,
            $shopName,
            $city,
            $category,
            $description
        ) {
            /*
             * La logique métier vendeur reste dans AuthService.
             */
            $user = app(AuthService::class)->registerSeller([
                'name' => $pending['name'],
                'phone' => $phone,
                'phone_momo' => $phoneMomo,
                'momo_operator' => $momoOperator,
                'email' => $pending['provider_email'] ?? null,
                'password' => Str::random(64),

                'shop_name' => $shopName,
                'city' => $city,
                'category' => $category,
                'description' => $description,
            ]);

            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => 'facebook',
                'provider_id' => $pending['provider_id'],
                'provider_email' => $pending['provider_email'] ?? null,
                'avatar' => $pending['avatar'] ?? null,
            ]);

            Session::forget('facebook_pending');
            Session::forget('facebook_auth_mode');

            return $user;
        });
    }

    /**
     * Vérifie qu'une session Facebook d'inscription
     * existe et correspond au rôle attendu.
     */
    protected function ensurePendingRegistration(
        ?array $pending,
        string $expectedRole
    ): void {
        if (! $pending) {
            throw new RuntimeException(
                'Votre session d’inscription Facebook a expiré.'
            );
        }

        if (
            ($pending['provider'] ?? null) !== 'facebook'
        ) {
            throw new RuntimeException(
                'Session Facebook invalide.'
            );
        }

        if (
            ($pending['role'] ?? null) !== $expectedRole
        ) {
            throw new RuntimeException(
                'Le rôle sélectionné ne correspond pas à cette inscription.'
            );
        }

        if (empty($pending['provider_id'])) {
            throw new RuntimeException(
                'Identité Facebook introuvable.'
            );
        }
    }

    /**
     * Vérifie si le compte Ali-Kamer peut se connecter.
     */
    protected function ensureUserCanLogin(User $user): void
    {
        if ($user->isBanned()) {
            throw new RuntimeException(
                'Votre compte Ali-Kamer est définitivement bloqué.'
            );
        }

        if (
            $user->status === User::STATUS_SUSPENDED
        ) {
            throw new RuntimeException(
                'Votre compte Ali-Kamer est actuellement suspendu.'
            );
        }
    }
}