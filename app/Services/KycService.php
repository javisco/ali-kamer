<?php

namespace App\Services;

use App\Models\AdminLog;
use App\Models\KycDocument;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Service métier KYC Ali-Kamer.
 *
 * Le service ne connaît pas la caméra ni l'interface Didit : il orchestre
 * uniquement l'état du dossier, les signaux retournés par Didit et les
 * briques de confiance déjà présentes dans Ali-Kamer.
 */
class KycService
{
    public function __construct(
        protected DiditService $didit,
        protected IdentityResolver $identityResolver,
        protected TrustService $trustService,
        protected DecisionEngine $decisionEngine,
        protected SanctionEngine $sanctionEngine,
    ) {
    }

    /**
     * Lance le parcours Hosted Session Didit.
     */
    public function startSession(
        User $user,
        string $consentVersion = 'kyc-didit-v1',
        ?string $callbackUrl = null,
        string $source = 'ali-kamer-web'
    ): array {
        if (! $user->isSeller()) {
            throw new RuntimeException('Le KYC Didit est réservé aux vendeurs.');
        }

        $session = $this->didit->createKycSession($user, $callbackUrl, $source);

        $kyc = KycDocument::updateOrCreate(
            ['user_id' => $user->id],
            [
                // Les anciens chemins locaux restent null : nous ne recevons
                // plus de CNI/selfie dans Ali-Kamer avec Hosted Sessions.
                'status' => 'pending',
                'provider' => 'didit',
                'didit_session_id' => $session['session_id'],
                'didit_session_number' => $session['session_number'] ?? null,
                'didit_session_status' => $session['status'] ?? 'Not Started',
                'didit_session_url' => $session['url'],
                'didit_workflow_id' => $session['workflow_id'] ?? config('didit.workflow_id'),
                'didit_workflow_version' => $session['workflow_version'] ?? null,
                'didit_vendor_data' => $session['vendor_data'] ?? ('user-' . $user->id),
                'didit_environment' => 'sandbox',
                'decision' => 'REVIEW',
                'decision_reason' => null,
                'rejection_reason' => null,
                'reviewer_id' => null,
                'reviewed_at' => null,
                'verification_ip' => request()->ip(),
                'consent_at' => now(),
                'consent_ip' => request()->ip(),
                'consent_user_agent' => Str::limit((string) request()->userAgent(), 500, ''),
                'consent_version' => $consentVersion,
            ]
        );

        return [
            'kyc' => $kyc,
            'url' => $session['url'],
            'session' => $session,
        ];
    }

    /**
     * Callback navigateur : on ne fait confiance qu'à l'API Didit.
     */
    public function processDiditCallback(string $sessionId, ?string $callbackStatus = null): ?KycDocument
    {
        $kyc = KycDocument::where('didit_session_id', $sessionId)->first();

        if (! $kyc) {
            return null;
        }

        // Le status de l'URL est informatif. On relit la décision serveur.
        $decision = $this->didit->getSessionDecision($sessionId);

        return $this->applyDiditResult($kyc, $decision, [
            'webhook_type' => 'callback',
            'status' => $callbackStatus,
        ], 'api');
    }

    /**
     * Entrée métier appelée par le job webhook.
     */
    public function processDiditWebhookEvent(array $payload, string $signatureMethod = 'v2'): ?KycDocument
    {
        $webhookType = $payload['webhook_type'] ?? null;

        // Pour notre destination KYC, les deux événements utiles sont ceux-ci.
        if (! in_array($webhookType, ['status.updated', 'data.updated'], true)) {
            Log::info('Webhook Didit ignoré par le module KYC.', [
                'webhook_type' => $webhookType,
            ]);
            return null;
        }

        $sessionId = $payload['session_id'] ?? null;

        if (! is_string($sessionId) || $sessionId === '') {
            return null;
        }

        $kyc = KycDocument::where('didit_session_id', $sessionId)->first();

        if (! $kyc) {
            Log::warning('Session Didit reçue mais aucun KYC local correspondant.', [
                'session_id' => $sessionId,
                'vendor_data' => $payload['vendor_data'] ?? null,
            ]);
            return null;
        }

        $status = $payload['status'] ?? $kyc->didit_session_status;

        // Didit ne fournit une décision complète que pour certains états.
        // Pour les états intermédiaires, on met simplement à jour l'état local.
        // Pour Resubmitted, Didit fournit resubmit_info mais pas de decision.
        $statusesWithDecision = ['Approved', 'Declined', 'In Review', 'Abandoned'];

        if (! in_array($status, $statusesWithDecision, true)) {
            $kyc->update([
                'didit_session_status' => $status,
                'didit_environment' => $payload['environment'] ?? $kyc->didit_environment ?? 'sandbox',
                'last_webhook_at' => now(),
                'status' => in_array($status, ['Expired', 'Kyc Expired'], true) ? 'rejected' : 'pending',
                'decision' => 'REVIEW',
                'decision_reason' => $status === 'Resubmitted'
                    ? 'Didit demande une nouvelle soumission.'
                    : 'Vérification Didit en cours.',
                'didit_result' => $this->sanitizeDecision($payload),
            ]);

            return $kyc->fresh();
        }

        // Pour un état final/revue, on relit la décision actuelle côté Didit.
        // C'est également ce qui rend le fallback X-Signature-Simple sûr.
        $decision = $this->didit->getSessionDecision($sessionId);

        return $this->applyDiditResult($kyc, $decision, $payload, $signatureMethod);
    }

