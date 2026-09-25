<?php

namespace App\Services;

use App\Models\Sanction;
use App\Models\SanctionRestriction;
use App\Models\Suspension;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Moteur d'application, de suivi et de levée des sanctions Ali-Kamer.
 *
 * Gère les trois catégories fondamentales :
 *   1. RESTRICTION : limitation de fonctionnalités spécifiques.
 *   2. SUSPENSION  : coupure temporaire d'accès avec date d'expiration stricte.
 *   3. BAN         : exclusion définitive sans date d'expiration.
 *
 * Règle d'or : À l'expiration ou levée d'une sanction, le compte ne redevient
 * actif que si AUCUNE autre sanction active n'est encore en cours.
 */
class SanctionEngine
{
    public function __construct(
        protected ?TrustService $trustService = null
    ) {
    }

    /**
     * Suspend temporairement un utilisateur pour une durée déterminée en minutes.
     */
    public function suspend(
        User $user,
        int $durationMinutes,
        string $reason,
        string $reasonCode = 'admin_suspension',
        array $restrictions = [],
        ?User $createdBy = null,
        ?array $metadata = null
    ): Sanction {
        $startsAt = now();
        $expiresAt = now()->addMinutes($durationMinutes);

        return DB::transaction(function () use (
            $user, $durationMinutes, $reason, $reasonCode,
            $restrictions, $createdBy, $metadata, $startsAt, $expiresAt
        ) {
            $sanction = Sanction::create([
                'user_id'     => $user->id,
                'type'        => Sanction::TYPE_SUSPENSION,
                'status'      => Sanction::STATUS_ACTIVE,
                'reason_code' => $reasonCode,
                'reason'      => $reason,
                'severity'    => 'hard',
                'starts_at'   => $startsAt,
                'expires_at'  => $expiresAt,
                'created_by'  => $createdBy?->id,
                'metadata'    => array_merge($metadata ?? [], [
                    'duration_minutes' => $durationMinutes,
                ]),
            ]);

            Suspension::create([
                'sanction_id' => $sanction->id,
                'starts_at'   => $startsAt,
                'expires_at'  => $expiresAt,
                'status'      => Suspension::STATUS_ACTIVE,
            ]);

            foreach ($restrictions as $code) {
                SanctionRestriction::create([
                    'sanction_id'      => $sanction->id,
                    'restriction_code' => $code,
                ]);
            }

            // Statut utilisateur -> suspended
            $user->update([
                'status' => User::STATUS_SUSPENDED,
            ]);

            // Si c'est un vendeur, masquer temporairement les produits de sa boutique
            if ($user->isSeller()) {
                $user->shop?->products()->where('status', 'visible')->update(['status' => 'hidden']);
            }

            // Révoquer immédiatement les sessions web et tokens API
            $this->revokeUserAccess($user);

            // Tracer dans les événements Trust
            $this->logTrustEvent(
                $user,
                'sanction_created',
                "Suspension temporaire ({$durationMinutes} min) : {$reason}",
                $createdBy
            );

            Log::info("Utilisateur #{$user->id} suspendu jusqu'au {$expiresAt->toIso8601String()}.", [
                'sanction_id' => $sanction->id,
                'reason_code' => $reasonCode,
            ]);

            return $sanction;
        });
    }

