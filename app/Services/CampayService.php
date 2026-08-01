<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CampayService
{
    // URL de base de l'API Campay (demo ou production selon .env)
    private string $baseUrl;

    // Token d'authentification récupéré au début de chaque session
    private ?string $token = null;

    public function __construct()
    {
        $this->baseUrl = config('services.campay.base_url');
    }

    // ── AUTHENTIFICATION ──────────────────────────────────────────────

    // Récupère le token d'accès Campay
    // Ce token est valide pour une durée limitée
    private function getToken(): string
    {
        if ($this->token) return $this->token;

        $response = Http::post("{$this->baseUrl}/token/", [
            'username' => config('services.campay.username'),
            'password' => config('services.campay.password'),
        ]);


        // Ajouter ce log temporaire
        \Log::info('Campay token response', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            Log::error('Campay auth failed', ['response' => $response->json()]);
            throw new \Exception('Impossible de se connecter à Campay. Réessayez.');
        }

        $this->token = $response->json('token');
        return $this->token;
    }
    // ── COLLECTE (encaissement depuis l'acheteur) ─────────────────────

    // Déclenche un push USSD sur le téléphone de l'acheteur
    // L'acheteur reçoit une notification et confirme le paiement
    public function collect(
        string $phone,        // numéro de l'acheteur (ex: 237655123456)
        int $amount,          // montant en FCFA
        string $reference,    // référence unique de la commande (pour idempotence)
        string $description   // description affichée à l'acheteur
    ): array {

        $response = Http::withToken($this->getToken())
            ->post("{$this->baseUrl}/collect/", [
                // Numéro au format international sans le +
                'from'              => $phone,
                'amount'            => $amount,
                'currency'          => 'XAF',   // Franc CFA
                'description'       => $description,

                // Référence unique — protection contre le double débit
                'external_reference' => $reference,

                // URL que Campay appellera après confirmation du paiement
                // 'webhook_url' => route('payment.webhook.campay'),
                'notify_url' => route('payment.webhook.campay'),
            ]);

        if (! $response->successful()) {
            Log::error('Campay collect failed', [
                'phone'     => $phone,
                'amount'    => $amount,
                'reference' => $reference,
                'response'  => $response->json(),
            ]);
            dd($response->status(), $response->json(), $response->body());
        }

        // Retourne la référence Campay et le statut initial (PENDING)
        return $response->json();
    }

    // ── VÉRIFICATION STATUT D'UNE TRANSACTION ────────────────────────

    // Vérifie le statut d'une transaction Campay par sa référence
    // Utile si le webhook n'arrive pas (fallback polling)
    public function checkStatus(string $campayReference): array
    {
        $response = Http::withToken($this->getToken())
            ->get("{$this->baseUrl}/transaction/{$campayReference}/");

        if (! $response->successful()) {
            throw new \Exception('Impossible de vérifier le statut du paiement.');
        }

        return $response->json();
    }

    // ── DÉCAISSEMENT (paiement vers le vendeur) ───────────────────────

    // Transfère les fonds vers le numéro MoMo du vendeur
    // Appelé quand le vendeur demande un retrait
    public function disburse(
        string $phone,       // numéro MoMo du vendeur
        int $amount,         // montant net à verser
        string $reference    // référence du retrait
    ): array {

        $response = Http::withToken($this->getToken())
            ->post("{$this->baseUrl}/transfer/", [
                'to'                 => $phone,
                'amount'             => $amount,
                'currency'           => 'XAF',
                'description'        => "Retrait Ali-Kamer — {$reference}",
                'external_reference' => $reference,
            ]);

        if (! $response->successful()) {
            Log::error('Campay disburse failed', [
                'phone'     => $phone,
                'amount'    => $amount,
                'reference' => $reference,
                'response'  => $response->json(),
            ]);
            throw new \Exception('Échec du virement. Contactez le support.');
        }

        return $response->json();
    }

    // ── VÉRIFICATION SIGNATURE WEBHOOK ───────────────────────────────

    // Vérifie que le webhook vient bien de Campay et pas d'un tiers
    // Utilise la signature HMAC-SHA256 dans le header de la requête
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac(
            'sha256',
            $payload,
            config('services.campay.webhook_secret')
        );

        // Comparaison sécurisée pour éviter les timing attacks
        return hash_equals($expected, $signature);
    }
}
