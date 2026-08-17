<?php

namespace App\Services;

use App\Models\AdminLog;
use App\Models\KycDocument;
use App\Models\Blacklist;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KycService
{
    // Vendeur soumet son dossier
    public function submitDossier(User $user, array $data): KycDocument
    {
        return DB::transaction(function () use ($user, $data) {

            // Stocker les fichiers en local (storage/app/private/kyc/)
            $cni_front = $data['cni_front_url']->store("kyc/{$user->id}", 'local');
            $cni_back  = $data['cni_back_url']->store("kyc/{$user->id}", 'local');
            $selfie    = $data['selfie_url']->store("kyc/{$user->id}", 'local');
            $rccm      = isset($data['rccm_url'])
                ? $data['rccm_url']->store("kyc/{$user->id}", 'local')
                : null;

            // Créer ou remplacer le dossier KYC
            return KycDocument::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cni_front_url'    => $cni_front,
                    'cni_back_url'     => $cni_back,
                    'selfie_url'       => $selfie,
                    'rccm_url'         => $rccm,
                    'status'           => 'pending',
                    'rejection_reason' => null,
                    'reviewed_at'      => null,
                ]
            );
        });
    }

    // Admin approuve
    public function approve(KycDocument $kyc, User $admin)
    {

        DB::transaction(function () use ($kyc, $admin) {
            $kyc->update([
                'status'      => 'approved',
                'reviewer_id' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // Activer le vendeur
            $kyc->user->update(['status' => 'active']);

            // Activer la boutique
            $kyc->user->shop?->update([
                'status'      => 'active',
                'verified_at' => now(),
            ]);
        });
        // Après approve() :
       // app(NotificationService::class)->notifyKycApproved($kyc->user);
    }

    // Admin rejette
    public function reject(KycDocument $kyc, User $admin, string $reason): void
    {
        $kyc->update([
            'status'           => 'rejected',
            'reviewer_id'      => $admin->id,
            'rejection_reason' => $reason,
            'reviewed_at'      => now(),
        ]);


        // Après reject() :
      //  app(NotificationService::class)->notifyKycRejected($kyc->user, $reason);
    }

    // Générer URL temporaire pour voir un fichier en local
    public function getTemporaryUrl(string $path): string
    {
        // En local : on génère une route sécurisée
        // En production R2 : Storage::disk('r2')->temporaryUrl($path, now()->addHour())
        return route('admin.kyc.file', ['path' => encrypt($path)]);
    }


    public function blacklist(User $seller, User $admin, string $reason): void
    {
        DB::transaction(function () use ($seller, $admin, $reason) {

            // 1. Bannir le compte
            $seller->update(['status' => User::STATUS_BANNED]);

            // 2. Masquer tous les produits
            $seller->shop?->products()->update(['status' => 'hidden']);

            // 3. Suspendre la boutique
            $seller->shop?->update(['status' => 'banned']);

            // 4. Révoquer toutes les sessions actives
            // Force le vendeur à se déconnecter immédiatement
            \DB::table('sessions')
                ->where('user_id', $seller->id)
                ->delete();

            // 5. Inscrire en blacklist permanente
            Blacklist::create([
                'phone_number' => $seller->phone,
                'phone_momo'   => $seller->phone_momo,
                'ip_address'   => request()->ip(),
                'reason'       => $reason,
                'created_by'   => $admin->id,
            ]);

            // 6. Logger l'action admin
            AdminLog::record(
                $admin,
                'seller.blacklisted',
                'user',
                $seller->id,
                $reason
            );
        });
    }
}
