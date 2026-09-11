<?php

namespace App\Services;

use App\Models\Blacklist;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    // ── VÉRIFICATION BLACKLIST ────────────────────────────────────────

    // Appelée à chaque inscription ET à chaque connexion
    // Vérifie le téléphone, le MoMo et l'IP contre la blacklist
    public function checkBlacklist(
        ?string $phone     = null,
        ?string $phoneMomo = null,
        ?string $email     = null
    ): void {
        // Vérifier le numéro de téléphone principal
        if ($phone && Blacklist::where('phone_number', $phone)->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Ce numéro ne peut pas être utilisé.',
            ]);
        }

        // Vérifier le numéro MoMo (obligatoire pour les vendeurs)
        if ($phoneMomo && Blacklist::where('phone_momo', $phoneMomo)->exists()) {
            throw ValidationException::withMessages([
                'phone_momo' => 'Ce numéro Mobile Money ne peut pas être utilisé.',
            ]);
        }

        // Vérifier l'adresse IP
        $ip = request()->ip();
        if ($ip && Blacklist::where('ip_address', $ip)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Accès refusé depuis cette adresse.',
            ]);
        }
    }

    // ── INSCRIPTION ACHETEUR ──────────────────────────────────────────

    // Flux : page choix → formulaire acheteur → email verification → catalogue
    public function registerBuyer(array $data): User
    {
        // Vérification blacklist avant création
        $this->checkBlacklist(
            phone: $data['phone'] ?? null,
            phoneMomo: $data['phone_momo'] ?? null,
            email: $data['email'] ?? null
        );
        // Dans registerBuyer() — après la validation, avant User::create()
        $resolution = app(IdentityResolver::class)->resolveIdentity(
            email: $data['email'],
            phone: $data['phone'] ?? null,
            ip: request()->ip()
        );

        if ($resolution->isBlocked()) {
            throw ValidationException::withMessages([
                'email' => 'Impossible de créer un compte avec ces informations.',
            ]);
        }

        // Logger la tentative dans velocity_logs
        app(IdentityResolver::class)->logVelocity(
            'register',
            request()->ip(),
            null,
            ['email' => $data['email']]
        );

        // ... reste du code existant inchangé
        return User::create([
            'name'     => $data['name'],
            'phone_momo'    => $data['phone_momo'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => User::ROLE_BUYER,
            'status'   => User::STATUS_ACTIVE,
        ]);
    }

    // ── INSCRIPTION VENDEUR ───────────────────────────────────────────

    // Flux : page choix → formulaire vendeur → email verification → KYC → boutique
    // Le vendeur crée son compte ET sa boutique en même temps
    // Statut : candidate — accès bloqué jusqu'à validation KYC
    // phone_momo : OBLIGATOIRE (numéro de retrait des gains)
    // phone : OPTIONNEL (numéro de contact)
    public function registerSeller(array $data): User
    {
        // Vérification blacklist sur phone ET phone_momo
        $this->checkBlacklist(
            phone: $data['phone']     ?? null,
            phoneMomo: $data['phone_momo'],
            email: $data['email']     ?? null
        );
        // Dans registerSeller() — même chose + phone_momo
        $resolution = app(IdentityResolver::class)->resolveIdentity(
            email: $data['email'],
            phone: $data['phone'] ?? null,
            phoneMomo: $data['phone_momo'],
            ip: request()->ip()
        );

        if ($resolution->isBlocked()) {
            throw ValidationException::withMessages([
                'phone_momo' => 'Impossible de créer un compte avec ces informations.',
            ]);
        }
        return \DB::transaction(function () use ($data) {

            $user = User::create([
                'name'          => $data['name'],
                'phone'         => $data['phone']     ?? null, // optionnel
                'phone_momo'    => $data['phone_momo'],        // obligatoire
                'momo_operator' => $data['momo_operator'],     // mtn ou orange
                'email'         => $data['email'],
                'password'      => Hash::make($data['password']),
                'role'          => User::ROLE_SELLER,
                // candidate = inscrit mais KYC non encore validé
                // Les routes de publication vérifient ce statut via middleware shop.active
                'status'        => User::STATUS_CANDIDATE,
            ]);

            // Créer la boutique en même temps
            // La boutique est suspendue jusqu'à approbation KYC
            Shop::create([
                'user_id'     => $user->id,
                'phone' => $user->phone,
                'name'        => $data['shop_name'],
                'slug'        => \Str::slug($data['shop_name']) . '-' . $user->id,
                'city'        => $data['city'],
                'category'    => $data['category'],
                'description' => $data['description'] ?? null,
                'status'      => 'suspended',
            ]);

            return $user;
        });
    }
}