    /**
     * Applique une décision Didit au dossier Ali-Kamer.
     */
    private function applyDiditResult(
        KycDocument $kyc,
        array $decisionResponse,
        array $envelope,
        string $source
    ): KycDocument {
        $user = $kyc->user;

        // Selon l'endpoint/événement, la réponse peut encapsuler la décision
        // sous `decision`. On accepte les deux formes pour rester tolérant.
        $decision = $decisionResponse['decision'] ?? $decisionResponse;
        $sessionStatus = $envelope['status']
            ?? $decisionResponse['status']
            ?? $kyc->didit_session_status;

        if (isset($decisionResponse['status'])) {
            $sessionStatus = $decisionResponse['status'];
        }

        $idVerification = $this->firstFeature($decision, 'id_verifications');
        $liveness = $this->firstFeature($decision, 'liveness_checks');
        $faceMatch = $this->firstFeature($decision, 'face_matches');
        $ipAnalysis = $this->firstFeature($decision, 'ip_analyses');
        $aml = $this->firstFeature($decision, 'aml_screenings');

        $documentStatus = $this->featureStatus($idVerification);
        $livenessStatus = $this->featureStatus($liveness);
        $faceStatus = $this->featureStatus($faceMatch);
        $ipStatus = $this->featureStatus($ipAnalysis);

        $documentNumber = $idVerification['document_number']
            ?? $idVerification['document_id']
            ?? null;

        $fullName = $this->joinName(
            $idVerification['first_name'] ?? null,
            $idVerification['last_name'] ?? null,
            $idVerification['full_name'] ?? null
        );

        $warnings = $this->collectWarnings([
            $idVerification,
            $liveness,
            $faceMatch,
            $ipAnalysis,
            $aml,
        ]);

        $update = [
            'didit_session_status' => $sessionStatus,
            'didit_environment' => $envelope['environment'] ?? $kyc->didit_environment ?? 'sandbox',
            'didit_result' => $this->sanitizeDecision($decisionResponse),
            'document_status' => $documentStatus,
            'ocr_status' => $documentStatus,
            'face_match_status' => $faceStatus,
            'liveness_status' => $livenessStatus,
            'ip_analysis_status' => $ipStatus,
            'document_number_masked' => $this->maskDocument($documentNumber),
            'full_name' => $fullName,
            'date_of_birth' => $idVerification['date_of_birth'] ?? null,
            'nationality' => $idVerification['nationality'] ?? null,
            'expiration_date' => $idVerification['expiration_date'] ?? null,
            'warnings' => $warnings,
            'name_match_score' => $this->numericValue(
                $idVerification['name_match_score']
                    ?? $idVerification['match_score']
                    ?? null
            ),
            'last_webhook_at' => $source !== 'api' ? now() : $kyc->last_webhook_at,
        ];

        // Si Didit demande une nouvelle soumission, le dossier reste traitable.
        if ($sessionStatus === 'Resubmitted') {
            $update['status'] = 'reviewing';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = 'Didit demande une nouvelle soumission.';
        } elseif ($sessionStatus === 'Approved') {
            $update['status'] = 'reviewing';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = 'Didit approuvé ; résolution d’identité Ali-Kamer en cours.';
        } elseif ($sessionStatus === 'Declined') {
            $update['status'] = 'rejected';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = $this->buildDeclineReason($warnings);
        } elseif (in_array($sessionStatus, ['In Review', 'Abandoned'], true)) {
            $update['status'] = 'reviewing';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = $sessionStatus === 'Abandoned'
                ? 'Session abandonnée : revue nécessaire.'
                : 'Didit a placé la vérification en revue.';
        } elseif (in_array($sessionStatus, ['Expired', 'Kyc Expired'], true)) {
            $update['status'] = 'rejected';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = 'La session Didit a expiré. Le vendeur peut recommencer.';
        } else {
            // Not Started / In Progress / Awaiting User
            $update['status'] = 'pending';
            $update['decision'] = 'REVIEW';
            $update['decision_reason'] = 'Vérification Didit en cours.';
        }

        $kyc->update($update);
        $kyc->refresh();

        // Une décision Approved n'active pas directement le vendeur :
        // IdentityResolver doit encore vérifier les signaux internes.
        if ($sessionStatus === 'Approved') {
            $this->applyIdentityResolution($kyc, $user, $documentNumber);
        }

        return $kyc->fresh();
    }

