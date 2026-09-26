<?php

namespace App\Services;

use App\Models\AdminReview;
use App\Models\DecisionLog;
use App\Models\KycDocument;
use App\Models\SanctionRestriction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Moteur d'évaluation multi-signaux et de décision Ali-Kamer.
 *
 * Évalue l'ensemble des signaux (KYC Didit, IdentityResolver, Trust, Litiges)
 * et produit une décision parmi :
 *   - ALLOW     : tout est cohérent, accès validé
 *   - CHALLENGE : signal mineur, vérification supplémentaire requise
 *   - REVIEW    : suspicion ou signaux divergents, revue humaine obligatoire
 *   - RESTRICT  : profil à risque, limitation de certaines fonctionnalités
 *   - BLOCK     : fraude confirmée ou identifiant critique blacklisté
 */
class DecisionEngine
{
    const DECISION_ALLOW     = 'ALLOW';
    const DECISION_CHALLENGE = 'CHALLENGE';
    const DECISION_REVIEW    = 'REVIEW';
    const DECISION_RESTRICT  = 'RESTRICT';
    const DECISION_BLOCK     = 'BLOCK';

    public function __construct(
        protected ?SanctionEngine $sanctionEngine = null,
        protected ?TrustService $trustService = null
    ) {
    }

    /**
     * Analyse l'ensemble des signaux disponibles et formule la décision.
     */
    public function evaluate(
        User $user,
        ?IdentityResolution $identityResolution = null,
        ?KycDocument $kyc = null,
        array $extraSignals = [],
        string $source = 'system'
    ): DecisionResult {
        $result = $this->computeDecision($user, $identityResolution, $kyc, $extraSignals);

        // 5. Consigner dans decision_logs
        $this->logDecision($user, $kyc, $result, $source);

        // 6. Déclencher les actions associées
        $this->handleDecisionSideEffects($user, $kyc, $result);

        return $result;
    }

    /**
     * Calcule purement la décision sans effet de bord en base.
     */
    public function computeDecision(
        User $user,
        ?IdentityResolution $identityResolution = null,
        ?KycDocument $kyc = null,
        array $extraSignals = []
    ): DecisionResult {
        $signals = $extraSignals;
        $riskScore = 0.0;
        $trustScore = (float) ($user->trust_score ?? 100);

        // 1. Signaux de résolution d'identité (Blacklist & Liens)
        if ($identityResolution) {
            $signals['matched_identifiers'] = $identityResolution->matchedIdentifiers;
            $signals['linked_accounts'] = $identityResolution->linkedAccounts;
            $signals['highest_confidence'] = $identityResolution->highestConfidence;

            foreach ($identityResolution->matchedIdentifiers as $match) {
                if (in_array($match['type'], ['cni_hash', 'phone_momo_hash'], true)) {
                    $riskScore += 90;
                    $signals['critical_blacklist_hit'] = true;
                } else {
                    $riskScore += 30;
                }
            }

            foreach ($identityResolution->linkedAccounts as $linked) {
                if (($linked['link_type'] ?? '') === 'same_face') {
                    $signals['same_face_detected'] = true;
                }
                if ($linked['status'] === User::STATUS_BANNED) {
                    $riskScore += ($linked['confidence'] >= 85) ? 60 : 35;
                    $signals['linked_to_banned_account'] = true;
                }
            }
        }

        if (! empty($extraSignals['same_face'])) {
            $signals['same_face_detected'] = true;
        }

        // 2. Signaux KYC Didit
        if ($kyc) {
            $signals['didit_session_status'] = $kyc->didit_session_status;
            $signals['document_status'] = $kyc->document_status;
            $signals['face_match_status'] = $kyc->face_match_status;
            $signals['liveness_status'] = $kyc->liveness_status;

            if ($kyc->warnings && ! empty($kyc->warnings)) {
                $signals['kyc_warnings'] = $kyc->warnings;
                $riskScore += min(30, count($kyc->warnings) * 10);
            }

            if ($kyc->name_match_score !== null && $kyc->name_match_score < 70) {
                $signals['low_name_match'] = $kyc->name_match_score;
                $riskScore += 25;
            }
        }

        // 3. Score de réputation et profil de risque
        if ($user->risk_profile === 'blocked') {
            $riskScore += 50;
        } elseif ($user->risk_profile === 'restricted') {
            $riskScore += 25;
        }

        // Borner le riskScore entre 0 et 100
        $riskScore = min(100.0, max(0.0, $riskScore));

        // 4. Formulation de la décision selon les règles fondamentales
        $decision = self::DECISION_ALLOW;
        $reasonCode = 'CLEAN_PROFILE';
        $reason = 'Identité cohérente et profil de risque faible.';

        // RÈGLE CRITIQUE : Identifiant critique blacklisté -> BLOCK
        if (! empty($signals['critical_blacklist_hit'])) {
            $decision = self::DECISION_BLOCK;
            $reasonCode = 'CRITICAL_IDENTIFIER_BLACKLISTED';
            $reason = 'Un identifiant critique (CNI ou Mobile Money) est présent sur la blacklist Ali-Kamer.';
        }
        // RÈGLE FRAUDE GRAVE : Lié avec forte certitude à un compte banni + récidive
        elseif (! empty($signals['linked_to_banned_account']) && $riskScore >= 80) {
            $decision = self::DECISION_BLOCK;
            $reasonCode = 'HIGH_RISK_IDENTITY_REUSE';
            $reason = 'Tentative de réutilisation d’identité fortement liée à un compte banni.';
        }
        // RÈGLE SAME_FACE SEUL OU SIGNAUX DIVERGENTS : -> REVIEW (jamais de ban automatique)
        elseif (! empty($signals['same_face_detected']) || ! empty($signals['linked_to_banned_account']) || $riskScore >= 50) {
            $decision = self::DECISION_REVIEW;
            $reasonCode = 'IDENTITY_CORROBORATION_REQUIRED';
            $reason = 'Correspondance faciale ou signaux d’identité nécessitant un examen administratif.';
        }
        // RÈGLE KYC INCOMPLET OU EN ATTENTE DE RE-SOUMISSION : -> REVIEW
        elseif ($kyc && in_array($kyc->didit_session_status, ['In Review', 'Abandoned', 'Resubmitted'], true)) {
            $decision = self::DECISION_REVIEW;
            $reasonCode = 'KYC_MANUAL_REVIEW';
            $reason = 'Le fournisseur KYC a placé la session en revue ou demande une re-soumission.';
        }
        // RÈGLE KYC DÉCLINÉ PAR LE PROVIDER
        elseif ($kyc && $kyc->didit_session_status === 'Declined') {
            $decision = self::DECISION_REVIEW;
            $reasonCode = 'KYC_DECLINED_BY_PROVIDER';
            $reason = 'Didit a refusé la vérification (document expiré ou inadéquation faciale).';
        }
        // RÈGLE SIGNAL MINEUR
        elseif ($riskScore >= 25 || ($identityResolution && $identityResolution->isChallenged())) {
            $decision = self::DECISION_CHALLENGE;
            $reasonCode = 'CHALLENGE_REQUIRED';
            $reason = 'Signal modéré détecté nécessitant une confirmation.';
        }

        return new DecisionResult(
            decision: $decision,
            riskScore: $riskScore,
            trustScore: $trustScore,
            reasonCode: $reasonCode,
            signals: $signals,
            reason: $reason
        );
    }

