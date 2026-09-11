<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderGroup;
use App\Models\OrderGroupPayment;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderShipment;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\ProductOutOfStockNotification;

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
            $this->checkStockAndNotify($variant ?? $product, $product);
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


    // Calcule la part vendeur/agence pour une boutique, SANS le gross-up
    // collecte (celui-ci se calcule une seule fois au niveau du groupe).
    public function calculatePayoutSplits(int $subtotal): array
    {
        $commissionRate = PlatformSetting::getRate('platform_commission_rate');
        $agencyRate     = PlatformSetting::getRate('agency_commission_rate');
        $protectionRate = PlatformSetting::getRate('protection_rate');

        $platformCommission = (int) ceil($subtotal * $commissionRate);
        $netSeller           = $subtotal - $platformCommission;
        $payoutGrossUp       = $this->elgiopay->grossUpPayout($netSeller);

        $agencyCommission = (int) round($subtotal * $agencyRate);
        $agencyGrossUp    = $this->elgiopay->grossUpPayout($agencyCommission);

        $protectionFee = (int) ceil($subtotal * $protectionRate);

        return [
            'subtotal'            => $subtotal,
            'protection_fee'      => $protectionFee,
            'platform_commission' => $platformCommission,
            'net_seller'          => $netSeller,
            'gross_seller'        => $payoutGrossUp['gross'],
            'gateway_payout_fee'  => $payoutGrossUp['fee'],
            'agency_commission'   => $agencyCommission,
            'net_agency'          => $agencyCommission,
            'gross_agency'        => $agencyGrossUp['gross'],
            'gateway_agency_fee'  => $agencyGrossUp['fee'],
        ];
    }


    // ── CRÉER DEPUIS LE PANIER ────────────────────────────────────────
    // ── CRÉER DEPUIS LE PANIER ────────────────────────────────────────
    // Détecte automatiquement mono ou multi-vendeur.
    public function createFromCart(User $buyer, $cart, array $data): Order|OrderGroup
    {
        return DB::transaction(function () use ($buyer, $cart, $data) {

            $items       = $cart->items->load('product.shop', 'variant');
            $itemsByShop = $items->groupBy(fn($item) => $item->product->shop_id);

            if ($itemsByShop->count() === 1) {
                // Comportement inchangé : un seul vendeur.
                return $this->createSingleShopOrder($buyer, $items, $data);
            }

            return $this->createMultiShopOrderGroup($buyer, $itemsByShop, $data);
        });
    }

    // ── PANIER MONO-VENDEUR (logique identique à l'ancienne version) ──
    private function createSingleShopOrder(User $buyer, $items, array $data): Order
    {
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

            $this->checkStockAndNotify($item->variant ?? $item->product, $item->product);
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
    }

    // ── PANIER MULTI-VENDEUR : un OrderGroup + un Order par boutique ──
    private function createMultiShopOrderGroup(User $buyer, $itemsByShop, array $data): OrderGroup
    {
        $totalSubtotal    = 0;
        $totalProtection  = 0;
        $shopCalculations = [];

        foreach ($itemsByShop as $shopId => $shopItems) {
            $shopSubtotal = $shopItems->sum(fn($item) => $item->unit_price * $item->quantity);
            $calc         = $this->calculatePayoutSplits($shopSubtotal);

            $shopCalculations[$shopId] = $calc;
            $totalSubtotal   += $shopSubtotal;
            $totalProtection += $calc['protection_fee'];
        }

        // Gross-up UNIQUE pour tout le panier — un seul débit MoMo,
        // un seul frais fixe, peu importe le nombre de boutiques.
        $collectGrossUp = $this->elgiopay->grossUpCollect($totalSubtotal + $totalProtection);

        $group = OrderGroup::create([
            'reference'          => OrderGroup::generateReference(),
            'buyer_id'           => $buyer->id,
            'status'             => OrderGroup::STATUS_AWAITING_PAYMENT,
            'subtotal'           => $totalSubtotal,
            'protection_fee'     => $totalProtection,
            'gateway_fee'        => $collectGrossUp['fee'],
            'total_amount'       => $collectGrossUp['gross'],
            'financial_snapshot' => [
                'shops'             => $shopCalculations,
                'collect_gross_up'  => $collectGrossUp,
                'calculated_at'     => now()->toISOString(),
            ],
        ]);

        foreach ($itemsByShop as $shopId => $shopItems) {
            $calc = $shopCalculations[$shopId];

            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $shopId,
                'order_group_id'      => $group->id,
                'status'              => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'            => $calc['subtotal'],
                'protection_fee'      => $calc['protection_fee'],
                'shipping_fee'        => 0,
                // Le frais gateway "collecte" est porté par le groupe, pas par chaque commande.
                'gateway_fee'         => 0,
                // Part nette de cette boutique (hors gross-up, qui n'existe qu'au niveau du groupe).
                'total_amount'        => $calc['subtotal'] + $calc['protection_fee'],
                'platform_commission' => $calc['platform_commission'],
                'agency_commission'   => $calc['agency_commission'],
                'gateway_payout_fee'  => $calc['gateway_payout_fee'],
                'net_amount'          => $calc['net_seller'],
                'gross_seller_amount' => $calc['gross_seller'],
                'net_agency_amount'   => $calc['net_agency'],
                'gross_agency_amount' => $calc['gross_agency'],
                'deposit_code'        => strtoupper(Str::random(8)),
                'financial_snapshot'  => $calc,
            ]);

            foreach ($shopItems as $item) {
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

                $this->checkStockAndNotify($item->variant ?? $item->product, $item->product);
            }

            $allShippingIncluded = $shopItems->every(fn($item) => (bool) $item->product->shipping_included);

            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => $allShippingIncluded,
                'recipient_name'    => $buyer->name,
                'recipient_phone'   => $buyer->phone,
                'destination_city'  => $data['destination_city'],
            ]);
        }

        OrderGroupPayment::create([
            'order_group_id'  => $group->id,
            'method'          => 'elgiopay',
            'status'          => 'pending',
            'idempotency_key' => OrderGroupPayment::generateIdempotencyKey(),
            'payer_phone'     => $data['payer_phone'],
            'payer_operator'  => $data['payer_operator'],
        ]);

        return $group;
    }

    // ── ANNULER ───────────────────────────────────────────────────────
    public function cancel(Order $order, string $reason): void
    {


        abort_unless($order->status === Order::STATUS_AWAITING_PAYMENT || Order::STATUS_PENDING, 403, "le colis est deja en preparation vous ne pouvez plus annuler");
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

    private function checkStockAndNotify(Product|ProductVariant $stockable, Product $product): void
    {
        $stockable->refresh();

        if ($stockable->availableStock() <= 0) {
            $product->shop->user->notify(
                new ProductOutOfStockNotification($product, $stockable instanceof ProductVariant ? $stockable : null)
            );
        }
    }
}
