<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Payment\WebhookController;
use App\Models\OrderShipment;

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

        if ($payment->status === 'processing') {
            throw new \Exception('Un paiement est déjà en cours.');
        }

        $payment->update(['status' => 'processing']);

        try {
            // Format Campay : 237XXXXXXXXX — sans +, sans 00
            // ltrim retire le 0 initial si présent (ex: 0655... → 655...)
            $phone = '237' . ltrim($payment->payer_phone, '0');

            $result = $this->campay->collect(
                phone: $phone,
                amount: $order->total_amount,  // entier FCFA
                reference: $payment->idempotency_key,  // UUID4
                description: "Commande Ali-Kamer {$order->reference}"
            );

            $payment->update([
                'provider_reference' => $result['reference'] ?? null,
                'provider_response'  => $result,
            ]);

            $order->update(['status' => Order::STATUS_AWAITING_PAYMENT]);
        } catch (\Exception $e) {
            $payment->update(['status' => 'pending']);
            throw $e;
        }
    }

    // ── TRAITER LE WEBHOOK CAMPAY ─────────────────────────────────────

    // Campay appelle cette méthode quand l'acheteur a confirmé le paiement
    // ou quand il a refusé / le délai a expiré
    // public function handleWebhook(array $data): void
    // {
    //     // Retrouver le paiement via la référence externe (notre idempotency_key)

    //     $payment = OrderPayment::where(
    //         'idempotency_key',
    //         $data['external_reference']
    //     )->first();


    //     if (! $payment) {
    //         Log::warning('Webhook Campay : paiement introuvable', $data);
    //         return;
    //     }

    //     $order = $payment->order;

    //     // Stocker la réponse brute pour audit
    //     $payment->update(['provider_response' => $data]);

    //     // Traiter selon le statut retourné par Campay
    //     match ($data['status']) {

    //         // Paiement confirmé par l'acheteur
    //         'SUCCESSFUL' => $this->confirmPayment($order, $payment, $data),

    //         // Paiement refusé ou délai expiré
    //         'FAILED' => $this->failPayment($order, $payment),

    //         // Statut inconnu — on loggue et on attend
    //         default => Log::info('Webhook Campay statut inconnu', $data),
    //     };
    // }
    public function handleWebhook(array $data): void
    {
        $externalRef = $data['external_reference'] ?? '';

        // Paiement produit normal
        $payment = OrderPayment::where('idempotency_key', $externalRef)->first();

        if ($payment) {
            $order = $payment->order;
            match ($data['status']) {
                'SUCCESSFUL' => $this->confirmPayment($order, $payment, $data),
                'FAILED'     => $this->failPayment($order, $payment),
                default      => null,
            };
            return;
        }

        // Paiement frais transport
        if (str_starts_with($externalRef, 'TRANSPORT-')) {
            $shipment = OrderShipment::where('transport_payment_reference', $data['reference'])
                ->first();

            if ($shipment && $data['status'] === 'SUCCESSFUL') {
                $shipment->update([
                    'transport_fee_paid'    => true,
                    'transport_fee_paid_at' => now(),
                ]);
            }
            return;
        }

        Log::warning('Webhook Campay : référence introuvable', $data);
    }
    //ajouter par chatgpt
    // ── SYNCHRONISER LE PAIEMENT AVEC CAMPAY ─────────────────────────────

    // Cette méthode est appelée par la page d'attente.
    // Elle permet de récupérer le véritable statut de la transaction
    // directement chez Campay.
    //
    // Le webhook reste prioritaire.
    // Cette méthode sert uniquement de sécurité si le webhook tarde.
    // Synchronise le statut de la commande avec Campay
    // Appelée par le polling JS toutes les 5 secondes
    // Le webhook reste prioritaire — ceci est un fallback
    public function synchronize(Order $order): string
    {
        $payment = $order->payment;

        // Si pas encore de référence Campay, rien à synchroniser
        if (! $payment->provider_reference) {
            return $order->status;
        }

        // Si déjà dans un état final, pas besoin d'interroger Campay
        if (in_array($order->status, [
            Order::STATUS_PAID,
            Order::STATUS_COMPLETED,
            Order::STATUS_AUTO_COMPLETED,
            Order::STATUS_CANCELLED,
            Order::STATUS_FAILED,
        ])) {
            return $order->status;
        }

        try {
            // Interroger Campay directement
            $transaction = $this->campay->getTransaction(
                $payment->provider_reference
            );

            $campayStatus = strtoupper($transaction['status'] ?? '');

            // Traiter selon le statut Campay
            match ($campayStatus) {
                'SUCCESSFUL' => $this->confirmPayment($order, $payment, $transaction),
                'FAILED'     => $this->failPayment($order, $payment),
                default      => null, // PENDING — on attend
            };
        } catch (\Throwable $e) {
            // Ne pas planter la page si Campay est injoignable
            Log::warning('Synchronisation Campay échouée', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
        }

        // Recharger la commande pour avoir le statut à jour
        $order->refresh();

        return $order->status;
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
        $phone    = '237' . ltrim($seller->phone_momo, '0');
        $fees     = (int) round($amount * 0.01);
        $netAmount = $amount - $fees;

        DB::transaction(function () use ($seller, $amount, $netAmount, $phone) {

            // Utilise /withdraw/ selon la doc Campay (pas /transfer/)
            $this->campay->withdraw(
                phone: $phone,
                amount: $netAmount,
                reference: \Str::uuid()->toString()
            );

            $this->wallet->requestWithdrawal($seller, $amount);
        });
    }

    // Paiement des frais transport via Campay
    // Séparé du paiement produit — idempotency_key différent
    public function initiateTransportPayment(Order $order): void
    {
        $transportFee = $order->shipment->transport_fee;

        $phone = '237' . ltrim($order->payment->payer_phone, '0');

        // Référence unique pour ce paiement transport
        // Différente de l'idempotency_key du paiement produit
        $reference = 'TRANSPORT-' . $order->id . '-' . Str::uuid();

        $result = $this->campay->collect(
            phone: $phone,
            amount: $transportFee,
            reference: $reference,
            description: "Frais transport commande {$order->reference}"
        );

        // Marquer les frais transport comme payés dès confirmation Campay
        // Le webhook Campay appellera handleWebhook()
        // On stocke la référence pour le retrouver
        $order->shipment->update([
            'transport_payment_reference' => $result['reference'] ?? null,
        ]);
    }
}
