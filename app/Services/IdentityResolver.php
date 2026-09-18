<?php

namespace App\Services;

use App\Models\AccountLink;
use App\Models\BlacklistIdentifier;
use App\Models\VelocityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IdentityResolver
{
    private string $pepper;

    public function __construct()
    {
        $this->pepper = config('app.blacklist_pepper', '');
    }

    // ── RÉSOLUTION D'IDENTITÉ ─────────────────────────────────────────

    // Appelé à l'inscription, à la connexion et au KYC
    // Retourne une décision : allow | challenge | restrict | block
    // Jamais de preuve absolue — uniquement des signaux agrégés
    public function resolveIdentity(
        ?string $email     = null,
        ?string $phone     = null,
        ?string $phoneMomo = null,
        ?string $ip        = null,
        ?string $deviceId  = null,
        ?string $cniHash   = null,   // uniquement au KYC, déjà hashé
        ?int    $userId    = null    // null si pas encore de compte
    ): IdentityResolution {

        $matchedIdentifiers = [];
        $linkedAccounts     = [];
        $highestConfidence  = 0;
        $blockReasons       = [];
        $restrictReasons    = [];

        // ── 1. Vérifier chaque identifiant contre les blacklists actives ──

        $identifiersToCheck = [
            'email_hash'     => $email,
            'phone_hash'     => $phone,
            'phone_momo_hash'=> $phoneMomo,
            'ip_hash'        => $ip,
            'device_id_hash' => $deviceId,
            'cni_hash'       => $cniHash, // Déjà hashé si fourni par KycService
        ];

        foreach ($identifiersToCheck as $type => $value) {
            if (! $value) continue;

            // Pour la CNI, le hash est déjà calculé par KycService
            // Pour les autres, on hashe ici
            $hash = ($type === 'cni_hash')
                ? $value
                : $this->hash($value);

            $match = BlacklistIdentifier::where('type', $type)
                ->where('value_hash', $hash)
                ->whereHas('blacklist', fn($q) => $q->where('status', 'active'))
                ->with('blacklist')
                ->first();

            if ($match) {
                $matchedIdentifiers[] = [
                    'type'            => $type,
                    'signal_strength' => $match->signal_strength,
                    'blacklist_id'    => $match->blacklist_id,
                ];

                // Identifiants forts → BLOCK immédiat
                if (in_array($type, ['cni_hash', 'phone_momo_hash'])) {
                    $blockReasons[] = "Identifiant blacklisté : {$type}";
                }

                // Identifiants faibles → contribuent au score de risque
                if (in_array($type, ['ip_hash', 'phone_hash'])) {
                    $restrictReasons[] = "Signal suspect : {$type}";
                }
            }
        }

        // ── 2. Vérifier le velocity (flood) ──────────────────────────────

        if ($ip) {
            $recentRegistrations = VelocityLog::where('ip_address', $ip)
                ->where('action', 'register')
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($recentRegistrations >= 5) {
                $blockReasons[] = "Velocity : {$recentRegistrations} inscriptions depuis cette IP en 1h";
            }
        }

        // ── 3. Détecter les comptes liés si userId fourni ─────────────────

        if ($userId) {
            $links = AccountLink::where(function ($q) use ($userId) {
                $q->where('user_a_id', $userId)
                  ->orWhere('user_b_id', $userId);
            })
            ->where('confidence', '>=', 70)
            ->whereHas('linkedUser', fn($q) => $q->where('status', 'banned'))
            ->get();

            foreach ($links as $link) {
                $linkedAccounts[] = [
                    'link_type'  => $link->link_type,
                    'confidence' => $link->confidence,
                ];
                $highestConfidence = max($highestConfidence, $link->confidence);

                if ($link->confidence >= 85) {
                    $restrictReasons[] = "Compte lié à un compte banni (confidence: {$link->confidence}%)";
                }
            }
        }

        // ── 4. Décision finale ────────────────────────────────────────────

        // Règle 1 : identifiant fort blacklisté → BLOCK immédiat
        if (! empty($blockReasons)) {
            return new IdentityResolution(
                decision:           'block',
                reason:             implode(' | ', $blockReasons),
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts:     $linkedAccounts,
                highestConfidence:  $highestConfidence
            );
        }

        // Règle 2 : plusieurs signaux faibles → RESTRICT
        if (count($restrictReasons) >= 2) {
            return new IdentityResolution(
                decision:           'restrict',
                reason:             implode(' | ', $restrictReasons),
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts:     $linkedAccounts,
                highestConfidence:  $highestConfidence
            );
        }

        // Règle 3 : compte lié avec haute confidence → RESTRICT + admin notifié
        if ($highestConfidence >= 80) {
            return new IdentityResolution(
                decision:           'restrict',
                reason:             "Compte potentiellement lié à un compte banni",
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts:     $linkedAccounts,
                highestConfidence:  $highestConfidence
            );
        }

        // Règle 4 : signal suspect unique → CHALLENGE (email verification renforcée)
        if (! empty($restrictReasons)) {
            return new IdentityResolution(
                decision:           'challenge',
                reason:             $restrictReasons[0],
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts:     $linkedAccounts,
                highestConfidence:  $highestConfidence
            );
        }

        // Par défaut → ALLOW
        return new IdentityResolution(
            decision:           'allow',
            reason:             null,
            matchedIdentifiers: $matchedIdentifiers,
            linkedAccounts:     $linkedAccounts,
            highestConfidence:  $highestConfidence
        );
    }

    // ── LOGGER UNE ACTION DANS VELOCITY_LOGS ─────────────────────────

    public function logVelocity(
        string  $action,
        string  $ip,
        ?int    $userId   = null,
        ?array  $metadata = null
    ): void {
        VelocityLog::create([
            'user_id'    => $userId,
            'ip_address' => $ip,
            'action'     => $action,
            'metadata'   => $metadata,
        ]);
    }

    // ── CRÉER UN LIEN ENTRE COMPTES ───────────────────────────────────

    public function createAccountLink(
        int    $userAId,
        int    $userBId,
        string $linkType,
        int    $confidence
    ): void {
        // Toujours user_a_id < user_b_id pour éviter les doublons inversés
        if ($userAId > $userBId) {
            [$userAId, $userBId] = [$userBId, $userAId];
        }

        AccountLink::updateOrCreate(
            [
                'user_a_id' => $userAId,
                'user_b_id' => $userBId,
                'link_type' => $linkType,
            ],
            [
                'confidence'  => $confidence,
                'detected_at' => now(),
            ]
        );
    }

    private function hash(string $value): string
    {
        $normalized = strtolower(trim($value));
        return hash('sha256', $normalized . $this->pepper);
    }
}

// ── Value Object retourné par resolveIdentity() ────────────────────────

class IdentityResolution
{
    public function __construct(
        public readonly string  $decision,           // allow | challenge | restrict | block
        public readonly ?string $reason,
        public readonly array   $matchedIdentifiers, // identifiants qui ont matché
        public readonly array   $linkedAccounts,     // comptes liés détectés
        public readonly int     $highestConfidence   // confidence max des liens
    ) {}

    public function isAllowed():    bool { return $this->decision === 'allow'; }
    public function isBlocked():    bool { return $this->decision === 'block'; }
    public function isRestricted(): bool { return $this->decision === 'restrict'; }
    public function isChallenged(): bool { return $this->decision === 'challenge'; }
}