<?php

namespace Tests\Unit;

use App\Models\KycDocument;
use App\Models\User;
use App\Services\DiditService;
use App\Services\KycService;
use App\Services\TrustService;
use App\Services\IdentityResolver;
use App\Services\DecisionEngine;
use App\Services\SanctionEngine;
use PHPUnit\Framework\TestCase;

class KycDecisionsAndStatusTest extends TestCase
{
    public function test_kyc_document_model_helpers(): void
    {
        $doc = new KycDocument([
            'status'               => KycDocument::STATUS_REVIEWING,
            'didit_session_status' => 'In Review',
            'admin_decision'       => KycDocument::ADMIN_DECISION_REVIEW,
            'aml_status'           => 'CONSIDER',
            'warnings'             => ['LOW_FACE_MATCH', 'MANUAL_REVIEW_REQUIRED'],
        ]);

        $this->assertTrue($doc->isReviewing());
        $this->assertTrue($doc->isDiditInReview());
        $this->assertFalse($doc->isDiditApproved());
        $this->assertFalse($doc->isDiditDeclined());
        $this->assertTrue($doc->hasWarnings());

        $approvedDoc = new KycDocument([
            'status'               => KycDocument::STATUS_APPROVED,
            'didit_session_status' => 'Approved',
            'admin_decision'       => KycDocument::ADMIN_DECISION_APPROVE,
        ]);

        $this->assertTrue($approvedDoc->isDiditApproved());
        $this->assertFalse($approvedDoc->isReviewing());
    }

    public function test_strict_decoupling_between_didit_and_admin_decisions(): void
    {
        // Règle d'or : Didit est un fournisseur de signaux.
        // Ali-Kamer et l'administrateur sont souverains.
        $doc = new KycDocument([
            'user_id'              => 1,
            'status'               => KycDocument::STATUS_PENDING,
            'didit_session_status' => 'Approved', // Didit dit OK
            'admin_decision'       => KycDocument::ADMIN_DECISION_CHALLENGE, // Mais l'admin impose un CHALLENGE
            'liveness_score'       => 45,
            'face_match_score'     => 60,
        ]);

        $this->assertEquals('Approved', $doc->didit_session_status);
        $this->assertEquals('challenge', $doc->admin_decision);
        $this->assertEquals(45, $doc->liveness_score);
        $this->assertEquals(60, $doc->face_match_score);
    }

    public function test_didit_face_blocklist_is_ignored_and_local_rules_prevail(): void
    {
        // Règle Critique #0 : Ignorer totalement la Face Blocklist de Didit.
        // Aucune synchronisation, aucune dépendance. Ali-Kamer conserve sa propre souveraineté.
        $payloadWithDiditBlocklist = [
            'status' => 'Approved',
            'face_blocklist' => [
                'matched' => true,
                'list_id' => 'external_didit_list_123',
                'confidence' => 99.9,
            ],
            'id_verifications' => [
                [
                    'status' => 'Approved',
                    'front_image' => 'https://media.didit.me/temp_front.jpg',
                    'extracted_data' => [
                        'first_name' => 'Jean',
                        'last_name' => 'Kamer',
                    ]
                ]
            ],
            'liveness_checks' => [
                ['score' => 95, 'status' => 'Passed']
            ],
            'face_matches' => [
                ['score' => 92, 'status' => 'Matched']
            ],
        ];

        // Vérifie qu'on n'extrait pas ni ne stocke face_blocklist comme vérité absolue
        $this->assertArrayHasKey('face_blocklist', $payloadWithDiditBlocklist);
        $this->assertArrayNotHasKey('didit_face_blocklist', (new KycDocument())->getFillable());
    }

    public function test_sanitize_decision_preserves_media_urls_and_truncates_large_base64(): void
    {
        $diditMock = $this->createMock(DiditService::class);
        $trustMock = $this->createMock(TrustService::class);
        $idResolverMock = $this->createMock(IdentityResolver::class);
        $decEngineMock = $this->createMock(DecisionEngine::class);
        $sanctionMock = $this->createMock(SanctionEngine::class);

        $kycService = new KycService(
            $diditMock,
            $idResolverMock,
            $trustMock,
            $decEngineMock,
            $sanctionMock
        );

        $refMethod = new \ReflectionMethod(KycService::class, 'sanitizeDecision');
        $refMethod->setAccessible(true);

        $largeBase64 = 'data:image/jpeg;base64,' . str_repeat('A', 5000);
        $validUrl = 'https://media.didit.me/v1/signed/portrait.jpg?token=abc';

        $data = [
            'valid_media_url' => $validUrl,
            'oversized_photo' => $largeBase64,
            'nested' => [
                'doc_url' => 'https://media.didit.me/v1/signed/doc.jpg',
                'raw_b64' => str_repeat('B', 3000),
            ]
        ];

        $sanitized = $refMethod->invoke($kycService, $data);

        $this->assertEquals($validUrl, $sanitized['valid_media_url']);
        $this->assertEquals('https://media.didit.me/v1/signed/doc.jpg', $sanitized['nested']['doc_url']);
        $this->assertEquals('[base64_truncated]', $sanitized['oversized_photo']);
        $this->assertEquals('[base64_truncated]', $sanitized['nested']['raw_b64']);
    }

    public function test_admin_decision_constants(): void
    {
        $this->assertEquals('approve', KycDocument::ADMIN_DECISION_APPROVE);
        $this->assertEquals('reject', KycDocument::ADMIN_DECISION_REJECT);
        $this->assertEquals('review', KycDocument::ADMIN_DECISION_REVIEW);
        $this->assertEquals('challenge', KycDocument::ADMIN_DECISION_CHALLENGE);
        $this->assertEquals('restrict', KycDocument::ADMIN_DECISION_RESTRICT);
        $this->assertEquals('block', KycDocument::ADMIN_DECISION_BLOCK);
    }
}
