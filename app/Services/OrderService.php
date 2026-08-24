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
    public function __construct(private CampayService $campay) {}

    // ── CALCUL FINANCIER CENTRAL ──────────────────────────────────────

    // Tout le calcul financier est ici — appelé à la création de chaque commande.
    // Toutes les valeurs sont lues depuis platform_settings (cachées Redis).
    // Jamais de valeurs en dur dans le code.
    //
    // RÉSULTAT GARANTI :
    //   - Vendeur reçoit exactement   : subtotal × (1 - commission_rate)
    //   - Agence reçoit exactement    : subtotal × agency_rate
    //   - Acheteur paie               : Gross-Up de (subtotal + protection)
    //   - Frais Campay payout         : supportés par la plateforme
    //   - Frais Campay collect        : supportés par l'acheteur via Gross-Up
    public function calculateFinancials(int $subtotal): array
    {
        // ── 1. Lire tous les taux depuis platform_settings ────────────

        // Commission plateforme (ex: 5%) — peut être mise à 0% par l'admin
        $commissionRate = PlatformSetting::getRate('platform_commission_rate');

        // Commission agence (ex: 1%)
        $agencyRate     = PlatformSetting::getRate('agency_commission_rate');

        // Frais de protection acheteur (ex: 2%)
        $protectionRate = PlatformSetting::getRate('protection_rate');

        // ── 2. Calcul côté vendeur ────────────────────────────────────

        // Commission plateforme déduite du subtotal
        // Ex: 10000 × 5% = 500 FCFA
        $platformCommission = (int) round($subtotal * $commissionRate);

        // Le vendeur DOIT recevoir exactement ce montant net sur son téléphone
        // Ex: 10000 − 500 = 9500 FCFA
        $netSeller = $subtotal - $platformCommission;

        // Gross-Up vendeur : combien envoyer à Campay pour que le vendeur
        // reçoive exactement $netSeller après déduction des frais Campay payout
        $payoutGrossUp = $this->campay->grossUpPayout($netSeller);

        // Ce qu'on envoie à Campay pour le vendeur
        // Ex: ceil(9500 / 0.99) = 9596 FCFA
        $grossSeller = $payoutGrossUp['gross'];

        // Frais Campay payout supportés par la plateforme
        // Ex: 9596 - 9500 = 96 FCFA
        $campayPayoutFee = $payoutGrossUp['fee'];

        // ── 3. Calcul côté agence ─────────────────────────────────────

        // Commission agence — déduite du subtotal (pas du net vendeur)
        // Ex: 10000 × 1% = 100 FCFA
        $agencyCommission = (int) round($subtotal * $agencyRate);

        // L'agence DOIT recevoir exactement $agencyCommission sur son téléphone
        $agencyGrossUp = $this->campay->grossUpPayout($agencyCommission);

        // Ce qu'on envoie à Campay pour l'agence
        // Ex: ceil(100 / 0.99) = 102 FCFA
        $grossAgency = $agencyGrossUp['gross'];

        // Frais Campay payout agence supportés par la plateforme
        $campayAgencyFee = $agencyGrossUp['fee'];

        // ── 4. Calcul côté acheteur ───────────────────────────────────

        // Frais de protection
        // Ex: 10000 × 2% = 200 FCFA
        $protectionFee = (int) round($subtotal * $protectionRate);

        // Montant que la plateforme veut recevoir nets après déduction Campay collect
        // = prix du produit + frais de protection
        // Ex: 10000 + 200 = 10200 FCFA
        $platformWantsToReceive = $subtotal + $protectionFee;

        // Gross-Up collect : l'acheteur paie ce montant à Campay
        // Campay déduit ses frais → la plateforme reçoit $platformWantsToReceive nets
        $collectGrossUp = $this->campay->grossUpCollect($platformWantsToReceive);

        // Total que l'acheteur paie réellement
        // Ex: ceil(10200 / 0.98) = 10409 FCFA
        $totalAmount = $collectGrossUp['gross'];

        // Frais Campay collect — supportés par l'acheteur (inclus dans total_amount)
        // Ex: 10409 - 10200 = 209 FCFA
        $campayCollectFee = $collectGrossUp['fee'];

        // ── 5. Marge nette Ali-Kamer ──────────────────────────────────

        // La plateforme encaisse : protection + commission
        // La plateforme décaisse : frais Campay payout vendeur + frais Campay payout agence
        // Note : les frais Campay collect sont payés par l'acheteur, pas par la plateforme
        $platformNet = $protectionFee + $platformCommission
            - $campayPayoutFee
            - $campayAgencyFee;

        // ── 6. Retourner tous les montants ────────────────────────────

        return [
            // Côté acheteur
            'subtotal'            => $subtotal,
            'protection_fee'      => $protectionFee,
            'campay_collect_fee'  => $campayCollectFee,
            'total_amount'        => $totalAmount,       // Ce que l'acheteur paie

            // Côté vendeur
            'platform_commission' => $platformCommission,
            'net_seller'          => $netSeller,         // Reçu exactement sur son téléphone
            'gross_seller'        => $grossSeller,        // Envoyé à Campay

            // Côté agence
            'agency_commission'   => $agencyCommission,
            'net_agency'          => $agencyCommission,  // Reçu exactement sur son téléphone
            'gross_agency'        => $grossAgency,        // Envoyé à Campay

            // Frais Campay
            'campay_payout_fee'   => $campayPayoutFee,   // Supportés par la plateforme
            'campay_agency_fee'   => $campayAgencyFee,   // Supportés par la plateforme
            'campay_collect_fee_detail' => $campayCollectFee, // Supportés par l'acheteur

            // Marge Ali-Kamer
            'platform_net'        => $platformNet,

            // Snapshot des taux appliqués — immuable
            'rates' => [
                'commission_rate'     => $commissionRate,
                'agency_rate'         => $agencyRate,
                'protection_rate'     => $protectionRate,
                'campay_collect_rate' => PlatformSetting::getRate('campay_collect_rate'),
                'campay_payout_rate'  => PlatformSetting::getRate('campay_payout_rate'),
                'campay_fixed_fee'    => PlatformSetting::getValue('campay_fixed_fee', 0),
                'calculated_at'       => now()->toISOString(),
            ],
        ];
    }

    // ── CRÉER UNE COMMANDE ────────────────────────────────────────────

    public function create(User $buyer, array $data): Order
    {
        return DB::transaction(function () use ($buyer, $data) {

            $product  = Product::findOrFail($data['product_id']);
            $quantity = (int) $data['quantity'];

            // Variante si sélectionnée
            $variant = isset($data['variant_id'])
                ? ProductVariant::findOrFail($data['variant_id'])
                : null;

            // Vérifier le stock
            $availableStock = $variant
                ? $variant->availableStock()
                : $product->availableStock();

            if ($availableStock < $quantity) {
                throw new \Exception(
                    'Stock insuffisant. Disponible : ' . $availableStock
                );
            }

            // Prix unitaire
            $unitPrice = $variant ? $variant->price : $product->price;
            $subtotal  = $unitPrice * $quantity;

            // Calcul financier avec Gross-Up
            $fin = $this->calculateFinancials($subtotal);

            // Créer la commande
            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $product->shop_id,
                'status'              => Order::STATUS_AWAITING_PAYMENT,

                // Montants
                'subtotal'            => $fin['subtotal'],
                'protection_fee'      => $fin['protection_fee'],
                'shipping_fee'        => 0,

                // gateway_fee = frais Campay collect supportés par l'acheteur
                'gateway_fee'         => $fin['campay_collect_fee'],

                // total_amount = ce que l'acheteur paie réellement (Gross-Up inclus)
                'total_amount'        => $fin['total_amount'],

                // Commissions
                'platform_commission' => $fin['platform_commission'],
                'agency_commission'   => $fin['agency_commission'],

                // Frais Campay payout (supportés par la plateforme)
                'gateway_payout_fee'  => $fin['campay_payout_fee'],

                // net_amount = ce que le vendeur reçoit EXACTEMENT sur son téléphone
                'net_amount'          => $fin['net_seller'],

                // Colonnes Gross-Up
                'gross_seller_amount' => $fin['gross_seller'],
                'net_agency_amount'   => $fin['net_agency'],
                'gross_agency_amount' => $fin['gross_agency'],

                // Deposit code pour le secrétaire
                'deposit_code'        => strtoupper(Str::random(8)),

                // Snapshot financier immuable — taux figés au moment de la commande
                'financial_snapshot'  => $fin,

                'buyer_note' => $data['note'] ?? null,
            ]);

            // Lignes de commande
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

            // Expédition
            OrderShipment::create([
                'order_id'          => $order->id,
                'type'              => 'interurban',
                'shipping_included' => $product->shipping_included,
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

            // Calcul financier avec Gross-Up
            $fin = $this->calculateFinancials($subtotal);

            $order = Order::create([
                'reference'           => Order::generateReference(),
                'buyer_id'            => $buyer->id,
                'shop_id'             => $shopId,
                'status'              => Order::STATUS_AWAITING_PAYMENT,
                'subtotal'            => $fin['subtotal'],
                'protection_fee'      => $fin['protection_fee'],
                'shipping_fee'        => 0,
                'gateway_fee'         => $fin['campay_collect_fee'],
                'total_amount'        => $fin['total_amount'],
                'platform_commission' => $fin['platform_commission'],
                'agency_commission'   => $fin['agency_commission'],
                'gateway_payout_fee'  => $fin['campay_payout_fee'],
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

            // Vrai uniquement si TOUS les produits de la commande ont le transport inclus
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
                'method'          => 'campay',
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
