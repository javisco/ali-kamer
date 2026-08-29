<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'role'           => $this->role,
            'status'         => $this->status,
            'email_verified' => $this->hasVerifiedEmail(),
            'trust_score'    => $this->trust_score,
            'wallet_pending' => $this->wallet_pending,
            'wallet_available' => $this->wallet_available,
            'prepayment_required' => $this->prepayment_required,

            // Boutique si vendeur
            'shop' => $this->when(
                $this->role === 'seller' && $this->shop,
                fn() => [
                    'id'     => $this->shop->id,
                    'name'   => $this->shop->name,
                    'status' => $this->shop->status,
                    'score'  => $this->shop->score,
                ]
            ),

            // Statut KYC si vendeur
            'kyc_status' => $this->when(
                $this->role === 'seller',
                fn() => $this->kycDocument?->status ?? 'not_submitted'
            ),

            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}