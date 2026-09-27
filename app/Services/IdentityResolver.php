<?php

namespace App\Services;

use App\Models\AccountLink;
use App\Models\BlacklistIdentifier;
use App\Models\User;
use App\Models\VelocityLog;
use Illuminate\Support\Facades\DB;

/**
 * Résout les liens d'identité sans appliquer directement une sanction.
 */
class IdentityResolver
{
    private string $pepper;

    public function __construct()
    {
        $this->pepper = config('services.blacklist_pepper', '');
    }

    public function resolveIdentity(
        ?string $email = null,
        ?string $phone = null,
        ?string $phoneMomo = null,
        ?string $ip = null,
        ?string $deviceId = null,
        ?string $cniHash = null,
        ?int $userId = null
    ): IdentityResolution {
        $matchedIdentifiers = [];
        $linkedAccounts = [];
        $highestConfidence = 0;
        $blockReasons = [];
        $restrictReasons = [];

        $identifiersToCheck = [
            'email_hash' => $email,
            'phone_hash' => $phone,
            'phone_momo_hash' => $phoneMomo,
            'ip_hash' => $ip,
            'device_id_hash' => $deviceId,
            'cni_hash' => $cniHash,
        ];

        foreach ($identifiersToCheck as $type => $value) {
            if (! $value) {
                continue;
            }

            // cni_hash arrive déjà calculé par KycService.
            $hash = $type === 'cni_hash' ? $value : $this->hash($value);

            $match = BlacklistIdentifier::query()
                ->where('type', $type)
                ->where('value_hash', $hash)
                ->whereHas('blacklist', fn ($q) => $q
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    }))
                ->with('blacklist')
                ->first();

            if (! $match) {
                continue;
            }

            $matchedIdentifiers[] = [
                'type' => $type,
                'signal_strength' => $match->signal_strength,
                'blacklist_id' => $match->blacklist_id,
            ];

            // Une CNI/MoMo blacklistée est un signal fort. On conserve la
            // règle actuelle du projet : elle peut bloquer immédiatement.
            if (in_array($type, ['cni_hash', 'phone_momo_hash'], true)) {
                $blockReasons[] = "Identifiant blacklisté : {$type}";
            }

            if (in_array($type, ['ip_hash', 'phone_hash', 'email_hash'], true)) {
                $restrictReasons[] = "Signal suspect : {$type}";
            }
        }

        if ($ip) {
            $recentRegistrations = VelocityLog::query()
                ->where('ip_address', $ip)
                ->where('action', 'register')
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($recentRegistrations >= 5) {
                $blockReasons[] = "Velocity : {$recentRegistrations} inscriptions depuis cette IP en 1h";
            }
        }

        if ($userId) {
            $links = AccountLink::query()
                ->where(function ($q) use ($userId) {
                    $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
                })
                ->where('confidence', '>=', 70)
                ->get();

            foreach ($links as $link) {
                $other = $link->otherUser($userId);

                if (! $other) {
                    continue;
                }

                $linkedAccounts[] = [
                    'linked_user_id' => $other->id,
                    'link_type' => $link->link_type,
                    'confidence' => $link->confidence,
                    'status' => $other->status,
                ];

                $highestConfidence = max($highestConfidence, (int) $link->confidence);

                if ($other->isBanned() && $link->confidence >= 85) {
                    $restrictReasons[] = "Compte lié à un compte banni (confidence: {$link->confidence}%)";
                }
            }
        }

        if (! empty($blockReasons)) {
            return new IdentityResolution(
                decision: 'block',
                reason: implode(' | ', $blockReasons),
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts: $linkedAccounts,
                highestConfidence: $highestConfidence,
            );
        }

        if (count($restrictReasons) >= 2) {
            return new IdentityResolution(
                decision: 'restrict',
                reason: implode(' | ', $restrictReasons),
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts: $linkedAccounts,
                highestConfidence: $highestConfidence,
            );
        }

        if ($highestConfidence >= 80) {
            return new IdentityResolution(
                decision: 'restrict',
                reason: 'Compte potentiellement lié à un compte à risque.',
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts: $linkedAccounts,
                highestConfidence: $highestConfidence,
            );
        }

        if (! empty($restrictReasons)) {
            return new IdentityResolution(
                decision: 'challenge',
                reason: $restrictReasons[0],
                matchedIdentifiers: $matchedIdentifiers,
                linkedAccounts: $linkedAccounts,
                highestConfidence: $highestConfidence,
            );
        }

        return new IdentityResolution(
            decision: 'allow',
            reason: null,
            matchedIdentifiers: $matchedIdentifiers,
            linkedAccounts: $linkedAccounts,
            highestConfidence: $highestConfidence,
        );
    }

    public function logVelocity(string $action, string $ip, ?int $userId = null, ?array $metadata = null): void
    {
        VelocityLog::create([
            'user_id' => $userId,
            'ip_address' => $ip,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }

    public function createAccountLink(int $userAId, int $userBId, string $linkType, int $confidence): void
    {
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
                'confidence' => $confidence,
                'detected_at' => now(),
            ]
        );
    }

    private function hash(string $value): string
    {
        return hash('sha256', strtolower(trim($value)) . $this->pepper);
    }
}


