<?php

namespace App\Services;

use App\Models\Cart;
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
    // ── Créer une commande directe (Un produit) ────────────────────────

    public function create(User $buyer, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $data) {

            $product  = Product::findOrFail($data['product_id']);
            $quantity = (int) $data['quantity'];

            // Vérifier le stock
            if ($product->availableStock() < $quantity) {
                throw new \Exception('Stock insuffisant.');
            }

            $subtotal    = $product->price * $quantity;
            $financials  = $this->calculateFinancials($subtotal);

            // Créer la commande
            $order = Order::create(array_merge([
                'reference'        => Order::generateReference(),
                'buyer_id'         => $buyer->id,
                'shop_id'          => $product->shop_id,
                'status'           => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'         => $subtotal,
                'shipping_fee'     => 0,
                'deposit_code'     => strtoupper(Str::random(8)),
                'buyer_note'       => $data['note'] ?? null,
            ], $financials));

            // Ligne de commande
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $product->id,
                'product_title' => $product->title,
                'quantity'      => $quantity,
                'unit_price'    => $product->price,
                'subtotal'      => $subtotal,
            ]);

            // Expédition
            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => (bool) $product->shipping_included,
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

            // Réserver le stock
            $product->increment('stock_reserved', $quantity);

            return $order;
        });
    }

    // ── Créer une commande depuis le panier (Multi-articles) ───────────

    public function createFromCart(User $buyer, Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $cart, $data) {

            $items  = $cart->items->load('product', 'variant');
            $shopId = $items->first()->product->shop_id;

            // Calcul du sous-total
            $subtotal   = $items->sum(fn($item) => $item->unit_price * $item->quantity);
            $financials = $this->calculateFinancials($subtotal);

            // Vérifier si le transport est inclus pour TOUS les articles du panier
            $allShippingIncluded = $items->every(fn($item) => (bool) $item->product->shipping_included);

            // Créer la commande
            $order = Order::create(array_merge([
                'reference'    => Order::generateReference(),
                'buyer_id'     => $buyer->id,
                'shop_id'      => $shopId,
                'status'       => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'     => $subtotal,
                'shipping_fee' => 0,
                'deposit_code' => strtoupper(Str::random(8)),
            ], $financials));

            // Créer les lignes de commande depuis le panier
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_title'      => $item->product->title .
                        ($item->variantLabel() ? ' — ' . $item->variantLabel() : ''),
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->unit_price,
                    'subtotal'           => $item->subtotal(),
                ]);

                // Réserver le stock
                if ($item->variant) {
                    $item->variant->increment('stock_reserved', $item->quantity);
                } else {
                    $item->product->increment('stock_reserved', $item->quantity);
                }
            }

            // Expédition (incluse seulement si TOUS les articles l'incluent)
            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => $allShippingIncluded,
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

    // ── Annuler une commande ──────────────────────────────────────────

    public function cancel(Order $order, string $reason): void
    {
        DB::transaction(function () use ($order, $reason) {

            foreach ($order->items as $item) {
                if ($item->productVariant) {
                    $item->productVariant->decrement('stock_reserved', $item->quantity);
                } else {
                    $item->product->decrement('stock_reserved', $item->quantity);
                }
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

    // ── Calculs financiers mutualisés ────────────────────────────────

    private function calculateFinancials(int|float $subtotal): array
    {
        $protectionRate = PlatformSetting::getRate('protection_rate');
        $gatewayRate    = PlatformSetting::getRate('gateway_collect_rate');
        $commissionRate = PlatformSetting::getRate('platform_commission_rate');
        $agencyRate     = PlatformSetting::getRate('agency_commission_rate');
        $payoutRate     = PlatformSetting::getRate('gateway_payout_rate');

        $protectionFee      = (int) round($subtotal * $protectionRate);
        $gatewayFee         = (int) round($subtotal * $gatewayRate);
        $totalAmount        = $subtotal + $protectionFee + $gatewayFee;
        $platformCommission = (int) round($subtotal * $commissionRate);
        $agencyCommission   = (int) round($subtotal * $agencyRate);
        $gatewayPayoutFee   = (int) round($subtotal * $payoutRate);
        $netAmount          = $subtotal - $platformCommission - $agencyCommission - $gatewayPayoutFee;

        return [
            'protection_fee'      => $protectionFee,
            'gateway_fee'         => $gatewayFee,
            'total_amount'        => $totalAmount,
            'platform_commission' => $platformCommission,
            'agency_commission'   => $agencyCommission,
            'gateway_payout_fee'  => $gatewayPayoutFee,
            'net_amount'          => $netAmount,
            'financial_snapshot'  => [
                'protection_rate' => $protectionRate,
                'gateway_rate'    => $gatewayRate,
                'commission_rate' => $commissionRate,
                'agency_rate'     => $agencyRate,
                'payout_rate'     => $payoutRate,
                'calculated_at'   => now()->toISOString(),
            ],
        ];
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