    /**
     * Deuxième barrière : Didit valide le dossier, puis Ali-Kamer vérifie
     * ses propres identifiants/blacklists avant activation.
     */
    private function applyIdentityResolution(
        KycDocument $kyc,
        User $user,
        ?string $documentNumber
    ): void {
        $cniHash = $documentNumber
            ? $this->hashIdentity($documentNumber)
            : null;

        $kyc->update([
            'cni_hash' => $cniHash,
            'identity_resolution_status' => 'pending',
        ]);

        $resolution = $this->identityResolver->resolveIdentity(
            email: $user->email,
            phone: $user->phone,
            phoneMomo: $user->phone_momo,
            ip: $kyc->verification_ip,
            cniHash: $cniHash,
            userId: $user->id,
        );

        $decisionResult = $this->decisionEngine->evaluate(
            user: $user,
            identityResolution: $resolution,
            kyc: $kyc,
            extraSignals: ['source' => 'didit_webhook'],
            source: 'kyc'
        );

        $kyc->update([
            'identity_resolution_status' => $resolution->decision,
            'decision' => $decisionResult->decision,
            'decision_reason' => $decisionResult->reason,
        ]);

        if ($decisionResult->isBlocked()) {
            $kyc->update([
                'status' => 'rejected',
                'rejection_reason' => $decisionResult->reason ?? 'Identifiant critique détecté.',
            ]);

            $this->checkRepeatedKycFailures($user);
            return;
        }

        if ($decisionResult->isReview() || $decisionResult->isChallenge() || $decisionResult->isRestricted()) {
            $kyc->update([
                'status' => 'reviewing',
            ]);

            return;
        }

        // Tout est cohérent : activation du vendeur.
        DB::transaction(function () use ($kyc, $user, $decisionResult) {
            $kyc->update([
                'status' => 'approved',
                'decision' => 'ALLOW',
                'decision_reason' => $decisionResult->reason,
                'rejection_reason' => null,
                'reviewed_at' => now(),
            ]);

            $user->update([
                'status' => User::STATUS_ACTIVE,
                'activated_at' => $user->activated_at ?? now(),
            ]);

            $user->shop?->update([
                'status' => 'active',
                'verified_at' => now(),
            ]);

            $this->trustService->record(
                user: $user,
                type: 'kyc_approved',
                roleContext: 'seller',
                reason: 'KYC Didit approuvé et identité Ali-Kamer cohérente.',
            );
        });
    }

    /**
     * Vérifie et applique l'escalade des sanctions en cas d'échecs KYC répétés (règle 28).
     */
    public function checkRepeatedKycFailures(User $user): void
    {
        $failureCount = ($user->abuse_count ?? 0) + 1;
        $user->update(['abuse_count' => $failureCount]);

        if ($failureCount >= 3) {
            $this->sanctionEngine->suspend(
                user: $user,
                durationMinutes: 1440, // 24h
                reason: '3 échecs ou rejets consécutifs de vérification KYC.',
                reasonCode: 'KYC_REPEATED_FAILURE'
            );
        }
    }

    // ────────────────────────────────────────────────────────────────
    // ACTIONS ADMINISTRATIVES
    // ────────────────────────────────────────────────────────────────

