<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sanction;
use App\Models\User;
use App\Services\KycService;
use App\Services\SanctionEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ApiKycController extends Controller
{
    public function __construct(
        protected KycService $kycService,
        protected SanctionEngine $sanctionEngine,
    ) {
    }

    /**
     * Démarre ou réutilise une session Didit Hosted pour l'application mobile / web.
     *
     * POST /api/v1/kyc/didit/session
     */
    public function startDiditSession(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isBanned()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Votre compte est banni.',
            ], 403);
        }

        if ($user->isSuspended()) {
            $suspension = $user->getActiveSuspension();
            return response()->json([
                'status'  => 'error',
                'message' => 'Votre compte est temporairement suspendu jusqu\'au ' . ($suspension?->formattedExpiry() ?? 'inconnu'),
                'sanction' => [
                    'type'             => 'SUSPENSION',
                    'expires_at'       => $suspension?->expires_at?->toIso8601String(),
                    'formatted_expiry' => $suspension?->formattedExpiry(),
                    'remaining_seconds'=> $suspension?->remainingSeconds(),
                ],
            ], 403);
        }

        if ($user->hasRestriction('cannot_sell')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Votre compte fait l\'objet d\'une restriction de vente.',
            ], 403);
        }

        $validated = $request->validate([
            'callback_url'    => ['nullable', 'string', 'max:500'],
            'source'          => ['nullable', 'string', 'max:50'],
            'consent_version' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $result = $this->kycService->startSession(
                user: $user,
                consentVersion: $validated['consent_version'] ?? 'kyc-didit-v1',
                callbackUrl: $validated['callback_url'] ?? null,
                source: $validated['source'] ?? 'ali-kamer-mobile'
            );

            return response()->json([
                'status'      => 'success',
                'session_id'  => $result['kyc']->didit_session_id,
                'session_url' => $result['url'],
                'kyc_status'  => $result['kyc']->status,
                'decision'    => $result['kyc']->decision,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Impossible d\'initialiser la vérification d\'identité : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupère l'état KYC actuel de l'utilisateur.
     *
     * GET /api/v1/kyc/status
     */
    public function getKycStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $kyc = $user->kycDocument;

        return response()->json([
            'status' => 'success',
            'kyc'    => [
                'has_document'         => $kyc !== null,
                'status'               => $kyc?->status ?? 'none',
                'provider'             => $kyc?->provider,
                'decision'             => $kyc?->decision,
                'is_approved'          => $user->kyc_status === 'approved',
                'didit_session_status' => $kyc?->didit_session_status,
                'rejection_reason'     => $kyc?->rejection_reason,
                'reviewed_at'          => $kyc?->reviewed_at?->toIso8601String(),
                'verified_at'          => $user->kyc_verified_at?->toIso8601String(),
            ],
            'user'   => [
                'id'         => $user->id,
                'status'     => $user->status,
                'kyc_status' => $user->kyc_status,
                'role'       => $user->role,
            ],
        ]);
    }

    /**
     * Récupère l'état complet des sanctions, suspensions et restrictions du compte.
     * Utilisé par l'application Flutter et le web pour afficher bannières, décomptes et désactiver boutons.
     *
     * GET /api/v1/user/sanctions
     */
    public function getSanctionsStatus(Request $request): JsonResponse
    {
        $user = $request->user();

        // Si l'utilisateur était suspendu mais que sa suspension est expirée, on déclenche le recalcul
        if ($user->isSuspended()) {
            $suspension = $user->getActiveSuspension();
            if (! $suspension || $suspension->isDue()) {
                $this->sanctionEngine->expireDueSanctions();
                $user->refresh();
            }
        }

        $activeSuspension = $user->getActiveSuspension();
        $activeRestrictions = $user->getActiveRestrictions();

        return response()->json([
            'status'              => 'success',
            'account_status'      => $user->status,
            'is_active'           => $user->isActive(),
            'is_banned'           => $user->isBanned(),
            'is_suspended'        => $user->isSuspended(),
            'trust_score'         => $user->trust_score,
            'risk_profile'        => $user->risk_profile,
            'suspension'          => $activeSuspension ? [
                'id'               => $activeSuspension->id,
                'starts_at'        => $activeSuspension->starts_at?->toIso8601String(),
                'expires_at'       => $activeSuspension->expires_at?->toIso8601String(),
                'remaining_seconds'=> $activeSuspension->remainingSeconds(),
                'formatted_expiry' => $activeSuspension->formattedExpiry(),
                'reason'           => $activeSuspension->sanction?->reason,
                'reason_code'      => $activeSuspension->sanction?->reason_code,
            ] : null,
            'active_restrictions' => $activeRestrictions,
            'permissions'         => [
                'can_buy'          => $user->canBuy(),
                'can_sell'         => $user->canSell(),
                'can_publish'      => $user->canPublish(),
                'can_withdraw'     => $user->canWithdraw(),
                'can_message'      => $user->canMessage(),
                'can_create_order' => $user->canCreateOrder(),
            ],
        ]);
    }
}
