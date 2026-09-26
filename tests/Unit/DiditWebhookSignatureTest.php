<?php

namespace Tests\Unit;

use App\Services\DiditWebhookSignatureService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DiditWebhookSignatureTest extends TestCase
{
    private string $secret = 'test-secret-key-12345';
    private DiditWebhookSignatureService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DiditWebhookSignatureService($this->secret);
    }

    public function test_verifies_valid_raw_signature(): void
    {
        $payload = json_encode([
            'session_id' => 'didit-sess-100',
            'status'     => 'Approved',
        ]);

        $timestamp = (string) time();
        $signature = hash_hmac('sha256', $payload, $this->secret);

        $request = Request::create('/didit/webhook', 'POST', [], [], [], [
            'HTTP_X_TIMESTAMP' => $timestamp,
            'HTTP_X_SIGNATURE' => $signature,
            'CONTENT_TYPE'     => 'application/json',
        ], $payload);

        $result = $this->service->verify($request);

        $this->assertEquals('didit-sess-100', $result['body']['session_id']);
        $this->assertEquals('raw', $result['method']);
        $this->assertFalse($result['simple_fallback']);
    }

    public function test_verifies_valid_v2_signature(): void
    {
        $data = [
            'status'     => 'Approved',
            'session_id' => 'didit-sess-200',
            'details'    => ['score' => 95, 'country' => 'CMR'],
        ];
        $payload = json_encode($data);

        // Compute canonical v2 signature (sorted keys)
        $canonical = json_encode([
            'details'    => ['country' => 'CMR', 'score' => 95],
            'session_id' => 'didit-sess-200',
            'status'     => 'Approved',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $signatureV2 = hash_hmac('sha256', $canonical, $this->secret);
        $timestamp = (string) time();

        $request = Request::create('/didit/webhook', 'POST', [], [], [], [
            'HTTP_X_TIMESTAMP'    => $timestamp,
            'HTTP_X_SIGNATURE_V2' => $signatureV2,
            'CONTENT_TYPE'        => 'application/json',
        ], $payload);

        $result = $this->service->verify($request);

        $this->assertEquals('didit-sess-200', $result['body']['session_id']);
        $this->assertEquals('v2', $result['method']);
    }

    public function test_rejects_expired_timestamp(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Webhook Didit trop ancien ou trop en avance.');

        $payload = json_encode(['session_id' => 'expired']);
        $expiredTimestamp = (string) (time() - 305); // More than 300s
        $signature = hash_hmac('sha256', $payload, $this->secret);

        $request = Request::create('/didit/webhook', 'POST', [], [], [], [
            'HTTP_X_TIMESTAMP' => $expiredTimestamp,
            'HTTP_X_SIGNATURE' => $signature,
            'CONTENT_TYPE'     => 'application/json',
        ], $payload);

        $this->service->verify($request);
    }

    public function test_rejects_tampered_signature(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Signature Didit invalide.');

        $payload = json_encode(['session_id' => 'tampered']);
        $timestamp = (string) time();

        $request = Request::create('/didit/webhook', 'POST', [], [], [], [
            'HTTP_X_TIMESTAMP' => $timestamp,
            'HTTP_X_SIGNATURE' => 'invalid-tampered-signature',
            'CONTENT_TYPE'     => 'application/json',
        ], $payload);

        $this->service->verify($request);
    }
}
