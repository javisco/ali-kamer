<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderShipment;
use App\Models\PlatformSetting;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private ElgiopayService $elgiopay,
        private WalletService $wallet
    ) {}

    // ── INITIER UN PAIEMENT ──────────────────────────────────────────
    // Déclenche le push mobile money sur le téléphone de l'acheteur.
    // Logique INCHANGÉE — seul le service appelé change.
    public function initiate(Order $order): void
    {
        $payment = $order->payment;

        if ($payment->status === 'processing') {
            throw new \Exception('Un paiement est déjà en cours.');
        }

        $payment->update(['status' => 'processing']);

        try {
            $phone = '237' . ltrim($payment->payer_phone, '0');

            $result = $this->elgiopay->collect(
                phone: $phone,
                amount: $order->total_amount,
                name:$order->buyer->name,          // déjà le montant Gross-Up
                reference: $payment->idempotency_key,
                description: "Commande Ali-Kamer {$order->reference}",
                operator: $payment->payer_operator
            );

            // provider_reference = transaction_id Elgiopay.
            // C'est CE champ qui sert à matcher le webhook (voir handleWebhook()) —
            // avec Campay c'était l'idempotency_key (external_reference), ici c'est
            // directement l'identifiant renvoyé par Elgiopay à l'initiation.
            $payment->update([
                'provider_reference' => $result['transaction_id'] ?? null,
                'provider_response'  => $result,
            ]);

            $order->update(['status' => Order::STATUS_AWAITING_PAYMENT]);
        } catch (\Exception $e) {
            $payment->update(['status' => 'pending']);
            throw $e;
        }
    }

    // ── TRAITER LE WEBHOOK ELGIOPAY ────────────────────────────────────
    // Appelé par ElgiopayWebhookController APRÈS vérification de la
    // signature HMAC et déduplication par event_id (voir ce controller) —
    // donc ici on ne reçoit que des events déjà authentifiés et non-doublons.
    //
    // $eventType : 'payment.completed' | 'payment.failed' | ...
    // $data      : le contenu de l'objet "data" de l'envelope webhook
    //              (transaction_id, status, amount, metadata, ...)
    public function handleWebhook(string $eventType, array $data): void
    {
        Log::info('Webhook Elgiopay reçu', ['event' => $eventType, 'data' => $data]);

        $transactionId = $data['transaction_id'] ?? null;

        if (! $transactionId) {
            Log::warning('Webhook Elgiopay sans transaction_id', $data);
            return;
        }

        // ── 1. Paiement produit principal ───────────────────────────
        $payment = OrderPayment::where('provider_reference', $transactionId)->first();

        if ($payment) {
            match ($eventType) {
                'payment.completed' => $this->confirmPayment($payment->order, $payment, $data),
                'payment.failed'    => $this->failPayment($payment->order, $payment),
                default             => Log::info('Webhook event produit non géré', ['event' => $eventType]),
            };
            return;
        }

        // ── 2. Paiement frais transport ───────────────────────────────
        $shipment = OrderShipment::where('transport_payment_reference', $transactionId)->first();

        if ($shipment && $eventType === 'payment.completed') {

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

                $seller = $shipment->order->shop->user;

                // Le vendeur reçoit exactement le montant NET (transport_fee).
                // Le surplus payé par l'acheteur (gross - net) a couvert les
                // frais gateway — il n'est jamais crédité au vendeur.
                $seller->increment('wallet_available', $shipment->transport_fee);

                WalletTransaction::create([
                    'user_id'      => $seller->id,
                    'type'         => WalletTransaction::TYPE_CREDIT_TRANSPORT_FEE,
                    'amount'       => $shipment->transport_fee,
                    'balance_after' => $seller->wallet_available,
                    'ref_type'     => 'shipment',
                    'ref_id'       => $shipment->id,
                    'note'         => "payement des frais de transport {$shipment->order->reference} — virement MoMo {$shipment->order->payer_phone}",
                ]);

                Log::info('Transport fee paid via webhook, OTP generated', [
                    'order' => $order->reference,
                    'otp'   => $otp,
                ]);
            });

            return;
        }

        if ($shipment && $eventType === 'payment.failed') {
            Log::info('Webhook transport échoué', ['shipment_id' => $shipment->id]);
            return;
        }

        Log::warning('Webhook Elgiopay : aucun paiement trouvé', $data);
    }

    // ── SYNCHRONISER LE PAIEMENT AVEC ELGIOPAY ────────────────────────
    // Fallback si le webhook tarde — le webhook reste prioritaire.
    // Appelée par PaymentController::status() (page d'attente, polling JS).
    public function synchronize(Order $order): string
    {
        $payment = $order->payment;

        if (! $payment->provider_reference) {
            return $order->status;
        }

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
            $transaction = $this->elgiopay->getTransaction($payment->provider_reference);

            // Statuts Elgiopay en MINUSCULE : completed | failed | pending | processing
            $status = strtolower($transaction['status'] ?? '');

            match ($status) {
                'completed' => $this->confirmPayment($order, $payment, $transaction),
                'failed'    => $this->failPayment($order, $payment),
                default     => null, // pending / processing — on attend
            };
        } catch (\Throwable $e) {
            Log::warning('Synchronisation Elgiopay échouée', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
        }

        $order->refresh();

        return $order->status;
    }

    // ── CONFIRMER UN PAIEMENT RÉUSSI ─────────────────────────────────
    // Logique INCHANGÉE (séquestre, wallet transaction).
    private function confirmPayment(Order $order, OrderPayment $payment, array $data): void
    {
        if ($payment->status === 'succeeded') {
            Log::info('Webhook Elgiopay doublon ignoré', ['order' => $order->reference]);
            return;
        }

        DB::transaction(function () use ($order, $payment, $data) {

            $payment->update([
                'status'             => 'succeeded',
                'provider_reference' => $data['transaction_id'] ?? $payment->provider_reference,
                'paid_at'            => now(),
            ]);

            $order->update([
                'status'  => Order::STATUS_PAID,
                'paid_at' => now(),
            ]);

            WalletTransaction::create([
                'user_id'      => $order->buyer->id,
                'type'         => WalletTransaction::TYPE_CREDIT_BUY,
                'amount'       => $order->total_amount,
                'balance_after' => 0,
                'ref_type'     => 'order',
                'ref_id'       => $order->id,
                'note'         => "payement de la commande {$order->reference} — virement MoMo {$order->payer_phone}",
            ]);

            $this->wallet->creditEscrow(
                $order->shop->user,
                $order->net_amount,
                $order
            );
        });

        Log::info('Paiement confirmé', ['order' => $order->reference]);
    }

    // ── MARQUER UN PAIEMENT ÉCHOUÉ ───────────────────────────────────
    // Logique INCHANGÉE.
    private function failPayment(Order $order, OrderPayment $payment): void
    {
        DB::transaction(function () use ($order, $payment) {

            $payment->update(['status' => 'failed']);
            $order->update(['status' => Order::STATUS_FAILED]);

            foreach ($order->items as $item) {
                $item->product->decrement('stock_reserved', $item->quantity);
            }
        });

        Log::info('Paiement échoué', ['order' => $order->reference]);
    }

    // ── PAIEMENT DES FRAIS TRANSPORT ──────────────────────────────────
    // AJOUT PAR RAPPORT À LA VERSION CAMPAY : Gross-Up. L'acheteur paie
    // désormais le montant BRUT ; la plateforme (donc le vendeur, in fine)
    // reçoit exactement $transportFee net, comme pour la commande principale.
    // Avant, le montant net était envoyé tel quel à Campay, sans compensation
    // des frais gateway — c'était le trou signalé.
    public function initiateTransportPayment(
        Order $order,
        string $phone,
        string $operator
    ): void {
        $transportFee = $order->shipment->transport_fee ;

        if ($transportFee <= 0) {
            throw new \Exception('Aucun frais de transport à payer.');
        }

        if ($order->shipment->transport_fee_paid) {
            throw new \Exception('Les frais transport ont déjà été payés.');
        }

        // Gross-Up : l'acheteur paie ce montant, la plateforme reçoit
        // exactement $transportFee net après déduction des frais gateway.
        $grossUp     = $this->elgiopay->grossUpCollect($transportFee);
        $grossAmount = $grossUp['gross'];
        $gatewayFee  = $grossUp['fee'];

        $formattedPhone = '237' . ltrim($phone, '0');
        $reference      = 'TRANSPORT-' . $order->id . '-' . Str::uuid();

        try {
            $result = $this->elgiopay->collect(
                phone: $formattedPhone,
                amount: $grossAmount,
                name:$order->buyer->name,
                reference: $reference,
                description: "Frais transport commande {$order->reference}",
                operator: $operator
            );

            // provider_reference = transaction_id Elgiopay (sert au matching webhook).
            // transport_fee_gross_amount / transport_fee_gateway_fee : nouvelles
            // colonnes, gardées en BDD pour l'audit — voir migration dédiée.
            $order->shipment->update([
                'transport_payment_reference' => $result['transaction_id'] ?? $reference,
                'transport_fee_gross_amount'  => $grossAmount,
                'transport_fee_gateway_fee'   => $gatewayFee,
            ]);

            Log::info('Transport payment initiated', [
                'order'          => $order->reference,
                'phone'          => $formattedPhone,
                'net'            => $transportFee,
                'gross'          => $grossAmount,
                'gateway_fee'    => $gatewayFee,
                'transaction_id' => $result['transaction_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Transport payment failed', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        // amount = montant réellement débité côté acheteur (le brut), gardé
        // en BDD dans wallet_transactions comme trace comptable — le montant
        // net (transport_fee) reste consultable sur order_shipments.
        WalletTransaction::create([
            'user_id'      => $order->buyer->id,
            'type'         => WalletTransaction::TYPE_DEBIT_TRANSPORT_FEE,
            'amount'       => $grossAmount,
            'balance_after' => 0,
            'ref_type'     => 'shipment',
            'ref_id'       => $order->shipment->id,
            'note'         => "payement des frais de transport {$order->reference} — virement MoMo {$formattedPhone} (net: {$transportFee} FCFA, frais gateway: {$gatewayFee} FCFA)",
        ]);
    }

    // ── SYNCHRONISATION TRANSPORT ─────────────────────────────────────
    // Logique INCHANGÉE, seuls le service et le statut minuscule changent.
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
            $transaction = $this->elgiopay->getTransaction($shipment->transport_payment_reference);
            $status      = strtolower($transaction['status'] ?? '');

            if ($status === 'completed') {
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
            Log::warning('Synchronisation transport Elgiopay échouée', [
                'order' => $order->reference,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