    public function approve(KycDocument $kyc, User $admin): void
    {
        DB::transaction(function () use ($kyc, $admin) {
            $kyc->update([
                'status' => 'approved',
                'decision' => 'ALLOW',
                'decision_reason' => 'Validation manuelle par l’administration.',
                'reviewer_id' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

            $kyc->user->update([
                'status' => User::STATUS_ACTIVE,
                'activated_at' => $kyc->user->activated_at ?? now(),
            ]);

            $kyc->user->shop?->update([
                'status' => 'active',
                'verified_at' => now(),
            ]);

            $this->trustService->record(
                user: $kyc->user,
                type: 'kyc_approved',
                roleContext: 'seller',
                reason: 'KYC validé manuellement par l’administration.',
                createdBy: $admin,
            );

            AdminLog::record(
                $admin,
                'kyc.approved',
                'user',
                $kyc->user_id,
                "KYC approuvé pour {$kyc->user->name}"
            );
        });
    }

    public function reject(KycDocument $kyc, User $admin, string $reason): void
    {
        DB::transaction(function () use ($kyc, $admin, $reason) {
            $kyc->update([
                'status' => 'rejected',
                'decision' => 'REVIEW',
                'decision_reason' => $reason,
                'rejection_reason' => $reason,
                'reviewer_id' => $admin->id,
                'reviewed_at' => now(),
            ]);

            $this->trustService->record(
                user: $kyc->user,
                type: 'kyc_issue',
                roleContext: 'seller',
                reason: 'KYC rejeté manuellement : ' . $reason,
                createdBy: $admin,
            );

            AdminLog::record(
                $admin,
                'kyc.rejected',
                'user',
                $kyc->user_id,
                "KYC rejeté : {$reason}"
            );

            $this->checkRepeatedKycFailures($kyc->user);
        });
    }

    /**
     * Ancienne entrée publique conservée pour les écrans admin existants.
     * Elle applique le moteur TrustService V2 sans supprimer l'ancien module.
     */
    public function blacklist(User $seller, User $admin, string $reason): void
    {
        DB::transaction(function () use ($seller, $admin, $reason) {
            $seller->update(['status' => User::STATUS_BANNED]);
            $seller->shop?->products()->update(['status' => 'hidden']);
            $seller->shop?->update(['status' => 'banned']);

            DB::table('sessions')->where('user_id', $seller->id)->delete();
            $seller->tokens()->delete();

            $blacklistEntry = $this->trustService->blacklist(
                user: $seller,
                reasonCode: 'admin_blacklist',
                severity: 'permanent',
                reason: $reason,
                triggerType: 'admin',
                createdBy: $admin
            );

            if ($seller->kycDocument?->cni_hash) {
                $this->trustService->storeCniIdentifier($blacklistEntry, $seller->kycDocument->cni_hash);
            }

            AdminLog::record(
                $admin,
                'seller.blacklisted',
                'user',
                $seller->id,
                "Blacklist : {$reason}"
            );
        });
    }

    // ────────────────────────────────────────────────────────────────
    // OUTILS
    // ────────────────────────────────────────────────────────────────

    private function firstFeature(array $decision, string $key): ?array
    {
        $items = $decision[$key] ?? [];

        if (! is_array($items)) {
            return null;
        }

        foreach ($items as $item) {
            if (is_array($item)) {
                return $item;
            }
        }

        return null;
    }

    private function featureStatus(?array $feature): ?string
    {
        return $feature['status']
            ?? $feature['verification_status']
            ?? null;
    }

    private function joinName(?string $firstName, ?string $lastName, ?string $fullName = null): ?string
    {
        if ($fullName) {
            return trim($fullName);
        }

        $name = trim(implode(' ', array_filter([$firstName, $lastName])));
        return $name !== '' ? $name : null;
    }

    private function numericValue(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function maskDocument(?string $documentNumber): ?string
    {
        if (! $documentNumber) {
            return null;
        }

        $clean = trim($documentNumber);
        $length = strlen($clean);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return substr($clean, 0, 2) . str_repeat('*', max(3, $length - 5)) . substr($clean, -3);
    }

    private function collectWarnings(array $features): array
    {
        $warnings = [];

        foreach ($features as $feature) {
            if (! is_array($feature)) {
                continue;
            }

            foreach (($feature['warnings'] ?? []) as $warning) {
                if (is_string($warning)) {
                    $warnings[] = $warning;
                } elseif (is_array($warning)) {
                    $warnings[] = $warning;
                }
            }
        }

        return array_values($warnings);
    }

    private function buildDeclineReason(array $warnings): string
    {
        if (! empty($warnings)) {
            return 'Didit a refusé la vérification. Des avertissements techniques sont disponibles dans le dossier admin.';
        }

        return 'Didit a refusé la vérification d’identité.';
    }

    /**
     * HMAC-SHA256 de l'identifiant documentaire normalisé.
     *
     * Cette clé est utilisable pour les recherches d'identité sans stocker
     * le numéro CNI en clair.
     */
    private function hashIdentity(string $value): string
    {
        $normalized = strtolower(trim($value));
        $secret = (string) config('services.blacklist_pepper', '');

        return hash_hmac('sha256', $normalized, $secret);
    }

    /**
     * Le résultat Didit peut contenir énormément d'informations. On retire
     * les URLs/images éventuelles avant de le stocker en base.
     */
    private function sanitizeDecision(array $value): array
    {
        $blockedKeys = [
            'image', 'image_url', 'front_image', 'back_image', 'selfie',
            'portrait_image', 'document_image', 'raw_image', 'media_url',
        ];

        $walk = function ($item) use (&$walk, $blockedKeys) {
            if (! is_array($item)) {
                return $item;
            }

            $result = [];
            foreach ($item as $key => $value) {
                $lower = strtolower((string) $key);

                if (in_array($lower, $blockedKeys, true) || str_contains($lower, 'image_url')) {
                    continue;
                }

                $result[$key] = is_array($value) ? $walk($value) : $value;
            }

            return $result;
        };

        return $walk($value);
    }
}
