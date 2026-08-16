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
    // Taux MVP — seront déplacés en BDD (PlatformSetting) en Phase 2
    const PROTECTION_RATE   = 0.02;
    const GATEWAY_RATE      = 0.02;
    const COMMISSION_RATE   = 0.05;
    const AGENCY_RATE       = 0.01;
    const PAYOUT_RATE       = 0.01;

    // ── Créer une commande ────────────────────────────────────────────

    public function create(User $buyer, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $data) {

            $product  = Product::findOrFail($data['product_id']);
            $quantity = (int) $data['quantity'];

            // Vérifier le stock
            if ($product->availableStock() < $quantity) {
                throw new \Exception('Stock insuffisant.');
            }

            // Calcul des montants
            $subtotal           = $product->price * $quantity;
            $protectionFee      = (int) round($subtotal * self::PROTECTION_RATE);
            $gatewayFee         = (int) round($subtotal * self::GATEWAY_RATE);
            $totalAmount        = $subtotal + $protectionFee + $gatewayFee;
            $platformCommission = (int) round($subtotal * self::COMMISSION_RATE);
            $agencyCommission   = (int) round($subtotal * self::AGENCY_RATE);
            $gatewayPayoutFee   = (int) round($subtotal * self::PAYOUT_RATE);
            $netAmount          = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;

            // Créer la commande
            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $product->shop_id,
                'status'              => Order::STATUS_PENDING,
                'subtotal'            => $subtotal,
                'protection_fee'      => $protectionFee,
                'gateway_fee'         => $gatewayFee,
                'total_amount'        => $totalAmount,
                'platform_commission' => $platformCommission,
                'agency_commission'   => $agencyCommission,
                'gateway_payout_fee'  => $gatewayPayoutFee,
                'net_amount'          => $netAmount,
                'shipping_fee'        => 0,

                // Code unique donné par le vendeur au secrétaire départ
                // Généré ici — jamais modifiable après
                'deposit_code' => strtoupper(Str::random(8)),

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

            // Ligne de commande
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $product->id,
                'product_title' => $product->title,
                'quantity'      => $quantity,
                'unit_price'    => $product->price,
                'subtotal'      => $subtotal,
            ]);

            // Expédition — agency_id et comptoirs seront définis
            // quand le vendeur choisit son agence (status=paid)
            OrderShipment::create([
                'order_id'         => $order->id,
                'type'             => 'interurban',
                'shipping_included' => $product->shipping_included,
                'recipient_name'   => $buyer->name,
                'recipient_phone'  => $buyer->phone,
                'destination_city' => $data['destination_city'],
            ]);

            // Paiement
            OrderPayment::create([
                'order_id'        => $order->id,
                'method'          => 'campay',
                'status'          => 'pending',
                'idempotency_key' => OrderPayment::generateIdempotencyKey(),
                'payer_phone'     => $data['payer_phone'],
                'payer_operator'  => $data['payer_operator'],
            ]);

            // Réserver le stock
            $product->increment('stock_reserved', $quantity);

            // Passer en awaiting_payment
            $order->update(['status' => Order::STATUS_AWAITING_PAYMENT]);

            return $order;
        });
    }

    // ── Annuler une commande ──────────────────────────────────────────

    public function cancel(Order $order, string $reason): void
    {
        DB::transaction(function () use ($order, $reason) {

            foreach ($order->items as $item) {
                $item->product->decrement('stock_reserved', $item->quantity);
            }

            $order->update([
                'status'              => Order::STATUS_CANCELLED,
                'cancelled_at'        => now(),
                'cancellation_reason' => $reason,
            ]);
        });
    }

    // ── Auto-compléter les commandes expirées (cron 72h) ─────────────

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
                    // app(WalletService::class)->releaseEscrow(...);
                });
            });
    }
}
