<?php

namespace App\Services;

use Illuminate\Http\Request;
use RuntimeException;

/**
 * Vérifie les signatures des Webhooks Didit V3.
 *
 * Ordre recommandé par Didit :
 *   1. X-Signature-V2  -> recommandé, couvre tout le JSON
 *   2. X-Signature     -> HMAC sur les octets bruts
 *   3. X-Signature-Simple -> ancien fallback, enveloppe seulement
 *
 * Le timestamp doit toujours avoir moins de 5 minutes d'écart.
 */
class DiditWebhookSignatureService
{
    public function verify(Request $request): array
    {
        $secret = (string) config('didit.webhook_secret');
        $timestamp = (string) $request->header('x-timestamp', '');
        $rawBody = $request->getContent();

        if ($secret === '') {
            throw new RuntimeException('DIDIT_WEBHOOK_SECRET est manquante.');
        }

        if ($timestamp === '' || ! ctype_digit($timestamp)) {
            throw new RuntimeException('X-Timestamp manquant ou invalide.');
        }

        // Protection anti-rejeu : Didit demande une fenêtre maximale de 300 s.
        if (abs(time() - (int) $timestamp) > 300) {
            throw new RuntimeException('Webhook Didit trop ancien ou trop en avance.');
        }

        $body = json_decode($rawBody, true);

        if (! is_array($body)) {
            throw new RuntimeException('Payload webhook Didit invalide.');
        }

        $signatureV2 = (string) $request->header('x-signature-v2', '');
        if ($signatureV2 !== '' && $this->verifyV2($rawBody, $signatureV2, $secret)) {
            return [
                'body' => $body,
                'method' => 'v2',
                'simple_fallback' => false,
            ];
        }

        $signatureRaw = (string) $request->header('x-signature', '');
        if ($signatureRaw !== '' && $this->verifyRaw($rawBody, $signatureRaw, $secret)) {
            return [
                'body' => $body,
                'method' => 'raw',
                'simple_fallback' => false,
            ];
        }

        // Dernier recours uniquement. Cette signature ne couvre pas decision.
        $signatureSimple = (string) $request->header('x-signature-simple', '');
        if ($signatureSimple !== '' && $this->verifySimple($body, $signatureSimple, $secret)) {
            return [
                'body' => $body,
                'method' => 'simple',
                'simple_fallback' => true,
            ];
        }

        throw new RuntimeException('Signature Didit invalide.');
    }

    /**
     * Didit V2 :
     * - objets JSON conservés comme objets
     * - clés triées récursivement avec SORT_STRING
     * - tableaux conservés dans leur ordre
     * - Unicode et slash non échappés
     */
    private function verifyV2(string $rawBody, string $signature, string $secret): bool
    {
        $decoded = json_decode($rawBody, false);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        $canonical = json_encode(
            $this->canonicalize($decoded),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        if ($canonical === false) {
            return false;
        }

        $expected = hash_hmac('sha256', $canonical, $secret);

        return hash_equals($expected, $signature);
    }

    /** HMAC sur les octets exacts reçus. */
    private function verifyRaw(string $rawBody, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $rawBody, $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * Signature Simple dépréciée par Didit.
     * Elle ne couvre que l'enveloppe ; le code appelant doit donc relire la
     * décision depuis l'API Didit avant toute action métier.
     */
    private function verifySimple(array $body, string $signature, string $secret): bool
    {
        $canonical = implode(':', [
            $body['timestamp'] ?? '',
            $body['session_id'] ?? '',
            $body['status'] ?? '',
            $body['webhook_type'] ?? '',
        ]);

        $expected = hash_hmac('sha256', $canonical, $secret);

        return hash_equals($expected, $signature);
    }

    private function canonicalize(mixed $value): mixed
    {
        if ($value instanceof \stdClass) {
            $properties = get_object_vars($value);
            ksort($properties, SORT_STRING);

            $sorted = new \stdClass();

            foreach ($properties as $key => $item) {
                $sorted->{$key} = $this->canonicalize($item);
            }

            return $sorted;
        }

        if (is_array($value)) {
            return array_map(fn ($item) => $this->canonicalize($item), $value);
        }

        return $value;
    }
}
