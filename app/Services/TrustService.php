<?php

namespace App\Services;

use App\Models\Blacklist;
use App\Models\BlacklistIdentifier;
use App\Models\PlatformSetting;
use App\Models\TrustEvent;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrustService
{
    // Pepper pour le hashage des identifiants blacklistés
    // Jamais stocké en base — uniquement dans .env
    // Empêche la construction de tables arc-en-ciel
    private string $pepper;

    public function __construct()
    {
        $this->pepper = config('app.blacklist_pepper', '');
    }

    // ── ENREGISTRER UN ÉVÉNEMENT TRUST ───────────────────────────────

    // Appelé à chaque action significative : commande livrée, litige perdu,
    // paiement confirmé, fraude détectée...
    // Calcule automatiquement la pondération selon la maturité du compte.
    public function record(
        User   $user,
        string $type,
        string $roleContext,     // buyer | seller | platform
        string $reason,
        ?string $referenceType = null,
        ?int    $referenceId   = null,
        ?User   $createdBy     = null
    ): TrustEvent {

        return DB::transaction(function () use (
            $user, $type, $roleContext, $reason,
            $referenceType, $referenceId, $createdBy
        ) {
            // Définir les points bruts et la direction selon le type
            [$direction, $pointsRaw, $isCritical] = $this->resolveEventConfig($type);

            // Calculer le poids selon la maturité du compte
            $weight = $this->calculateWeight($user, $type);

            // Appliquer la pondération (arrondi supérieur)
            $pointsApplied = (int) ceil($pointsRaw * $weight);

            $trustBefore = $user->trust_score;

            // Calculer le nouveau score (borné entre 0 et 100)
            $trustAfter = match($direction) {
                'increase' => min(100, $trustBefore + $pointsApplied),
                'decrease' => max(0, $trustBefore - $pointsApplied),
            };

            // Mettre à jour le score sur le user
            $user->update(['trust_score' => $trustAfter]);

            // Créer la trace immuable
            $event = TrustEvent::create([
                'user_id'        => $user->id,
                'role_context'   => $roleContext,
                'type'           => $type,
                'direction'      => $direction,
                'points_raw'     => $pointsRaw,
                'points_applied' => $pointsApplied,
                'trust_before'   => $trustBefore,
                'trust_after'    => $trustAfter,
                'weight'         => $weight,
                'reason'         => $reason,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'is_critical'    => $isCritical,
                'created_by'     => $createdBy?->id,
            ]);

            // Recalculer le risk_profile après chaque événement
            $this->updateRiskProfile($user->fresh());

            // Si événement critique → action immédiate indépendamment du score
            if ($isCritical) {
                $this->handleCriticalEvent($user->fresh(), $type, $reason);
            }

            return $event;
        });
    }

    // ── CONFIGURATION DES ÉVÉNEMENTS ─────────────────────────────────

    // Retourne [direction, points_bruts, is_critical]
    // Les événements critiques déclenchent une action immédiate
    private function resolveEventConfig(string $type): array
    {
        return match($type) {

            // ── Positifs ──────────────────────────────────────────────
            'transaction_success'   => ['increase', 1,   false],
            'order_completed'       => ['increase', 2,   false],
            'order_completed_fast'  => ['increase', 1,   false],
            'dispute_won'           => ['increase', 3,   false],
            'kyc_approved'          => ['increase', 10,  false],
            'milestone_10_orders'   => ['increase', 3,   false],
            'milestone_20_orders'   => ['increase', 5,   false],
            'milestone_50_orders'   => ['increase', 10,  false],
            'seller_reliable'       => ['increase', 5,   false],
            'buyer_reliable'        => ['increase', 5,   false],

            // ── Négatifs légers (pondérés par maturité) ───────────────
            'order_cancelled'       => ['decrease', 2,   false],
            'late_pickup'           => ['decrease', 2,   false],
            'spam'                  => ['decrease', 5,   false],
            'order_cancelled_repeat'=> ['decrease', 8,   false],
            'dispute_lost'          => ['decrease', 8,   false],

            // ── Négatifs graves (poids fixe — maturité ignorée) ───────
            'payment_abuse'         => ['decrease', 20,  false],
            'harassment'            => ['decrease', 20,  false],
            'multiple_accounts'     => ['decrease', 30,  false],
            'fake_information'      => ['decrease', 30,  false],
            'kyc_issue'             => ['decrease', 25,  false],
            'fraud_attempt'         => ['decrease', 40,  false],

            // ── Critiques (action immédiate) ──────────────────────────
            'fraud_confirmed'       => ['decrease', 100, true],
            'cni_blacklisted'       => ['decrease', 100, true],

            // ── Système ───────────────────────────────────────────────
            'admin_adjustment'      => ['decrease', 0,   false],
            'system_adjustment'     => ['decrease', 0,   false],

            // Type inconnu → log sans points
            default => ['decrease', 0, false],
        };
    }

    // ── PONDÉRATION PAR MATURITÉ ──────────────────────────────────────

    // Les événements légers sont amplifiés pour les nouveaux comptes
    // et atténués pour les comptes anciens.
    // Les événements graves ont toujours un poids fixe de 1.0.
    private function calculateWeight(User $user, string $type): float
    {
        // Événements graves → poids fixe, la maturité ne change rien
        $fixedWeightEvents = [
            'payment_abuse', 'harassment', 'multiple_accounts',
            'fake_information', 'kyc_issue', 'fraud_attempt',
            'fraud_confirmed', 'cni_blacklisted',
        ];

        if (in_array($type, $fixedWeightEvents)) {
            return 1.0;
        }

        // Pour les événements légers → pondérer selon l'âge du compte
        $createdAt = $user->created_at;

        if (! $createdAt) return 1.0;

        $ageInDays = $createdAt->diffInDays(now());

        return match(true) {
            $ageInDays < 7    => 2.0,   // Nouveau compte → amplifier les négatifs
            $ageInDays < 30   => 1.5,
            $ageInDays < 180  => 1.0,   // Neutre
            $ageInDays < 365  => 0.7,
            default           => 0.5,   // Compte établi → atténuer
        };
    }

    // ── RECALCULER LE RISK PROFILE ────────────────────────────────────

    // Appelé automatiquement après chaque événement trust
    // et à la connexion pour détecter les changements récents
    public function updateRiskProfile(User $user): string
    {
        $score  = $user->trust_score;
        $profile = match(true) {
            $score >= 70 => 'clean',
            $score >= 50 => 'watch',
            $score >= 30 => 'restricted',
            default      => 'blocked',
        };

        $user->update(['risk_profile' => $profile]);

        // Si passage en 'blocked' → déclencher la blacklist automatique
        if ($profile === 'blocked' && $user->risk_profile !== 'blocked') {
            $this->blacklist(
                user:        $user,
                reasonCode:  'auto_score_critical',
                severity:    'hard',
                reason:      'Score de confiance critique',
                description: "Score descendu à {$score}/100",
                triggerType: 'auto_score'
            );
        }

        return $profile;
    }

    // ── CRÉER UNE BLACKLIST ───────────────────────────────────────────

    // Crée une entrée blacklist enrichie avec tous les identifiants
    // hashés de l'utilisateur pour détecter les tentatives de contournement
    public function blacklist(
        User    $user,
        string  $reasonCode,
        string  $severity,       // soft | hard | permanent
        string  $reason,
        ?string $description  = null,
        ?Carbon $expiresAt    = null,
        string  $triggerType  = 'admin',
        ?User   $createdBy    = null
    ): Blacklist {

        return DB::transaction(function () use (
            $user, $reasonCode, $severity, $reason,
            $description, $expiresAt, $triggerType, $createdBy
        ) {
            // Créer l'entrée blacklist
            $blacklist = Blacklist::create([
                'user_id'      => $user->id,
                'status'       => 'active',
                'severity'     => $severity,
                'reason_code'  => $reasonCode,
                'reason'       => $reason,
                'description'  => $description,
                'trigger_type' => $triggerType,
                'blocked_at'   => now(),
                'expires_at'   => $expiresAt,
                'created_by'   => $createdBy?->id,
            ]);

            // Hasher et stocker tous les identifiants connus de cet utilisateur
            // Ces hashes serviront à détecter les tentatives de réinscription
            $this->storeIdentifiers($blacklist, $user);

            return $blacklist;
        });
    }

    // ── STOCKER LES IDENTIFIANTS HASHÉS ──────────────────────────────

    private function storeIdentifiers(Blacklist $blacklist, User $user): void
    {
        $identifiers = [];

        // Email — force moyenne
        if ($user->email) {
            $identifiers[] = [
                'type'            => 'email_hash',
                'value_hash'      => $this->hash($user->email),
                'value_masked'    => $this->maskEmail($user->email),
                'signal_strength' => 40,
            ];
        }

        // Téléphone — force faible au Cameroun
        if ($user->phone) {
            $identifiers[] = [
                'type'            => 'phone_hash',
                'value_hash'      => $this->hash($user->phone),
                'value_masked'    => $this->maskPhone($user->phone),
                'signal_strength' => 20,
            ];
        }

        // MoMo — force très haute (lié à une CNI légalement)
        if ($user->phone_momo) {
            $identifiers[] = [
                'type'            => 'phone_momo_hash',
                'value_hash'      => $this->hash($user->phone_momo),
                'value_masked'    => $this->maskPhone($user->phone_momo),
                'signal_strength' => 85,
            ];
        }

        // IP courante — force faible
        if ($user->last_ip) {
            $identifiers[] = [
                'type'            => 'ip_hash',
                'value_hash'      => $this->hash($user->last_ip),
                'value_masked'    => $this->maskIp($user->last_ip),
                'signal_strength' => 15,
            ];
        }

        foreach ($identifiers as $identifier) {
            BlacklistIdentifier::create(array_merge(
                ['blacklist_id' => $blacklist->id],
                $identifier
            ));
        }
    }

    // Stocke le hash de la CNI — appelé depuis KycService::blacklist()
    public function storeCniIdentifier(Blacklist $blacklist, string $cniNumber): void
    {
        BlacklistIdentifier::create([
            'blacklist_id'    => $blacklist->id,
            'type'            => 'cni_hash',
            'value_hash'      => $this->hash($cniNumber),
            'value_masked'    => 'CM***' . substr($cniNumber, -3),
            'signal_strength' => 100,
        ]);
    }

    // ── LEVER UNE BLACKLIST ───────────────────────────────────────────

    public function liftBlacklist(
        Blacklist $blacklist,
        User      $admin,
        string    $liftReason
    ): void {

        DB::transaction(function () use ($blacklist, $admin, $liftReason) {

            $blacklist->update([
                'status'     => 'lifted',
                'lifted_at'  => now(),
                'lifted_by'  => $admin->id,
                'lift_reason'=> $liftReason,
            ]);

            // Réactiver le compte
            $blacklist->user->update([
                'status'       => 'active',
                'risk_profile' => 'watch', // Sous surveillance après réhabilitation
            ]);

            // Enregistrer l'événement trust
            $this->record(
                user:        $blacklist->user,
                type:        'blacklist_lifted',
                roleContext: 'platform',
                reason:      "Blacklist levée par l'admin",
                createdBy:   $admin
            );
        });
    }

    // ── HASHAGE AVEC PEPPER ───────────────────────────────────────────

    // SHA-256(normalize(valeur) + pepper_secret)
    // Le pepper n'est jamais stocké en base → protège contre les leaks BDD
    private function hash(string $value): string
    {
        $normalized = strtolower(trim($value));
        return hash('sha256', $normalized . $this->pepper);
    }

    // ── MASQUAGE POUR L'AFFICHAGE ADMIN ──────────────────────────────

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $masked = substr($local, 0, 2) . str_repeat('*', max(3, strlen($local) - 2));
        return $masked . '@' . $domain;
    }

    private function maskPhone(string $phone): string
    {
        return substr($phone, 0, 3) . '****' . substr($phone, -2);
    }

    private function maskIp(string $ip): string
    {
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            return $parts[0] . '.' . $parts[1] . '.*.*';
        }
        return substr($ip, 0, 6) . '***';
    }

    // ── GESTION ÉVÉNEMENT CRITIQUE ────────────────────────────────────

    private function handleCriticalEvent(User $user, string $type, string $reason): void
    {
        // Révoquer toutes les sessions et tokens
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $user->tokens()->delete();

        // Bannir le compte
        $user->update(['status' => 'banned']);

        // Créer une blacklist permanente automatique
        $this->blacklist(
            user:        $user,
            reasonCode:  $type,
            severity:    'permanent',
            reason:      $reason,
            triggerType: 'auto_critical'
        );
    }
}