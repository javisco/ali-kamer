<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\DecisionEngine;
use App\Services\IdentityResolution;
use PHPUnit\Framework\TestCase;

class DecisionEngineTest extends TestCase
{
    private DecisionEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new DecisionEngine();
    }

    public function test_clean_profile_evaluates_to_allow(): void
    {
        $user = new User([
            'id'           => 1,
            'trust_score'  => 100,
            'risk_profile' => 'clean',
        ]);

        $identityResolution = new IdentityResolution(
            decision: 'allow',
            reason: null,
            matchedIdentifiers: [],
            linkedAccounts: [],
            highestConfidence: 0
        );

        $result = $this->engine->computeDecision($user, $identityResolution);

        $this->assertEquals(DecisionEngine::DECISION_ALLOW, $result->decision);
        $this->assertEquals('CLEAN_PROFILE', $result->reasonCode);
        $this->assertTrue($result->isAllowed());
    }

    public function test_same_face_alone_never_blocks_automatically_and_requires_review(): void
    {
        // Conception Finale Règle #3 : Le même visage seul ne constitue JAMAIS une preuve automatique de fraude.
        $user = new User([
            'id'           => 2,
            'trust_score'  => 85,
            'risk_profile' => 'clean',
        ]);

        // Simule un signal facial identique détecté avec un autre compte (sans autre signal critique)
        $identityResolution = new IdentityResolution(
            decision: 'review',
            reason: 'Correspondance faciale détectée avec un autre compte existant.',
            matchedIdentifiers: [
                ['type' => 'face_vector_hash', 'signal_strength' => 50, 'blacklist_id' => 1]
            ],
            linkedAccounts: [
                ['linked_user_id' => 99, 'link_type' => 'same_face', 'confidence' => 60, 'status' => 'active']
            ],
            highestConfidence: 60
        );

        $result = $this->engine->computeDecision($user, $identityResolution);

        // DOIT être REVIEW et JAMAIS BLOCK
        $this->assertEquals(DecisionEngine::DECISION_REVIEW, $result->decision);
        $this->assertFalse($result->isBlocked());
        $this->assertTrue($result->isReview());
    }

    public function test_critical_blacklisted_identifier_evaluates_to_block(): void
    {
        $user = new User([
            'id'           => 3,
            'trust_score'  => 20,
            'risk_profile' => 'clean',
        ]);

        // CNI ou MoMo blacklisté
        $identityResolution = new IdentityResolution(
            decision: 'block',
            reason: 'Identifiant critique blacklisté',
            matchedIdentifiers: [
                ['type' => 'cni_hash', 'signal_strength' => 100, 'blacklist_id' => 5]
            ],
            linkedAccounts: [],
            highestConfidence: 100
        );

        $result = $this->engine->computeDecision($user, $identityResolution);

        $this->assertEquals(DecisionEngine::DECISION_BLOCK, $result->decision);
        $this->assertEquals('CRITICAL_IDENTIFIER_BLACKLISTED', $result->reasonCode);
        $this->assertTrue($result->isBlocked());
    }

    public function test_strong_link_to_banned_account_evaluates_to_block(): void
    {
        $user = new User([
            'id'           => 4,
            'trust_score'  => 30,
            'risk_profile' => 'restricted',
        ]);

        // Lié avec confidence >= 85% à un compte banni + profil restreint (riskScore >= 80)
        $identityResolution = new IdentityResolution(
            decision: 'block',
            reason: 'Compte lié à un compte banni',
            matchedIdentifiers: [
                ['type' => 'phone_hash', 'signal_strength' => 20, 'blacklist_id' => 7]
            ],
            linkedAccounts: [
                ['linked_user_id' => 77, 'link_type' => 'biometric_and_device', 'confidence' => 90, 'status' => 'banned']
            ],
            highestConfidence: 90
        );

        $result = $this->engine->computeDecision($user, $identityResolution);

        $this->assertEquals(DecisionEngine::DECISION_BLOCK, $result->decision);
        $this->assertEquals('HIGH_RISK_IDENTITY_REUSE', $result->reasonCode);
        $this->assertTrue($result->isBlocked());
    }

    public function test_moderate_signal_evaluates_to_challenge(): void
    {
        $user = new User([
            'id'           => 5,
            'trust_score'  => 80,
            'risk_profile' => 'restricted', // gives 25 points risk score
        ]);

        $identityResolution = new IdentityResolution(
            decision: 'challenge',
            reason: 'Signal suspect : ip_hash',
            matchedIdentifiers: [],
            linkedAccounts: [],
            highestConfidence: 0
        );

        $result = $this->engine->computeDecision($user, $identityResolution);

        $this->assertEquals(DecisionEngine::DECISION_CHALLENGE, $result->decision);
        $this->assertEquals('CHALLENGE_REQUIRED', $result->reasonCode);
        $this->assertTrue($result->isChallenged());
    }
}
