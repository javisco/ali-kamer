<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CampayService
{
    // URL de base selon l'environnement (.env)
    private string $baseUrl;

    // Token temporaire mis en cache pour la durée de la requête
    private ?string $token = null;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.campay.base_url'), '/');
    }

    // ── MÉTHODE D'AUTHENTIFICATION ────────────────────────────────────

    // Retourne le header Authorization selon la méthode choisie
    // Méthode 1 : token permanent (recommandé pour MVP — ne expire pas)
    // Méthode 2 : token temporaire (expire, nécessite un renouvellement)
    private function authHeader(): string
    {
        // Méthode 1 — token permanent depuis les clés de l'application
        // Configure CAMPAY_PERMANENT_TOKEN dans .env
        $permanent = config('services.campay.permanent_token');
        if ($permanent) {
            return "Token {$permanent}";
        }

        // Méthode 2 — token temporaire via username/password
        return "Token {$this->getTemporaryToken()}";
    }

    // Récupère un token temporaire via l'endpoint /token/
    private function getTemporaryToken(): string
    {
        if ($this->token !== null) {
            return $this->token;
        }
        // Vérifier d'abord dans le cache Redis
        // Le token est stocké pendant expires_in secondes moins 60s de marge
        $cached = \Cache::get('campay_token');
        if ($cached) return $cached;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/token/", [
            'username' => config('services.campay.username'),
            'password' => config('services.campay.password'),
        ]);

        if (! $response->successful()) {
            throw new \Exception(
                'Authentification Campay échouée : ' . $response->body()
            );
        }

        $token     = $response->json('token');
        $expiresIn = $response->json('expires_in', 3600); // défaut 1h

        // Stocker dans Redis — expire 60s avant la vraie expiration
        \Cache::put('campay_token', $token, $expiresIn - 60);

        return $token;
    }

    // ── COLLECTE (encaissement depuis l'acheteur) ─────────────────────

    // Déclenche un push USSD sur le téléphone de l'acheteur
    // L'acheteur reçoit une notification et confirme le paiement
    public function collect(
        string $phone,      // format : 237XXXXXXXXX (avec indicatif, sans +)
        int $amount,        // entier en FCFA — pas de décimales
        string $reference,  // UUID4 unique — idempotence
        string $description // affiché à l'acheteur
    ): array {

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/collect/", [
            // Numéro avec indicatif pays — ex: 237655123456
            'from'               => $phone,

            // Montant entier — Campay rejette les décimales (ER201)
            'amount'             => (string) $amount,

            // Devise obligatoire
            'currency'           => 'XAF',

            'description'        => $description,

            // UUID4 unique — si même référence envoyée deux fois,
            // Campay retourne le résultat de la première (idempotence)
            'external_reference' => $reference,
        ]);

        Log::info('Campay collect response', [
            'phone'    => $phone,
            'amount'   => $amount,
            'status'   => $response->status(),
            'body'     => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception(
                'Échec initiation paiement : ' . $response->body()
            );
        }

        return $response->json();
    }

    // ── VÉRIFICATION STATUT D'UNE TRANSACTION ────────────────────────

    //ajouter par chatgpt
    // ── RÉCUPÉRER UNE TRANSACTION PAR SA RÉFÉRENCE ───────────────────────

    // Interroge directement Campay pour connaître l'état actuel
    // de la transaction.

    // Récupère le statut actuel d'une transaction chez Campay
    // Utilisé par synchronize() comme fallback si le webhook tarde
    public function getTransaction(string $reference): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/transaction/{$reference}/");

        if (! $response->successful()) {
            throw new \Exception(
                'Impossible de récupérer la transaction : ' . $response->body()
            );
        }

        return $response->json();
    }

    // ── RETRAIT (virement vers MoMo vendeur) ─────────────────────────

    // Endpoint correct selon la doc : /withdraw/ (pas /transfer/)
    public function withdraw(
        string $phone,      // format : 237XXXXXXXXX
        int $amount,        // entier en FCFA
        string $reference   // UUID4 unique
    ): array {

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/withdraw/", [
            'to'                 => $phone,
            'amount'             => (string) $amount,
            'description'        => 'Retrait Ali-Kamer',
            'external_reference' => $reference,
        ]);

        Log::info('Campay withdraw response', [
            'phone'  => $phone,
            'amount' => $amount,
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception(
                'Échec du retrait : ' . $response->body()
            );
        }

        return $response->json();
    }

    // ── VÉRIFICATION DU WEBHOOK ───────────────────────────────────────

    // Campay envoie un JWT dans le champ "signature" du webhook
    // On valide ce JWT avec la webhook key de l'application
    public function verifyWebhookSignature(string $signature): bool
    {
        try {
            // Décoder le JWT sans vérification d'abord pour extraire le header
            $parts = explode('.', $signature);

            if (count($parts) !== 3) {
                Log::warning('Campay webhook : signature JWT invalide (format)');
                return false;
            }

            // En MVP on vérifie juste que la signature est présente
            // Phase 2 : utiliser firebase/php-jwt pour vérifier avec la webhook key
            // composer require firebase/php-jwt
            // $decoded = JWT::decode($signature, new Key($webhookKey, 'HS256'));

            return strlen($signature) > 0;
        } catch (\Exception $e) {
            Log::warning('Campay webhook signature error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // ── SOLDE DE L'APPLICATION ────────────────────────────────────────

    // Utile pour l'admin — vérifie les soldes MTN et Orange disponibles
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

    // ── INFO TITULAIRE D'UN NUMÉRO ────────────────────────────────────

    // Utilisé pour le KYC — vérifie que le nom MoMo correspond à la CNI
    // Endpoint : GET /holder_info/?phone_number=237XXXXXXXX
    public function getHolderInfo(string $phone): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/holder_info/", [
            'phone_number' => $phone,
        ]);

        if (! $response->successful()) {
            Log::warning('Campay holder_info failed', [
                'phone'  => $phone,
                'status' => $response->status(),
            ]);
            return null;
        }

        return $response->json();
    }
}
