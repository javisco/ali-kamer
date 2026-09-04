<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use RuntimeException;

class SocialAuthService
{
    /**
     * Authentifie un utilisateur existant avec son compte social.
     *
     * Pour un nouvel utilisateur, aucun User n'est créé immédiatement.
     * Les informations sociales sont conservées en session.
     */
    public function authenticate(
        string $provider,
        SocialUser $socialUser
    ): ?User {
        if (! $socialUser->getId()) {
            throw new RuntimeException(
                'Impossible de récupérer votre identité auprès du fournisseur.'
            );
        }

        $email = $socialUser->getEmail();

        /*
         * Le rôle provient du bouton sur lequel
         * l'utilisateur a cliqué.
         */
        $role = session('social_registration_role', User::ROLE_BUYER);

        if (! in_array($role, [
            User::ROLE_BUYER,
            User::ROLE_SELLER,
        ], true)) {
            $role = User::ROLE_BUYER;
        }

        /*
         |--------------------------------------------------------------------------
         | 1. Compte social déjà lié
         |--------------------------------------------------------------------------
         */

        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {

            $user = $socialAccount->user;

            $this->ensureUserCanLogin($user);

            $socialAccount->update([
                'provider_email' => $email,
                'avatar'         => $socialUser->getAvatar(),
            ]);

            session()->forget('social_registration_role');

            return $user;
        }

        /*
         |--------------------------------------------------------------------------
         | 2. Compte Ali-Kamer existant avec le même email
         |--------------------------------------------------------------------------
         */

        $user = $email
            ? User::where('email', $email)->first()
            : null;

        if ($user) {

            $this->ensureUserCanLogin($user);

            SocialAccount::create([
                'user_id'        => $user->id,
                'provider'       => $provider,
                'provider_id'    => $socialUser->getId(),
                'provider_email' => $email,
                'avatar'         => $socialUser->getAvatar(),
            ]);

            session()->forget('social_registration_role');

            return $user;
        }

        /*
         |--------------------------------------------------------------------------
         | 3. Nouvel utilisateur
         |--------------------------------------------------------------------------
         */

        Session::put('social_pending', [
            'provider'       => $provider,
            'provider_id'    => $socialUser->getId(),
            'provider_email' => $email,

            'name' => $socialUser->getName()
                ?: $socialUser->getNickname()
                ?: 'Utilisateur Ali-Kamer',

            'avatar' => $socialUser->getAvatar(),

            /*
             * Très important :
             * on mémorise Buyer ou Seller.
             */
            'role' => $role,
        ]);

        return null;
    }

    /**
     * Finalise l'inscription Buyer.
     */
    public function completeBuyerRegistration(
        string $phoneMomo,
        string $momoOperator
    ): User {
        $pending = $this->getPendingRegistration();

        if (($pending['role'] ?? null) !== User::ROLE_BUYER) {
            throw new RuntimeException(
                'Cette session ne correspond pas à une inscription acheteur.'
            );
        }

        $this->checkBlacklist(
            phoneMomo: $phoneMomo,
            email: $pending['provider_email'] ?? null
        );

        $this->ensurePhoneMomoAvailable($phoneMomo);

        return DB::transaction(function () use (
            $pending,
            $phoneMomo,
            $momoOperator
        ) {

            $this->ensureEmailAvailable(
                $pending['provider_email'] ?? null
            );

            /*
             * Le password reste NON NULL.
             *
             * L'utilisateur social possède donc un mot de passe
             * aléatoire hashé, sans que celui-ci soit demandé
             * pendant l'inscription Google.
             */
            $user = User::create([
                'name' => $pending['name'],

                'email' => $pending['provider_email'] ?? null,

                'phone_momo' => $phoneMomo,

                'momo_operator' => $momoOperator,

                'password' => Str::random(64),

                'role' => User::ROLE_BUYER,

                'status' => User::STATUS_ACTIVE,

                'email_verified_at' => now(),
            ]);

            $this->createSocialAccount(
                $user,
                $pending
            );

            $this->clearPendingRegistration();

            return $user;
        });
    }

