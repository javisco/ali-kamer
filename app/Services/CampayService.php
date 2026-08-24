<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CampayService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.campay.base_url'), '/');
    }

    // ── GROSS-UP PAYOUT ───────────────────────────────────────────────

    // Calcule le montant brut à envoyer à Campay (virement sortant)
    // pour que le destinataire reçoive EXACTEMENT $netAmount sur son téléphone.
    //
    // Formule : grossAmount = ceil((netAmount + fixedFee) / (1 - payoutRate))
    //
    // Exemple avec rate=1%, fixed=0, net=9500 :
    //   gross = ceil(9500 / 0.99) = ceil(9595.95) = 9596
    //   Campay prélève 1% de 9596 = 96 FCFA
    //   Destinataire reçoit 9596 - 96 = 9500 FCFA ✅
    //
    // La différence (gross - net) est le coût Campay supporté par la plateforme.
    public function grossUpPayout(int $netAmount): array
    {
        // Lire les taux depuis platform_settings (cachés Redis 1h)
        $rate     = PlatformSetting::getRate('campay_payout_rate');
        $fixedFee = (int) PlatformSetting::getValue('campay_fixed_fee', 0);

        // Si pas de frais → on envoie le net directement
        if ($rate <= 0) {
            return [
                'net'   => $netAmount,
                'gross' => $netAmount + $fixedFee,
                'fee'   => $fixedFee,
            ];
        }

        // Impossible mathématiquement si rate >= 1 (100%)
        if ($rate >= 1) {
            return ['net' => $netAmount, 'gross' => $netAmount, 'fee' => 0];
        }

        // Formule Gross-Up
        $gross = (int) ceil(($netAmount + $fixedFee) / (1 - $rate));
        $fee   = $gross - $netAmount;

        return [
            'net'   => $netAmount, // Ce que le destinataire reçoit
            'gross' => $gross,     // Ce qu'on envoie à Campay
            'fee'   => $fee,       // Frais Campay supportés par la plateforme
        ];
    }

    // ── GROSS-UP COLLECT ──────────────────────────────────────────────

    // Calcule le montant que l'acheteur doit payer à Campay
    // pour que la plateforme reçoive EXACTEMENT $netToReceive après déduction Campay.
    //
    // Formule : grossAmount = ceil((netToReceive + fixedFee) / (1 - collectRate))
    //
    // Exemple avec rate=2%, fixed=0, net=10200 :
    //   gross = ceil(10200 / 0.98) = ceil(10408.16) = 10409
    //   Campay prélève 2% de 10409 = 208 FCFA
    //   Plateforme reçoit 10409 - 208 = 10201 FCFA ✅
    public function grossUpCollect(int $netToReceive): array
    {
        $rate     = PlatformSetting::getRate('campay_collect_rate');
        $fixedFee = (int) PlatformSetting::getValue('campay_fixed_fee', 0);

        if ($rate <= 0) {
            return [
                'net'   => $netToReceive,
                'gross' => $netToReceive + $fixedFee,
                'fee'   => $fixedFee,
            ];
        }

        if ($rate >= 1) {
            return ['net' => $netToReceive, 'gross' => $netToReceive, 'fee' => 0];
        }

        $gross = (int) ceil(($netToReceive + $fixedFee) / (1 - $rate));
        $fee   = $gross - $netToReceive;

        return [
            'net'   => $netToReceive, // Ce que la plateforme reçoit
            'gross' => $gross,        // Ce que l'acheteur paie à Campay
            'fee'   => $fee,          // Frais Campay supportés par l'acheteur
        ];
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

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post("{$this->baseUrl}/token/", [
                'username' => config('services.campay.username'),
                'password' => config('services.campay.password'),
            ]);

        Log::info('Campay token', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Auth Campay échouée : ' . $response->body());
        }

        $token     = $response->json('token');
        $expiresIn = $response->json('expires_in', 3600);
        Cache::put('campay_token', $token, $expiresIn - 60);

        return $token;
    }

    // ── COLLECTE ──────────────────────────────────────────────────────

    // Le $amount passé est le montant Gross-Up (calculé par grossUpCollect())
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

        Log::info('Campay collect', [
            'phone'  => $phone,
            'amount' => $amount,
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Échec paiement : ' . $response->body());
        }

        return $response->json();
    }

    // ── DÉCAISSEMENT ──────────────────────────────────────────────────

    // Le $grossAmount passé est le montant Gross-Up (calculé par grossUpPayout())
    // Campay déduit ses frais de $grossAmount → le destinataire reçoit le net exact
    public function disburse(
        string $phone,
        int $grossAmount,
        string $reference,
        string $description = 'Virement Ali-Kamer'
    ): array {

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/withdraw/", [
            'to'                 => $phone,
            'amount'             => (string) $grossAmount,
            'currency'           => 'XAF',
            'description'        => $description,
            'external_reference' => $reference,
        ]);

        Log::info('Campay disburse', [
            'phone'       => $phone,
            'grossAmount' => $grossAmount,
            'status'      => $response->status(),
            'body'        => $response->json(),
        ]);

        if (! $response->successful()) {
            throw new \Exception('Échec virement : ' . $response->body());
        }

        return $response->json();
    }

    // ── AUTRES MÉTHODES (inchangées) ──────────────────────────────────

    public function checkStatus(string $campayReference): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/transaction/{$campayReference}/");

        if (! $response->successful()) {
            throw new \Exception('Vérification statut échouée.');
        }
        return $response->json();
    }

    public function getBalance(): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/balance/");

        if (! $response->successful()) {
            throw new \Exception('Solde Campay indisponible.');
        }
        return $response->json();
    }
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
    public function getHolderInfo(string $phone): ?array
    {
        $formatted = '237' . ltrim($phone, '0');

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
            'Content-Type'  => 'application/json',
        ])->get("{$this->baseUrl}/holder_info/", [
            'phone_number' => $formatted,
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function verifyWebhookSignature(string $signature): bool
    {
        if (empty($signature)) return false;
        return count(explode('.', $signature)) === 3;
    }
}
