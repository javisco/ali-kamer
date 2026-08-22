<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Payment\WebhookController;
use App\Models\OrderShipment;
use App\Models\WalletTransaction;
use Illuminate\Support\Str;

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
    public function handleWebhook(array $data): void
    {
        Log::info('Webhook Campay reçu', $data);

        $campayRef   = $data['reference'] ?? '';
        $externalRef = $data['external_reference'] ?? '';
        $status      = strtoupper($data['status'] ?? '');

        // ── 1. Paiement produit principal ───────────────────────────────
        $payment = OrderPayment::where('idempotency_key', $externalRef)->first();

        if ($payment) {
            match ($status) {
                'SUCCESSFUL' => $this->confirmPayment($payment->order, $payment, $data),
                'FAILED'     => $this->failPayment($payment->order, $payment),
                default      => Log::info('Webhook statut produit inconnu', $data),
            };
            return;
        }

        // ── 2. Paiement frais transport ─────────────────────────────────
        // Recherche robuste par la référence Campay OU l'external reference
        $shipment = OrderShipment::where('transport_payment_reference', $externalRef)
            ->orWhere('transport_payment_reference', $campayRef)
            ->first();

        // Fallback si la référence contient le préfixe TRANSPORT-
        if (! $shipment && str_starts_with($externalRef, 'TRANSPORT-')) {
            $parts   = explode('-', $externalRef);
            $orderId = $parts[1] ?? null;

            if ($orderId) {
                $shipment = OrderShipment::where('order_id', $orderId)->first();
            }
        }

        if ($shipment && $status === 'SUCCESSFUL') {

            if ($shipment->transport_fee_paid) {
                Log::info('Webhook transport doublon ignoré', ['shipment_id' => $shipment->id]);
                return;
            }

            DB::transaction(function () use ($shipment) {

                $shipment->update([
                    'transport_fee_paid'    => true,
                    'transport_fee_paid_at' => now(),
                ]);

                $order = $shipment->order;

                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $order->update([
                    'otp_code'       => $otp,
                    'otp_expires_at' => now()->addHours(120),
                ]);

                Log::info('Transport fee paid via webhook, OTP generated', [
                    'order' => $order->reference,
                    'otp'   => $otp,
                ]);
            });

            return;
        }

        Log::warning('Webhook Campay : aucun paiement trouvé', $data);
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
            WalletTransaction::create([
                'user_id'      => $order->buyer->id,
                'type'         => WalletTransaction::TYPE_CREDIT_BUY,
                'amount'       => $order->net_amount,
                'balance_after' => 0,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "payement de la commande {$order->reference} — virement MoMo {$order->payer_phone}",
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



    // Paiement des frais transport via Campay
    // Séparé du paiement produit — idempotency_key différent
    // Paiement des frais transport via Campay
    // L'acheteur peut choisir un numéro différent de celui de la commande
    public function initiateTransportPayment(
        Order $order,
        string $phone,
        string $operator
    ): void {
        $transportFee = $order->shipment->transport_fee;

        if ($transportFee <= 0) {
            throw new \Exception('Aucun frais de transport à payer.');
        }

        if ($order->shipment->transport_fee_paid) {
            throw new \Exception('Les frais transport ont déjà été payés.');
        }

        $formattedPhone = '237' . ltrim($phone, '0');
        $reference      = 'TRANSPORT-' . $order->id . '-' . Str::uuid();

        try {
            $result = $this->campay->collect(
                phone: $formattedPhone,
                amount: $transportFee,
                reference: $reference,
                description: "Frais transport commande {$order->reference}"
            );

            // On conserve IMPÉRATIVEMENT la référence Campay ET l'external reference
            $order->shipment->update([
                'transport_payment_reference' => $result['reference'] ?? $reference,
            ]);

            Log::info('Transport payment initiated', [
                'order'     => $order->reference,
                'phone'     => $formattedPhone,
                'amount'    => $transportFee,
                'reference' => $reference,
                'campay_ref' => $result['reference'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Transport payment failed', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }



    // Synchronise le paiement des frais de transport directement auprès de Campay
    // ── SYNCHRONISATION TRANSPORT (Correction polling) ─────────────────

    public function synchronizeTransportPayment(Order $order): bool
    {
        $shipment = $order->shipment;

        if (! $shipment || $shipment->transport_fee_paid) {
            return true;
        }

        if (! $shipment->transport_payment_reference) {
            return false;
        }

        try {
            // Tenter d'interroger la transaction
            $transaction = $this->campay->getTransaction($shipment->transport_payment_reference);
            $status      = strtoupper($transaction['status'] ?? '');

            if ($status === 'SUCCESSFUL') {
                DB::transaction(function () use ($shipment, $order) {
                    $shipment->update([
                        'transport_fee_paid'    => true,
                        'transport_fee_paid_at' => now(),
                    ]);

                    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                    $order->update([
                        'otp_code'       => $otp,
                        'otp_expires_at' => now()->addHours(120),
                    ]);

                    Log::info('Transport fee paid via sync, OTP generated', [
                        'order' => $order->reference,
                        'otp'   => $otp,
                    ]);
                });

                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('Synchronisation transport Campay échouée', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