    /**
     * Finalise l'inscription Seller.
     *
     * Cette méthode utilise ton AuthService existant
     * afin de conserver la même logique que l'inscription
     * Seller classique.
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
        $pending = $this->getPendingRegistration();

        if (($pending['role'] ?? null) !== User::ROLE_SELLER) {
            throw new RuntimeException(
                'Cette session ne correspond pas à une inscription vendeur.'
            );
        }

        /*
         |--------------------------------------------------------------------------
         | Blacklist
         |--------------------------------------------------------------------------
         */

        $this->checkBlacklist(
            phoneMomo: $phoneMomo,
            email: $pending['provider_email'] ?? null
        );

        /*
         |--------------------------------------------------------------------------
         | Vérification du numéro MoMo
         |--------------------------------------------------------------------------
         */

        $this->ensurePhoneMomoAvailable($phoneMomo);

        /*
         |--------------------------------------------------------------------------
         | Préparation des données pour ton AuthService
         |--------------------------------------------------------------------------
         */

        $data = [
            'name' => $pending['name'],

            'email' => $pending['provider_email'] ?? null,

            'phone' => $phone,

            'phone_momo' => $phoneMomo,

            'momo_operator' => $momoOperator,

            /*
             * Comme le password de ta table est NON NULL,
             * on donne un password aléatoire.
             */
            'password' => Str::random(64),

            'password_confirmation' => null,

            'shop_name' => $shopName,

            'city' => $city,

            'category' => $category,

            'description' => $description,
        ];

        /*
         |--------------------------------------------------------------------------
         | Ton inscription Seller normale
         |--------------------------------------------------------------------------
         */

        $user = app(AuthService::class)
            ->registerSeller($data);

        /*
         |--------------------------------------------------------------------------
         | Liaison Google/Facebook
         |--------------------------------------------------------------------------
         */

        $this->createSocialAccount(
            $user,
            $pending
        );

        /*
         |--------------------------------------------------------------------------
         | Nettoyage
         |--------------------------------------------------------------------------
         */

        $this->clearPendingRegistration();

        return $user;
    }

    /**
     * Récupère l'inscription sociale temporaire.
     */
    protected function getPendingRegistration(): array
    {
        $pending = Session::get('social_pending');

        if (! $pending) {
            throw new RuntimeException(
                'Votre session d’inscription sociale a expiré.'
            );
        }

        return $pending;
    }

    /**
     * Vérification blacklist.
     */
    protected function checkBlacklist(
        ?string $phoneMomo = null,
        ?string $email = null
    ): void {
        app(AuthService::class)->checkBlacklist(
            phoneMomo: $phoneMomo,
            email: $email
        );
    }

    /**
     * Vérifie qu'un numéro MoMo n'existe pas déjà.
     */
    protected function ensurePhoneMomoAvailable(
        string $phoneMomo
    ): void {
        if (
            User::where('phone_momo', $phoneMomo)->exists()
        ) {
            throw new RuntimeException(
                'Ce numéro Mobile Money est déjà associé à un compte Ali-Kamer.'
            );
        }
    }

    /**
     * Vérifie que l'email n'est pas déjà utilisé.
     */
    protected function ensureEmailAvailable(
        ?string $email
    ): void {
        if (
            $email &&
            User::where('email', $email)->exists()
        ) {
            throw new RuntimeException(
                'Un compte Ali-Kamer existe déjà avec cette adresse email.'
            );
        }
    }

    /**
     * Création de la liaison sociale.
     */
    protected function createSocialAccount(
        User $user,
        array $pending
    ): SocialAccount {
        return SocialAccount::create([
            'user_id' => $user->id,

            'provider' => $pending['provider'],

            'provider_id' => $pending['provider_id'],

            'provider_email' => $pending['provider_email'] ?? null,

            'avatar' => $pending['avatar'] ?? null,
        ]);
    }

    /**
     * Nettoyage de la session.
     */
    protected function clearPendingRegistration(): void
    {
        Session::forget([
            'social_pending',
            'social_registration_role',
        ]);
    }

    /**
     * Vérifie si le compte peut se connecter.
     */
    protected function ensureUserCanLogin(User $user): void
    {
        if ($user->isBanned()) {
            throw new RuntimeException(
                'Votre compte Ali-Kamer est définitivement bloqué.'
            );
        }

        if ($user->status === User::STATUS_SUSPENDED) {
            throw new RuntimeException(
                'Votre compte Ali-Kamer est actuellement suspendu.'
            );
        }
    }
}