    /**
     * Enregistre l'empreinte immuable de la décision dans `decision_logs`.
     */
    private function logDecision(
        User $user,
        ?KycDocument $kyc,
        DecisionResult $result,
        string $source
    ): DecisionLog {
        return DecisionLog::create([
            'user_id'         => $user->id,
            'kyc_document_id' => $kyc?->id,
            'decision'        => $result->decision,
            'risk_score'      => $result->riskScore,
            'trust_score'     => $result->trustScore,
            'reason_code'     => $result->reasonCode,
            'signals'         => $result->signals,
            'source'          => $source,
            'created_at'      => now(),
        ]);
    }

    /**
     * Applique les effets de bord (création de file d'examen admin ou sanction).
     */
    private function handleDecisionSideEffects(User $user, ?KycDocument $kyc, DecisionResult $result): void
    {
        if ($result->decision === self::DECISION_REVIEW) {
            AdminReview::updateOrCreate(
                [
                    'user_id'         => $user->id,
                    'kyc_document_id' => $kyc?->id,
                    'status'          => AdminReview::STATUS_PENDING,
                ],
                [
                    'priority' => ($result->riskScore >= 60)
                        ? AdminReview::PRIORITY_HIGH
                        : AdminReview::PRIORITY_MEDIUM,
                    'notes'    => "Dossier généré automatiquement ({$result->reasonCode}) : {$result->reason}",
                ]
            );
        } elseif ($result->decision === self::DECISION_BLOCK) {
            // Si blocage critique confirmé
            $this->sanctionEngine->ban(
                user: $user,
                reason: $result->reason,
                reasonCode: $result->reasonCode
            );
        } elseif ($result->decision === self::DECISION_RESTRICT) {
            $this->sanctionEngine->restrict(
                user: $user,
                restrictions: [
                    SanctionRestriction::CANNOT_PUBLISH,
                    SanctionRestriction::CANNOT_WITHDRAW,
                ],
                reason: $result->reason,
                durationMinutes: 4320, // 3 jours par défaut
                reasonCode: $result->reasonCode
            );
        }
    }
}

class DecisionResult
{
    public function __construct(
        public readonly string $decision,
        public readonly float $riskScore,
        public readonly float $trustScore,
        public readonly string $reasonCode,
        public readonly array $signals,
        public readonly ?string $reason
    ) {
    }

    public function isAllowed(): bool { return $this->decision === DecisionEngine::DECISION_ALLOW; }
    public function isBlocked(): bool { return $this->decision === DecisionEngine::DECISION_BLOCK; }
    public function isReview(): bool { return $this->decision === DecisionEngine::DECISION_REVIEW; }
    public function isRestricted(): bool { return $this->decision === DecisionEngine::DECISION_RESTRICT; }
    public function isChallenge(): bool { return $this->decision === DecisionEngine::DECISION_CHALLENGE; }
    public function isChallenged(): bool { return $this->isChallenge(); }
}
