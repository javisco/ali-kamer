<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderShipment;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private ElgiopayService $elgiopay) {}

    // ── CALCUL FINANCIER CENTRAL ──────────────────────────────────────
    //
    // Logique métier INCHANGÉE par rapport à la version Campay :
    //   - Vendeur reçoit exactement   : subtotal × (1 - commission_rate)
    //   - Agence reçoit exactement    : subtotal × agency_rate
    //   - Acheteur paie               : Gross-Up de (subtotal + protection)
    //   - Frais gateway payout        : supportés par la plateforme
    //   - Frais gateway collect       : supportés par l'acheteur via Gross-Up
    //
    // Seul changement : $this->campay → $this->elgiopay, et les clés du
    // snapshot 'rates' passent de campay_* à gateway_* (génériques).
    public function calculateFinancials(int $subtotal): array
    {
        $commissionRate = PlatformSetting::getRate('platform_commission_rate');
        $agencyRate     = PlatformSetting::getRate('agency_commission_rate');
        $protectionRate = PlatformSetting::getRate('protection_rate');

        // ── Côté vendeur ────────────────────────────────────────────
        $platformCommission = (int) round($subtotal * $commissionRate);
        $netSeller           = $subtotal - $platformCommission;

        $payoutGrossUp   = $this->elgiopay->grossUpPayout($netSeller);
        $grossSeller     = $payoutGrossUp['gross'];
        $gatewayPayoutFee = $payoutGrossUp['fee'];

        // ── Côté agence ─────────────────────────────────────────────
        $agencyCommission = (int) round($subtotal * $agencyRate);

        $agencyGrossUp    = $this->elgiopay->grossUpPayout($agencyCommission);
        $grossAgency       = $agencyGrossUp['gross'];
        $gatewayAgencyFee  = $agencyGrossUp['fee'];

        // ── Côté acheteur ───────────────────────────────────────────
        $protectionFee = (int) ceil($subtotal * $protectionRate);

        $platformWantsToReceive = $subtotal + $protectionFee;

        $collectGrossUp    = $this->elgiopay->grossUpCollect($platformWantsToReceive);
        $totalAmount        = $collectGrossUp['gross'];
        $gatewayCollectFee  = $collectGrossUp['fee'];

        // ── Marge nette Ali-Kamer ───────────────────────────────────
        $platformNet = $protectionFee + $platformCommission
            - $gatewayPayoutFee
            - $gatewayAgencyFee;

        return [
            // Côté acheteur
            'subtotal'             => $subtotal,
            'protection_fee'       => $protectionFee,
            'gateway_collect_fee'  => $gatewayCollectFee,
            'total_amount'         => $totalAmount,

            // Côté vendeur
            'platform_commission'  => $platformCommission,
            'net_seller'           => $netSeller,
            'gross_seller'         => $grossSeller,

            // Côté agence
            'agency_commission'    => $agencyCommission,
            'net_agency'           => $agencyCommission,
            'gross_agency'         => $grossAgency,

            // Frais gateway
            'gateway_payout_fee'   => $gatewayPayoutFee,
            'gateway_agency_fee'   => $gatewayAgencyFee,
            'gateway_collect_fee_detail' => $gatewayCollectFee,

            // Marge Ali-Kamer
            'platform_net'         => $platformNet,

            // Snapshot des taux appliqués — immuable
            'rates' => [
                'commission_rate'      => $commissionRate,
                'agency_rate'          => $agencyRate,
                'protection_rate'      => $protectionRate,
                'gateway_collect_rate' => PlatformSetting::getRate('gateway_collect_rate'),
                'gateway_payout_rate'  => PlatformSetting::getRate('gateway_payout_rate'),
                'gateway_fixed_fee'    => PlatformSetting::getValue('gateway_fixed_fee', 0),
                'calculated_at'        => now()->toISOString(),
            ],
        ];
    }

    // ── CRÉER UNE COMMANDE ────────────────────────────────────────────
    public function create(User $buyer, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $data) {

            $product  = Product::findOrFail($data['product_id']);
            $quantity = (int) $data['quantity'];

            $variant = isset($data['variant_id'])
                ? ProductVariant::findOrFail($data['variant_id'])
                : null;

            $availableStock = $variant
                ? $variant->availableStock()
                : $product->availableStock();

            if ($availableStock < $quantity) {
                throw new \Exception('Stock insuffisant. Disponible : ' . $availableStock);
            }

            $unitPrice = $variant ? $variant->price : $product->price;
            $subtotal  = $unitPrice * $quantity;

            $fin = $this->calculateFinancials($subtotal);

            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $product->shop_id,
                'status'              => Order::STATUS_AWAITING_PAYMENT,

                'subtotal'            => $fin['subtotal'],
                'protection_fee'      => $fin['protection_fee'],
                'shipping_fee'        => 0,

                // gateway_fee = frais Elgiopay collect supportés par l'acheteur
                'gateway_fee'         => $fin['gateway_collect_fee'],

                'total_amount'        => $fin['total_amount'],

                'platform_commission' => $fin['platform_commission'],
                'agency_commission'   => $fin['agency_commission'],

                // Frais Elgiopay payout (supportés par la plateforme)
                'gateway_payout_fee'  => $fin['gateway_payout_fee'],

                'net_amount'          => $fin['net_seller'],

                'gross_seller_amount' => $fin['gross_seller'],
                'net_agency_amount'   => $fin['net_agency'],
                'gross_agency_amount' => $fin['gross_agency'],

                'deposit_code'        => strtoupper(Str::random(8)),

                'financial_snapshot'  => $fin,

                'buyer_note' => $data['note'] ?? null,
            ]);

            OrderItem::create([
                'order_id'           => $order->id,
                'product_id'         => $product->id,
                'product_variant_id' => $variant?->id,
                'product_title'      => $product->title
                    . ($variant ? ' — ' . $variant->label() : ''),
                'quantity'           => $quantity,
                'unit_price'         => $unitPrice,
                'subtotal'           => $subtotal,
            ]);

            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => $product->shipping_included,
                'recipient_name'    => $buyer->name,
                'recipient_phone'   => $buyer->phone,
                'destination_city'  => $data['destination_city'],
            ]);

            OrderPayment::create([
                'order_id'        => $order->id,
                'method'          => 'elgiopay',
                'status'          => 'pending',
                'idempotency_key' => OrderPayment::generateIdempotencyKey(),
                'payer_phone'     => $data['payer_phone'],
                'payer_operator'  => $data['payer_operator'],
            ]);

            $variant
                ? $variant->increment('stock_reserved', $quantity)
                : $product->increment('stock_reserved', $quantity);

            return $order;
        });
    }

    // ── CRÉER DEPUIS LE PANIER ────────────────────────────────────────
    public function createFromCart(User $buyer, $cart, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $cart, $data) {

            $items    = $cart->items->load('product', 'variant');
            $subtotal = $items->sum(fn($item) => $item->unit_price * $item->quantity);
            $shopId   = $items->first()->product->shop_id;

            $fin = $this->calculateFinancials($subtotal);

            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $shopId,
                'status'              => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'            => $fin['subtotal'],
                'protection_fee'      => $fin['protection_fee'],
                'shipping_fee'        => 0,
                'gateway_fee'         => $fin['gateway_collect_fee'],
                'total_amount'        => $fin['total_amount'],
                'platform_commission' => $fin['platform_commission'],
                'agency_commission'   => $fin['agency_commission'],
                'gateway_payout_fee'  => $fin['gateway_payout_fee'],
                'net_amount'          => $fin['net_seller'],
                'gross_seller_amount' => $fin['gross_seller'],
                'net_agency_amount'   => $fin['net_agency'],
                'gross_agency_amount' => $fin['gross_agency'],
                'deposit_code'        => strtoupper(Str::random(8)),
                'financial_snapshot'  => $fin,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_title'      => $item->product->title
                        . ($item->variantLabel() ? ' — ' . $item->variantLabel() : ''),
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->unit_price,
                    'subtotal'           => $item->subtotal(),
                ]);

                $item->variant
                    ? $item->variant->increment('stock_reserved', $item->quantity)
                    : $item->product->increment('stock_reserved', $item->quantity);
            }

            $allShippingIncluded = $items->every(fn($item) => (bool) $item->product->shipping_included);

            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => $allShippingIncluded,
                'recipient_name'    => $buyer->name,
                'recipient_phone'   => $buyer->phone,
                'destination_city'  => $data['destination_city'],
            ]);

            OrderPayment::create([
                'order_id'        => $order->id,
                'method'          => 'elgiopay',
                'status'          => 'pending',
                'idempotency_key' => OrderPayment::generateIdempotencyKey(),
                'payer_phone'     => $data['payer_phone'],
                'payer_operator'  => $data['payer_operator'],
            ]);

            return $order;
        });
    }

    // ── ANNULER ───────────────────────────────────────────────────────
    public function cancel(Order $order, string $reason): void
    {
        DB::transaction(function () use ($order, $reason) {
            foreach ($order->items as $item) {
                $item->product_variant_id
                    ? $item->variant?->decrement('stock_reserved', $item->quantity)
                    : $item->product?->decrement('stock_reserved', $item->quantity);
            }
            $order->update([
                'status'              => Order::STATUS_CANCELLED,
                'cancelled_at'        => now(),
                'cancellation_reason' => $reason,
            ]);
        });
    }

    // ── AUTO-COMPLÉTION ───────────────────────────────────────────────
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
                    app(WalletService::class)->releaseEscrow(
                        $order->shop->user,
                        $order->net_amount,
                        $order
                    );
                });
            });
    }
}
