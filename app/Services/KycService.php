<?php

namespace App\Services;

use App\Models\AdminLog;
use App\Models\Blacklist;
use App\Models\KycDocument;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Notifications\Kyc\KycApprovedNotification;
use App\Notifications\Kyc\KycRejectedNotification;

class KycService
{
    // ── SOUMISSION DU DOSSIER ─────────────────────────────────────────

    public function submitDossier(User $user, array $data): KycDocument
    {
        return DB::transaction(function () use ($user, $data) {

            // Stocker les fichiers en local (storage/app/private/kyc/)
            // En production : remplacer 'local' par 'r2' (Cloudflare R2)
            $cni_front = $data['cni_front_url']->store("kyc/{$user->id}", 'local');
            $cni_back  = $data['cni_back_url']->store("kyc/{$user->id}", 'local');
            $selfie    = $data['selfie_url']->store("kyc/{$user->id}", 'local');
            $rccm      = isset($data['rccm_url'])
                ? $data['rccm_url']->store("kyc/{$user->id}", 'local')
                : null;

            // Mettre à jour le numéro MoMo sur le user si différent
            if (isset($data['momo_number'])) {
                $user->update([
                    'phone_momo'    => $data['momo_number'],
                    'momo_operator' => $data['momo_operator'] ?? $user->momo_operator,
                ]);
            }

            // Créer ou remplacer le dossier KYC
            return KycDocument::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cni_front_url'    => $cni_front,
                    'cni_back_url'     => $cni_back,
                    'selfie_url'       => $selfie,
                    'rccm_url'         => $rccm,
                    'momo_number'      => $data['momo_number'] ?? $user->phone_momo,
                    'status'           => 'pending',
                    'rejection_reason' => null,
                    'reviewer_id'      => null,
                    'reviewed_at'      => null,
                ]
            );
        });
    }

    // ── APPROBATION ADMIN ─────────────────────────────────────────────

    public function approve(KycDocument $kyc, User $admin): void
    {
        DB::transaction(function () use ($kyc, $admin) {

            $kyc->update([
                'status'      => 'approved',
                'reviewer_id' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // Activer le compte vendeur
            //      $kyc->user->update(['status' => User::STATUS_ACTIVE]);

            // Activer la boutique
            $kyc->user->shop?->update([
                'status'      => 'active',
                'verified_at' => now(),
            ]);
            //  $kyc->user->notify(new KycApprovedNotification());
            // Dans approve() — après l'activation de la boutique
            app(TrustService::class)->record(
                user: $kyc->user,
                type: 'kyc_approved',
                roleContext: 'seller',
                reason: 'KYC validé par l\'admin',
                createdBy: $admin
            );
            AdminLog::record(
                $admin,
                'kyc.approved',
                'user',
                $kyc->user_id,
                "KYC approuvé pour {$kyc->user->name}"
            );
        });

        // Décommenter en production :
        // app(NotificationService::class)->notifyKycApproved($kyc->user);
    }

    // ── REJET ADMIN ───────────────────────────────────────────────────

    public function reject(KycDocument $kyc, User $admin, string $reason): void
    {
        DB::transaction(function () use ($kyc, $admin, $reason) {

            $kyc->update([
                'status'           => 'rejected',
                'reviewer_id'      => $admin->id,
                'rejection_reason' => $reason,
                'reviewed_at'      => now(),
            ]);
            // $kyc->user->notify(new KycRejectedNotification($reason));
            AdminLog::record(
                $admin,
                'kyc.rejected',
                'user',
                $kyc->user_id,
                "KYC rejeté : {$reason}"
            );
        });

        // Décommenter en production :
        // app(NotificationService::class)->notifyKycRejected($kyc->user, $reason);
    }

    // ── URL TEMPORAIRE POUR LES FICHIERS ─────────────────────────────

    public function getTemporaryUrl(string $path): string
    {
        // En local : route sécurisée avec path chiffré
        // En production R2 : Storage::disk('r2')->temporaryUrl($path, now()->addHour())
        return route('admin.kyc.file', ['path' => encrypt($path)]);
    }

    // ── BLACKLIST PAR L'ADMIN ─────────────────────────────────────────

    // Déclenché depuis /admin/utilisateurs — vendeur fraudeur
    // Effet immédiat : bannissement + révocation sessions + produits cachés + blacklist
    public function blacklist(User $seller, User $admin, string $reason): void
    {
        DB::transaction(function () use ($seller, $admin, $reason) {

            // 1. Bannir le compte
            $seller->update(['status' => User::STATUS_BANNED]);

            // 2. Cacher tous les produits de la boutique
            $seller->shop?->products()->update(['status' => 'hidden']);

            // 3. Suspendre la boutique
            $seller->shop?->update(['status' => 'banned']);

            // 4. Révoquer toutes les sessions actives (déconnexion immédiate)
            DB::table('sessions')
                ->where('user_id', $seller->id)
                ->delete();

            // 5. Révoquer les tokens Sanctum (API mobile)
            $seller->tokens()->delete();

            // 6. Inscrire en blacklist permanente
            // On stocke phone, phone_momo et IP pour bloquer toute réinscription
            // Blacklist::create([
            //     'phone_number' => $seller->phone,
            //     'phone_momo'   => $seller->phone_momo,
            //     'ip_address'   => request()->ip(),
            //     'reason'       => $reason,
            //     'created_by'   => $admin->id,
            // ]);
            // Dans blacklist() — remplacer Blacklist::create() par TrustService
            $blacklistEntry = app(TrustService::class)->blacklist(
                user: $seller,
                reasonCode: 'admin_blacklist',
                severity: 'permanent',
                reason: $reason,
                triggerType: 'admin',
                createdBy: $admin
            );

            // Si KYC disponible, ajouter la CNI hashée
            if ($seller->kycDocument?->cni_hash) {
                app(TrustService::class)->storeCniIdentifier(
                    $blacklistEntry,
                    $seller->kycDocument->cni_hash
                );
            }
            // 7. Logger l'action admin pour audit
            AdminLog::record(
                $admin,
                'seller.blacklisted',
                'user',
                $seller->id,
                "Blacklist : {$reason}"
            );
        });
    }
}
