<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Remplace CampayService.php (à supprimer). Même contrat public
 * (mêmes noms de méthodes : grossUpPayout, grossUpCollect, collect,
 * disburse, getTransaction, getBalance, getHolderInfo) pour que tous
 * les services appelants n'aient qu'à changer le nom de la classe
 * injectée, pas leur logique.
 *
 * Différences avec Campay :
 * - Auth : un seul bearer token statique (pas de username/password
 *   ni de token temporaire à cacher).
 * - Statuts renvoyés en MINUSCULE ('completed', 'failed', 'pending',
 *   'processing') au lieu de MAJUSCULE ('SUCCESSFUL', 'FAILED').
 * - payment_method / payout_method requis explicitement à chaque appel
 *   ('mtn_mobile_money' | 'orange_money') — Campay déduisait l'opérateur
 *   du numéro, Elgiopay non.
 * - La vérification de signature webhook se fait dans
 *   ElgiopayWebhookController (header HMAC), pas ici.
 */
class ElgiopayService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.elgiopay.base_url'), '/');
        $this->token   = config('services.elgiopay.secret_token');
    }

    // ── GROSS-UP PAYOUT ───────────────────────────────────────────────
    //
    // Calcule le montant brut à envoyer à Elgiopay (virement sortant)
    // pour que le destinataire reçoive EXACTEMENT $netAmount sur son
    // téléphone. Formule INCHANGÉE par rapport à Campay :
    //
    //   grossAmount = ceil((netAmount + fixedFee) / (1 - payoutRate))
    //
    // Lit 'gateway_payout_rate' / 'gateway_fixed_fee' dans platform_settings
    // (mets gateway_payout_rate à 0 dans le panneau admin — confirmé
    // qu'Elgiopay ne facture rien sur ce trajet).
    public function grossUpPayout(int $netAmount): array
    {
        $rate     = PlatformSetting::getRate('gateway_payout_rate');
        $fixedFee = (int) PlatformSetting::getValue('gateway_fixed_fee', 0);

        if ($rate <= 0) {
            return [
                'net'   => $netAmount,
                'gross' => $netAmount + $fixedFee,
                'fee'   => $fixedFee,
            ];
        }

        if ($rate >= 1) {
            return ['net' => $netAmount, 'gross' => $netAmount, 'fee' => 0];
        }

        $gross = (int) ceil(($netAmount + $fixedFee) / (1 - $rate));
        $fee   = $gross - $netAmount;

        return ['net' => $netAmount, 'gross' => $gross, 'fee' => $fee];
    }

    // ── GROSS-UP COLLECT ──────────────────────────────────────────────
    //
    // Calcule ce que l'acheteur doit payer pour que la plateforme
    // reçoive EXACTEMENT $netToReceive après déduction des frais Elgiopay.
    // Formule INCHANGÉE. Lit 'gateway_collect_rate' (confirmé 2%).
    public function grossUpCollect(int $netToReceive): array
    {
        $rate     = PlatformSetting::getRate('gateway_collect_rate');
        $fixedFee = (int) PlatformSetting::getValue('gateway_fixed_fee', 0);

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

        return ['net' => $netToReceive, 'gross' => $gross, 'fee' => $fee];
    }

    private function client()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->timeout(15);
    }

    // ── COLLECTE (remplace CampayService::collect()) ───────────────────
    // $amount = montant Gross-Up déjà calculé par grossUpCollect().
    // $operator = 'mtn' | 'orange' — mappé vers l'enum Elgiopay.
    public function collect(
        string $phone,
        int $amount,
        ?String $name,
        string $reference,
        string $description,
        string $operator = 'mtn'
    ): array {
        $response = $this->client()->post("{$this->baseUrl}/api/v1/payments", [
            'amount'         => $amount,
            'currency'       => 'XAF',
            'payment_method' => $this->mapOperator($operator),
            'customer_name' => $name,
            'customer_phone' => $phone,
            'reference'      => $reference,
            'metadata'       => ['reference' => $reference],
        ]);

        Log::info('Elgiopay collect', [
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

    // ── DÉCAISSEMENT (remplace CampayService::disburse()) ──────────────
    // $grossAmount = montant Gross-Up calculé par grossUpPayout().
    public function disburse(
        string $phone,
        int $grossAmount,
        string $reference,
        string $description = 'Virement Ali-Kamer',
        string $operator = 'mtn',
        ?string $recipientName = null
    ): array {
        $response = $this->client()->post("{$this->baseUrl}/api/v1/payouts", [
            'amount'          => $grossAmount,
            'currency'        => 'XAF',
            'payout_method'   => $this->mapOperator($operator),
            'recipient_phone' => $phone,
            'recipient_name'  => $recipientName ?? 'Ali-Kamer',
            'description'     => $description,
            'reference'       => $reference,
        ]);

        Log::info('Elgiopay disburse', [
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

    // ── STATUT D'UNE TRANSACTION (remplace checkStatus()/getTransaction()) ──
    // $transactionId = ce qui a été stocké dans provider_reference à
    // l'initiation (= data.transaction_id renvoyé par collect()).
    public function getTransaction(string $transactionId): array
    {
        $response = $this->client()->get("{$this->baseUrl}/api/v1/payments/{$transactionId}");

        if (! $response->successful()) {
            throw new \Exception('Impossible de récupérer la transaction : ' . $response->body());
        }

        return $response->json();
    }

    // ── SOLDE ───────────────────────────────────────────────────────────
    public function getBalance(): array
    {
        $response = $this->client()->get("{$this->baseUrl}/api/v1/balance");

        if (! $response->successful()) {
            throw new \Exception('Solde Elgiopay indisponible.');
        }

        return $response->json();
    }

    // ── VALIDATION DESTINATAIRE (remplace getHolderInfo()) ──────────────
    // Retourne ['valid' => bool, 'recipient_name' => ?string] au lieu
    // du format brut Campay.
    public function getHolderInfo(string $phone): ?array
    {
        // Elgiopay attend le numéro SANS préfixe pays (doc: "digits only, no country prefix")
        $digitsOnly = ltrim($phone, '0');
        $digitsOnly = preg_replace('/^237/', '', $digitsOnly);

        $response = $this->client()->post("{$this->baseUrl}/api/v1/validate-recipient", [
            'recipient' => $digitsOnly,
        ]);

        return $response->successful() ? ($response->json('data') ?? $response->json()) : null;
    }

    private function mapOperator(string $operator): string
    {
        return match (strtolower($operator)) {
            'orange' => 'orange_money',
            default  => 'mtn_mobile_money',
        };
    }
}
