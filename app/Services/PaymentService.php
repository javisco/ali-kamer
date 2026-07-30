<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private CampayService $campay,
        private WalletService $wallet
    ) {}

    // ── INITIER UN PAIEMENT CAMPAY ────────────────────────────────────

    // Déclenche le push USSD sur le téléphone de l'acheteur
    public function initiate(Order $order): void
    {
        $payment = $order->payment;

        // Vérifier que le paiement n'est pas déjà en cours
        // Protection contre le double clic
        if ($payment->status === 'processing') {
            throw new \Exception('Un paiement est déjà en cours pour cette commande.');
        }

        // Passer en processing — verrou de 10 minutes
        $payment->update(['status' => 'processing']);

        try {
            // Formater le numéro au format Campay (237XXXXXXXXX)
            $phone = '237' . ltrim($payment->payer_phone, '0');

            // Appeler l'API Campay
            $result = $this->campay->collect(
                phone: $phone,
                amount: $order->total_amount,

                // La clé d'idempotence est notre protection contre le double débit
                // Si Campay reçoit deux fois la même référence, il rejette la 2ème
                reference: $payment->idempotency_key,

                description: "Commande Ali-Kamer {$order->reference}"
            );

            // Stocker la référence Campay pour pouvoir vérifier le statut
            $payment->update([
                'provider_reference' => $result['reference'] ?? null,
                'provider_response'  => $result,
            ]);

            // Mettre à jour le statut de la commande
            $order->update(['status' => Order::STATUS_AWAITING_PAYMENT]);
        } catch (\Exception $e) {
            // En cas d'erreur Campay, repasser en pending pour permettre un retry
            $payment->update(['status' => 'pending']);
            throw $e;
        }
    }

    // ── TRAITER LE WEBHOOK CAMPAY ─────────────────────────────────────

    // Campay appelle cette méthode quand l'acheteur a confirmé le paiement
    // ou quand il a refusé / le délai a expiré
    public function handleWebhook(array $data): void
    {
        // Retrouver le paiement via la référence externe (notre idempotency_key)
        $payment = OrderPayment::where('idempotency_key', $data['external_reference'])
            ->first();

        if (! $payment) {
            Log::warning('Webhook Campay : paiement introuvable', $data);
            return;
        }

        $order = $payment->order;

        // Stocker la réponse brute pour audit
        $payment->update(['provider_response' => $data]);

        // Traiter selon le statut retourné par Campay
        match ($data['status']) {

            // Paiement confirmé par l'acheteur
            'SUCCESSFUL' => $this->confirmPayment($order, $payment, $data),

            // Paiement refusé ou délai expiré
            'FAILED' => $this->failPayment($order, $payment),

            // Statut inconnu — on loggue et on attend
            default => Log::info('Webhook Campay statut inconnu', $data),
        };
    }

    // ── CONFIRMER UN PAIEMENT RÉUSSI ─────────────────────────────────

    private function confirmPayment(Order $order, OrderPayment $payment, array $data): void
    {
        // Éviter le double traitement si le webhook arrive deux fois
        if ($payment->status === 'succeeded') {
            Log::info('Webhook Campay doublon ignoré', ['order' => $order->reference]);
            return;
        }

        DB::transaction(function () use ($order, $payment, $data) {

            // Confirmer le paiement
            $payment->update([
                'status'             => 'succeeded',
                'provider_reference' => $data['reference'],
                'paid_at'            => now(),
            ]);

            // Faire avancer la commande
            $order->update([
                'status'  => Order::STATUS_PAID,
                'paid_at' => now(),
            ]);

            // Séquestrer les fonds pour le vendeur
            $this->wallet->creditEscrow(
                $order->shop->user,
                $order->net_amount,
                $order
            );
        });

        Log::info('Paiement confirmé', ['order' => $order->reference]);
    }

    // ── MARQUER UN PAIEMENT ÉCHOUÉ ───────────────────────────────────

    private function failPayment(Order $order, OrderPayment $payment): void
    {
        DB::transaction(function () use ($order, $payment) {

            $payment->update(['status' => 'failed']);

            $order->update(['status' => Order::STATUS_FAILED]);

            // Libérer le stock réservé
            foreach ($order->items as $item) {
                $item->product->decrement('stock_reserved', $item->quantity);
            }
        });

        Log::info('Paiement échoué', ['order' => $order->reference]);
    }

    // ── REMBOURSER UN ACHETEUR ────────────────────────────────────────

    // Appelé lors d'un litige résolu en faveur de l'acheteur
    // ou d'une annulation après paiement
    public function refund(Order $order): void
    {
        $payment = $order->payment;

        // En MVP : le remboursement est déclenché manuellement
        // via le dashboard admin qui transfère via Campay disburse
        $phone = '237' . ltrim($order->buyer->phone_momo ?? $payment->payer_phone, '0');

        $this->campay->disburse(
            phone: $phone,
            amount: $order->total_amount,
            reference: 'REFUND-' . $order->reference
        );

        $payment->update(['status' => 'refunded']);

        // Enregistrer le remboursement dans le wallet
        $this->wallet->refund($order->buyer, $order->total_amount, $order);
    }

    // ── EXÉCUTER UN RETRAIT VENDEUR ───────────────────────────────────

    // Transfère les fonds disponibles vers le MoMo du vendeur
    public function processWithdrawal(User $seller, int $amount): void
    {
        $phone = '237' . ltrim($seller->phone_momo, '0');

        // 1% de frais Campay déduits automatiquement
        $fees      = (int) round($amount * 0.01);
        $netAmount = $amount - $fees;

        DB::transaction(function () use ($seller, $amount, $netAmount, $phone) {

            // Déclencher le virement via Campay
            $this->campay->disburse(
                phone: $phone,
                amount: $netAmount,
                reference: 'WITHDRAWAL-' . $seller->id . '-' . time()
            );

            // Enregistrer dans le wallet
            $this->wallet->requestWithdrawal($seller, $amount);
        });
    }
}
