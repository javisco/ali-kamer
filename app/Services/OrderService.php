<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderShipment;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    // Taux MVP — seront déplacés en BDD (PlatformSetting) en Phase 2

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


            $protectionRate      = PlatformSetting::getRate('protection_rate');
            $gatewayRate         = PlatformSetting::getRate('gateway_collect_rate');
            $commissionRate      = PlatformSetting::getRate('platform_commission_rate');
            $agencyRate          = PlatformSetting::getRate('agency_commission_rate');
            $payoutRate          = PlatformSetting::getRate('gateway_payout_rate');

            $subtotal            = $product->price * $quantity;
            $protectionFee       = (int) round($subtotal * $protectionRate);
            $gatewayFee          = (int) round($subtotal * $gatewayRate);
            $totalAmount         = $subtotal + $protectionFee + $gatewayFee;
            $platformCommission  = (int) round($subtotal * $commissionRate);
            $agencyCommission    = (int) round($subtotal * $agencyRate);
            $gatewayPayoutFee    = (int) round($subtotal * $payoutRate);
            $netAmount           = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;

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
                    'protection_rate'  => $protectionRate,
                    'gateway_rate'     => $gatewayRate,
                    'commission_rate'  => $commissionRate,
                    'agency_rate'      => $gatewayPayoutFee,
                    'payout_rate'      => $payoutRate,
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

    // Créer une commande depuis le panier (plusieurs articles)
    public function createFromCart(User $buyer, Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $cart, $data) {

            $items   = $cart->items->load('product', 'variant');
            $shopId  = $items->first()->product->shop_id;

            // Calcul du sous-total
            $subtotal = $items->sum(fn($item) => $item->unit_price * $item->quantity);

            // Récupérer les taux depuis PlatformSetting
            $protectionRate     = PlatformSetting::getRate('protection_rate');
            $gatewayRate        = PlatformSetting::getRate('gateway_collect_rate');
            $commissionRate     = PlatformSetting::getRate('platform_commission_rate');
            $agencyRate         = PlatformSetting::getRate('agency_commission_rate');
            $payoutRate         = PlatformSetting::getRate('gateway_payout_rate');

            $protectionFee      = (int) round($subtotal * $protectionRate);
            $gatewayFee         = (int) round($subtotal * $gatewayRate);
            $totalAmount        = $subtotal + $protectionFee + $gatewayFee;
            $platformCommission = (int) round($subtotal * $commissionRate);
            $agencyCommission   = (int) round($subtotal * $agencyRate);
            $gatewayPayoutFee   = (int) round($subtotal * $payoutRate);
            $netAmount          = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;

            // Créer la commande
            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $shopId,
                'status'              => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'            => $subtotal,
                'protection_fee'      => $protectionFee,
                'gateway_fee'         => $gatewayFee,
                'total_amount'        => $totalAmount,
                'platform_commission' => $platformCommission,
                'agency_commission'   => $agencyCommission,
                'gateway_payout_fee'  => $gatewayPayoutFee,
                'net_amount'          => $netAmount,
                'shipping_fee'        => 0,
                'deposit_code'        => strtoupper(Str::random(8)),
                'financial_snapshot'  => [
                    'protection_rate'  => $protectionRate,
                    'gateway_rate'     => $gatewayRate,
                    'commission_rate'  => $commissionRate,
                    'agency_rate'      => $agencyRate,
                    'payout_rate'      => $payoutRate,
                    'calculated_at'    => now()->toISOString(),
                ],
            ]);

            // Créer les lignes de commande depuis le panier
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_title'     => $item->product->title .
                        ($item->variantLabel() ? ' — ' . $item->variantLabel() : ''),
                    'quantity'          => $item->quantity,
                    'unit_price'        => $item->unit_price,
                    'subtotal'          => $item->subtotal(),
                ]);

                // Réserver le stock
                if ($item->variant) {
                    $item->variant->increment('stock_reserved', $item->quantity);
                } else {
                    $item->product->increment('stock_reserved', $item->quantity);
                }
            }

            // Expédition
            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => false,
                'recipient_name'    => $buyer->name,
                'recipient_phone'   => $buyer->phone,
                'destination_city'  => $data['destination_city'],
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

            return $order;
        });
    }
}











// // Dans OrderService::create() — remplacer les constantes par PlatformSetting
// $protectionRate      = PlatformSetting::getRate('protection_rate');
// $gatewayRate         = PlatformSetting::getRate('gateway_collect_rate');
// $commissionRate      = PlatformSetting::getRate('platform_commission_rate');
// $agencyRate          = PlatformSetting::getRate('agency_commission_rate');
// $payoutRate          = PlatformSetting::getRate('gateway_payout_rate');

// $subtotal            = $product->price * $quantity;
// $protectionFee       = (int) round($subtotal * $protectionRate);
// $gatewayFee          = (int) round($subtotal * $gatewayRate);
// $totalAmount         = $subtotal + $protectionFee + $gatewayFee;
// $platformCommission  = (int) round($subtotal * $commissionRate);
// $agencyCommission    = (int) round($subtotal * $agencyRate);
// $gatewayPayoutFee    = (int) round($subtotal * $payoutRate);
// $netAmount           = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;