    /**
     * Banni définitivement un utilisateur (sans date d'expiration).
     */
    public function ban(
        User $user,
        string $reason,
        string $reasonCode = 'admin_ban',
        ?User $createdBy = null,
        ?array $metadata = null
    ): Sanction {
        return DB::transaction(function () use ($user, $reason, $reasonCode, $createdBy, $metadata) {
            $sanction = Sanction::create([
                'user_id'     => $user->id,
                'type'        => Sanction::TYPE_BAN,
                'status'      => Sanction::STATUS_ACTIVE,
                'reason_code' => $reasonCode,
                'reason'      => $reason,
                'severity'    => 'permanent',
                'starts_at'   => now(),
                'expires_at'  => null,
                'created_by'  => $createdBy?->id,
                'metadata'    => $metadata,
            ]);

            $user->update([
                'status' => User::STATUS_BANNED,
            ]);

            if ($user->isSeller()) {
                $user->shop?->products()->update(['status' => 'hidden']);
                $user->shop?->update(['status' => 'banned']);
            }

            $this->revokeUserAccess($user);

            $this->logTrustEvent(
                $user,
                'fraud_confirmed',
                "Bannissement définitif : {$reason}",
                $createdBy
            );

            Log::warning("Utilisateur #{$user->id} banni définitivement.", [
                'sanction_id' => $sanction->id,
                'reason_code' => $reasonCode,
            ]);

            return $sanction;
        });
    }

    /**
     * Applique une restriction ciblée de fonctionnalités sans suspendre complètement le compte.
     */
    public function restrict(
        User $user,
        array $restrictions,
        string $reason,
        ?int $durationMinutes = null,
        string $reasonCode = 'admin_restriction',
        ?User $createdBy = null,
        ?array $metadata = null
    ): Sanction {
        $startsAt = now();
        $expiresAt = $durationMinutes ? now()->addMinutes($durationMinutes) : null;

        return DB::transaction(function () use (
            $user, $restrictions, $reason, $durationMinutes,
            $reasonCode, $createdBy, $metadata, $startsAt, $expiresAt
        ) {
            $sanction = Sanction::create([
                'user_id'     => $user->id,
                'type'        => Sanction::TYPE_RESTRICTION,
                'status'      => Sanction::STATUS_ACTIVE,
                'reason_code' => $reasonCode,
                'reason'      => $reason,
                'severity'    => $durationMinutes ? 'soft' : 'hard',
                'starts_at'   => $startsAt,
                'expires_at'  => $expiresAt,
                'created_by'  => $createdBy?->id,
                'metadata'    => array_merge($metadata ?? [], [
                    'duration_minutes' => $durationMinutes,
                    'restrictions' => $restrictions,
                ]),
            ]);

            foreach ($restrictions as $code) {
                SanctionRestriction::create([
                    'sanction_id'      => $sanction->id,
                    'restriction_code' => $code,
                ]);
            }

            // Si cannot_publish ou cannot_sell, on peut adapter le comportement
            if (in_array(SanctionRestriction::CANNOT_PUBLISH, $restrictions, true) && $user->isSeller()) {
                $user->shop?->products()->where('status', 'visible')->update(['status' => 'hidden']);
            }

            $this->logTrustEvent(
                $user,
                'sanction_created',
                "Restrictions appliquées (" . implode(', ', $restrictions) . ") : {$reason}",
                $createdBy
            );

            return $sanction;
        });
    }

    /**
     * Lève manuellement une sanction avant son expiration.
     */
    public function liftSanction(Sanction $sanction, User $admin, string $reason): void
    {
        DB::transaction(function () use ($sanction, $admin, $reason) {
            $sanction->update([
                'status'      => Sanction::STATUS_LIFTED,
                'lifted_at'   => now(),
                'lifted_by'   => $admin->id,
                'lift_reason' => $reason,
            ]);

            if ($sanction->suspension) {
                $sanction->suspension->update([
                    'status'      => Suspension::STATUS_LIFTED,
                    'lifted_at'   => now(),
                    'lifted_by'   => $admin->id,
                    'lift_reason' => $reason,
                ]);
            }

            $user = $sanction->user;

            $this->logTrustEvent(
                $user,
                'sanction_lifted',
                "Sanction #{$sanction->id} levée par l'admin : {$reason}",
                $admin
            );

            // Recalcul strict du statut du compte
            $this->recalculateUserStatus($user);

            Log::info("Sanction #{$sanction->id} levée pour l'utilisateur #{$user->id}.", [
                'lifted_by' => $admin->id,
            ]);
        });
    }

