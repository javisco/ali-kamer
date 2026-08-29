<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function dashboard(User $user)
    {
        return match($user->role) {
            User::ROLE_BUYER          => redirect()->route('buyer.home'),
            User::ROLE_SELLER         => $this->sellerRedirect($user),
            User::ROLE_SECRETARY      => redirect()->route('secretary.dashboard'),
            User::ROLE_ADMIN          => redirect()->route('admin.dashboard'),
            User::ROLE_AGENCY_MANAGER => redirect()->route('agency.dashboard'),
            default                   => abort(403, 'Rôle non reconnu.'),
        };
    }

    private function sellerRedirect(User $user)
    {
        // Boutique non créée (cas anormal avec le nouveau flux)
        if (! $user->shop) {
            return redirect()->route('seller.shop.create');
        }

        $kyc = $user->kycDocument;

        // KYC approuvé → dashboard vendeur complet
        if ($kyc?->isApproved()) {
            return redirect()->route('seller.dashboard');
        }

        // KYC en cours d'examen (pending ou reviewing)
        if ($kyc && ($kyc->isPending() || $kyc->status === 'reviewing')) {
            return redirect()->route('seller.kyc.pending');
        }

        // KYC rejeté → vendeur peut resoumettre
        if ($kyc?->isRejected()) {
            return redirect()->route('seller.kyc.rejected');
        }

        // Pas encore de dossier KYC → redirection vers le formulaire
        return redirect()->route('seller.kyc.create');
    }
}