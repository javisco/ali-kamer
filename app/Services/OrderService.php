<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderShipment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    // Taux fixes MVP
    const PROTECTION_RATE  = 0.02; // 2% payé par acheteur
    const GATEWAY_RATE     = 0.02; // 2% Campay collecte
    const COMMISSION_RATE  = 0.05; // 5% plateforme
    const AGENCY_RATE      = 0.01; // 1% agences
    const PAYOUT_RATE      = 0.01; // 1% Campay retrait

    // ── Créer une commande ────────────────────────────────────────────

    public function create(User $buyer, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $data) {

            $product  = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'];

            // 1. Vérifier le stock disponible
            if ($product->availableStock() < $quantity) {
                throw new \Exception('Stock insuffisant.');
            }

            // 2. Calculer les montants
            $subtotal          = $product->price * $quantity;
            $protectionFee     = (int) round($subtotal * self::PROTECTION_RATE);
            $gatewayFee        = (int) round($subtotal * self::GATEWAY_RATE);
            $totalAmount       = $subtotal + $protectionFee + $gatewayFee;
            $platformCommission = (int) round($subtotal * self::COMMISSION_RATE);
            $agencyCommission  = (int) round($subtotal * self::AGENCY_RATE);
            $gatewayPayoutFee  = (int) round($subtotal * self::PAYOUT_RATE);
            $netAmount         = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;

            // 3. Créer la commande
            $order = Order::create([
                'reference'          => Order::generateReference(),
                'buyer_id'           => $buyer->id,
                'shop_id'            => $product->shop_id,
                'status'             => Order::STATUS_PENDING,
                'subtotal'           => $subtotal,
                'protection_fee'     => $protectionFee,
                'gateway_fee'        => $gatewayFee,
                'total_amount'       => $totalAmount,
                'platform_commission' => $platformCommission,
                'agency_commission'  => $agencyCommission,
                'gateway_payout_fee' => $gatewayPayoutFee,
                'net_amount'         => $netAmount,
                'shipping_fee'       => 0,
                'deposit_code'       => strtoupper(Str::random(8)),

                // Instantané financier immuable
                'financial_snapshot' => [
                    'protection_rate'  => self::PROTECTION_RATE,
                    'gateway_rate'     => self::GATEWAY_RATE,
                    'commission_rate'  => self::COMMISSION_RATE,
                    'agency_rate'      => self::AGENCY_RATE,
                    'payout_rate'      => self::PAYOUT_RATE,
                    'calculated_at'    => now()->toISOString(),
                ],

                'buyer_note' => $data['note'] ?? null,
            ]);

            // 4. Créer la ligne de commande
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $product->id,
                'product_title' => $product->title,
                'quantity'      => $quantity,
                'unit_price'    => $product->price,
                'subtotal'      => $subtotal,
            ]);

            // 5. Créer l'expédition
            OrderShipment::create([
                'order_id'         => $order->id,
                'type'             => $data['shipping_type'] ?? 'interurban',
                'shipping_included' => $product->shipping_included,
                'recipient_name'   => $buyer->name,
                'recipient_phone'  => $buyer->phone,
                'destination_city' => $data['destination_city'],
            ]);

            // 6. Créer le paiement
            OrderPayment::create([
                'order_id'         => $order->id,
                'method'           => 'manual',
                'status'           => 'pending',
                'idempotency_key'  => OrderPayment::generateIdempotencyKey(),
                'payer_phone'      => $data['payer_phone'],
                'payer_operator'   => $data['payer_operator'],
            ]);

            // 7. Réserver le stock
            $product->increment('stock_reserved', $quantity);

            // 8. Passer en awaiting_payment
            $order->update(['status' => Order::STATUS_AWAITING_PAYMENT]);

            return $order;
        });
    }

    // ── Confirmer le paiement manuel (admin valide) ───────────────────

    public function confirmManualPayment(Order $order, User $admin, string $transactionId): void
    {
        DB::transaction(function () use ($order, $admin, $transactionId) {

            $order->payment->update([
                'status'                 => 'succeeded',
                'manual_transaction_id'  => $transactionId,
                'manual_validated_by'    => $admin->id,
                'manual_validated_at'    => now(),
                'paid_at'                => now(),
            ]);

            $order->update([
                'status'  => Order::STATUS_PAID,
                'paid_at' => now(),
            ]);

            // Créditer le wallet vendeur en séquestre
            // app(WalletService::class)->creditEscrow($order->shop->user, $order->net_amount, $order);
        });
    }

    // ── Vendeur marque "En préparation" ──────────────────────────────

    public function markPreparing(Order $order): void
    {
        $order->update([
            'status'       => Order::STATUS_PREPARING,
            'preparing_at' => now(),
        ]);
    }

    // ── Secrétaire départ enregistre le colis ────────────────────────

    public function registerAtOrigin(Order $order, User $secretary, int $counterId): void
    {
        DB::transaction(function () use ($order, $secretary, $counterId) {

            $order->shipment->update([
                'origin_counter_id' => $counterId,
                'registered_by'     => $secretary->id,
                'registered_at'     => now(),
            ]);

            $order->update([
                'status'     => Order::STATUS_REGISTERED_ORIGIN,
                'shipped_at' => now(),
            ]);
        });
    }

    // ── Secrétaire arrivée valide la réception ───────────────────────

    public function registerAtDestination(Order $order, User $secretary, int $counterId): void
    {
        DB::transaction(function () use ($order, $secretary, $counterId) {

            $order->shipment->update([
                'destination_counter_id' => $counterId,
                'validated_by'           => $secretary->id,
                'arrived_at'             => now(),
            ]);

            // Générer l'OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $order->update([
                'status'         => Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                'arrived_at'     => now(),
                'otp_code'       => $otp,
                'otp_expires_at' => now()->addHours(120), // 5 jours
                'timer_deadline' => now()->addHours(72),  // 72h pour AUTO_COMPLETE
            ]);

            // Créditer immédiatement le solde vendeur (en attente)
            // app(WalletService::class)->creditEscrow($order->shop->user, $order->net_amount, $order);

            // Notifier l'acheteur avec l'OTP
            // app(NotificationService::class)->sendOtp($order->buyer, $otp);
        });
    }

    // ── Acheteur remet l'OTP au secrétaire ───────────────────────────

    public function confirmWithOtp(Order $order, string $otp): void
    {
        if ($order->otp_code !== $otp) {
            throw new \Exception('Code OTP incorrect.');
        }

        if ($order->otp_expires_at && now()->isAfter($order->otp_expires_at)) {
            throw new \Exception('Code OTP expiré.');
        }

        DB::transaction(function () use ($order, $otp) {
            $order->update([
                'status'       => Order::STATUS_COMPLETED,
                'otp_used_at'  => now(),
                'completed_at' => now(),
            ]);

            // Libérer les fonds du vendeur
            // app(WalletService::class)->releaseEscrow($order->shop->user, $order->net_amount, $order);
        });
    }

    // ── Cron job : AUTO_COMPLETE les commandes expirées (72h) ────────

    public function autoCompleteExpired(): void
    {
        Order::where('status', Order::STATUS_AWAITING_BUYER_CONFIRMATION)
            ->where('timer_deadline', '<=', now())
            ->each(function (Order $order) {
                DB::transaction(function () use ($order) {
                    $order->update([
                        'status'       => Order::STATUS_AUTO_COMPLETED,
                        'completed_at' => now(),
                    ]);
                    // app(WalletService::class)->releaseEscrow($order->shop->user, $order->net_amount, $order);
                });
            });
    }

    // ── Annuler une commande ──────────────────────────────────────────

    public function cancel(Order $order, string $reason): void
    {
        DB::transaction(function () use ($order, $reason) {
            // Libérer le stock réservé
            foreach ($order->items as $item) {
                $item->product->decrement('stock_reserved', $item->quantity);
            }

            $order->update([
                'status'               => Order::STATUS_CANCELLED,
                'cancelled_at'         => now(),
                'cancellation_reason'  => $reason,
            ]);

            // Rembourser si déjà payé
            // if ($order->payment->isSucceeded()) {
            //     app(WalletService::class)->refund($order->buyer, $order->total_amount, $order);
            // }
        });
    }
}