    /**
     * Parcourt toutes les sanctions échues et les marque comme expirées,
     * puis recalcule le statut des utilisateurs concernés.
     */
    public function expireDueSanctions(): int
    {
        $dueSanctions = Sanction::query()
            ->where('status', Sanction::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->with(['user', 'suspension'])
            ->get();

        $count = 0;

        foreach ($dueSanctions as $sanction) {
            DB::transaction(function () use ($sanction, &$count) {
                $sanction->update(['status' => Sanction::STATUS_EXPIRED]);

                if ($sanction->suspension) {
                    $sanction->suspension->update(['status' => Suspension::STATUS_EXPIRED]);
                }

                $user = $sanction->user;

                if ($user) {
                    $this->logTrustEvent(
                        $user,
                        'sanction_expired',
                        "Expiration automatique de la sanction #{$sanction->id} ({$sanction->reason_code})."
                    );

                    $this->recalculateUserStatus($user);
                }

                $count++;
            });
        }

        return $count;
    }

    /**
     * Règle Fondamentale : Recalcul du statut d'un compte.
     *
     * Un compte ne doit JAMAIS repasser à active si une autre sanction
     * active existe encore (ex. ban permanent ou autre suspension en cours).
     */
    public function recalculateUserStatus(User $user): void
    {
        $activeSanctions = Sanction::query()
            ->where('user_id', $user->id)
            ->where('status', Sanction::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();

        // 1. S'il reste un ban actif -> toujours banned
        $hasActiveBan = $activeSanctions->contains(fn ($s) => $s->type === Sanction::TYPE_BAN);
        if ($hasActiveBan) {
            if ($user->status !== User::STATUS_BANNED) {
                $user->update(['status' => User::STATUS_BANNED]);
            }
            return;
        }

        // 2. S'il reste une suspension active -> toujours suspended
        $hasActiveSuspension = $activeSanctions->contains(fn ($s) => $s->type === Sanction::TYPE_SUSPENSION);
        if ($hasActiveSuspension) {
            if ($user->status !== User::STATUS_SUSPENDED) {
                $user->update(['status' => User::STATUS_SUSPENDED]);
            }
            return;
        }

        // 3. Aucune sanction bloquante active
        // Si le compte était suspendu ou banni, on le réhabilite
        if (in_array($user->status, [User::STATUS_SUSPENDED, User::STATUS_BANNED], true)) {
            $user->update([
                'status' => User::STATUS_ACTIVE,
            ]);

            // Si c'est un vendeur avec boutique, réactiver les produits visibles si aucune restriction
            $restrictions = $this->getActiveRestrictions($user);
            if ($user->isSeller() && ! in_array(SanctionRestriction::CANNOT_SELL, $restrictions, true)) {
                $user->shop?->update(['status' => 'active']);
            }

            Log::info("Compte #{$user->id} réactivé après recalcul des sanctions.");
        }
    }

    /**
     * Récupère la liste consolidée des codes de restriction actifs d'un utilisateur.
     */
    public function getActiveRestrictions(User $user): array
    {
        $sanctionIds = Sanction::query()
            ->where('user_id', $user->id)
            ->where('status', Sanction::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->pluck('id');

        if ($sanctionIds->isEmpty()) {
            return [];
        }

        return SanctionRestriction::whereIn('sanction_id', $sanctionIds)
            ->pluck('restriction_code')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Révoque l'accès immédiat de l'utilisateur (sessions & tokens Sanctum).
     */
    private function revokeUserAccess(User $user): void
    {
        try {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {
            // Table session peut être fichier ou redis selon l'environnement
        }

        $user->tokens()->delete();
    }

    private function logTrustEvent(User $user, string $type, string $reason, ?User $createdBy = null): void
    {
        try {
            $trustService = $this->trustService ?? app(TrustService::class);
            $trustService->record(
                user: $user,
                type: $type,
                roleContext: $user->role ?? 'buyer',
                reason: $reason,
                createdBy: $createdBy
            );
        } catch (\Throwable $e) {
            Log::warning("Impossible d'enregistrer l'événement trust pour la sanction.", [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
