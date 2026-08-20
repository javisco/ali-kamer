<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CampayService
{
    private string $baseUrl;
    private ?string $token = null;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.campay.base_url'), '/');
    }

    // ── AUTHENTIFICATION ──────────────────────────────────────────────

    private function authHeader(): string
    {
        return "Token {$this->getTemporaryToken()}";
    }

    private function getTemporaryToken(): string
    {
        $cached = Cache::get('campay_token');
        if ($cached) return $cached;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/token/", [
            'username' => config('services.campay.username'),
            'password' => config('services.campay.password'),
        ]);

        Log::info('Campay token response', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Authentification Campay échouée : ' . $response->body());
        }

        $token     = $response->json('token');
        $expiresIn = $response->json('expires_in', 3600);

        Cache::put('campay_token', $token, $expiresIn - 60);

        return $token;
    }

    // ── COLLECTE (encaissement depuis l'acheteur) ─────────────────────

    public function collect(
        string $phone,
        int $amount,
        string $reference,
        string $description
    ): array {

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/collect/", [
            'from'               => $phone,
            'amount'             => (string) $amount,
            'currency'           => 'XAF',
            'description'        => $description,
            'external_reference' => $reference,
        ]);

        Log::info('Campay collect response', [
            'phone'    => $phone,
            'amount'   => $amount,
            'status'   => $response->status(),
            'body'     => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Échec initiation paiement : ' . $response->body());
        }

        return $response->json();
    }

    // ── DÉCAISSEMENT (virement vers bénéficiaire) ─────────────────────

    // Selon la doc Campay : POST /withdraw/
    // Paramètres : to, amount, description, external_reference
    public function disburse(
        string $phone,      // format : 237XXXXXXXXX
        int $amount,        // montant en FCFA
        string $reference,  // référence unique UUID
        string $description = 'Virement Ali-Kamer'
    ): array {

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/withdraw/", [
            'to'                 => $phone,
            'amount'             => (string) $amount,
            'currency'           => 'XAF',
            'description'        => $description,
            'external_reference' => $reference,
        ]);

        Log::info('Campay disburse response', [
            'phone'     => $phone,
            'amount'    => $amount,
            'reference' => $reference,
            'status'    => $response->status(),
            'body'      => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Échec du virement : ' . $response->body());
        }

        return $response->json();
    }

    // ── VÉRIFICATION STATUT TRANSACTION ──────────────────────────────

    public function checkStatus(string $campayReference): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/transaction/{$campayReference}/");

        if (! $response->successful()) {
            throw new \Exception('Vérification statut échouée : ' . $response->body());
        }

        return $response->json();
    }

    // ── SOLDE DE L'APPLICATION ────────────────────────────────────────

    // Retourne les soldes MTN et Orange disponibles
    public function getBalance(): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/balance/");

        if (! $response->successful()) {
            throw new \Exception('Impossible de récupérer le solde Campay.');
        }

        return $response->json();
    }

    // ── INFO TITULAIRE D'UN NUMÉRO (KYC) ─────────────────────────────

    // Vérifie que le nom MoMo correspond à la CNI du vendeur
    // GET /holder_info/?phone_number=237XXXXXXXX
    public function getHolderInfo(string $phone): ?array
    {
        $formatted = '237' . ltrim($phone, '0');

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/holder_info/", [
            'phone_number' => $formatted,
        ]);

        Log::info('Campay holder_info response', [
            'phone'  => $formatted,
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }

    // ── VÉRIFICATION WEBHOOK ─────────────────────────────────────────

    public function verifyWebhookSignature(string $signature): bool
    {
        if (empty($signature)) return false;

        $parts = explode('.', $signature);
        return count($parts) === 3;
    }
}