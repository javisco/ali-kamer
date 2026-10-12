<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shop;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderRepurchaseService;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class OrderRepurchaseTest extends TestCase
{
    public function test_order_eligible_for_repurchase_statuses(): void
    {
        $eligibleStatuses = [
            Order::STATUS_CANCELLED,
            Order::STATUS_FAILED,
            Order::STATUS_COMPLETED,
            Order::STATUS_AUTO_COMPLETED,
        ];

        foreach ($eligibleStatuses as $status) {
            $order = new Order(['status' => $status]);
            $this->assertTrue(
                $order->isEligibleForRepurchase(),
                "Le statut '{$status}' devrait être éligible au rachat."
            );
        }

        $ineligibleStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_AWAITING_PAYMENT,
            Order::STATUS_PAID,
            Order::STATUS_PREPARING,
            Order::STATUS_IN_TRANSIT,
            Order::STATUS_ARRIVED_DESTINATION,
            Order::STATUS_DISPUTED,
        ];

        foreach ($ineligibleStatuses as $status) {
            $order = new Order(['status' => $status]);
            $this->assertFalse(
                $order->isEligibleForRepurchase(),
                "Le statut '{$status}' ne devrait PAS être éligible au rachat."
            );
        }
    }

    public function test_prepare_rejects_ineligible_order(): void
    {
        $cartMock = $this->createMock(CartService::class);
        $service = new OrderRepurchaseService($cartMock);

        $order = new Order(['status' => Order::STATUS_IN_TRANSIT]);

        $this->expectException(\InvalidArgumentException::class);
        $service->prepare($order);
    }

    public function test_prepare_evaluates_availability_price_changes_and_stock(): void
    {
        $cartMock = $this->createMock(CartService::class);
        $service = new OrderRepurchaseService($cartMock);

        $seller = new User(['id' => 20, 'status' => User::STATUS_ACTIVE]);
        $shop = new Shop(['id' => 10, 'status' => Shop::STATUS_ACTIVE]);
        $shop->setRelation('user', $seller);

        // Produit 1 : Toujours en stock, mais le prix a augmenté de 5 000 FCFA
        $prod1 = new Product([
            'id'             => 101,
            'status'         => Product::STATUS_VISIBLE,
            'price'          => 20000, // Nouveau prix
            'stock'          => 10,
            'stock_reserved' => 0,
        ]);
        $prod1->setRelation('shop', $shop);
        $prod1->setRelation('variants', collect());

        $item1 = new OrderItem([
            'product_id'    => 101,
            'product_title' => 'Produit A',
            'product_image' => 'products/item1_snapshot.jpg',
            'quantity'      => 2,
            'unit_price'    => 15000, // Ancien prix
        ]);
        $item1->id = 1;
        $item1->setRelation('product', $prod1);
        $item1->setRelation('variant', null);

        // Produit 2 : Stock partiel (demandé: 5, dispo: 2)
        $prod2 = new Product([
            'id'             => 102,
            'status'         => Product::STATUS_VISIBLE,
            'price'          => 5000,
            'stock'          => 2,
            'stock_reserved' => 0,
        ]);
        $prod2->setRelation('shop', $shop);
        $prod2->setRelation('variants', collect());

        $item2 = new OrderItem([
            'product_id'    => 102,
            'product_title' => 'Produit B',
            'product_image' => 'products/item2_snapshot.jpg',
            'quantity'      => 5,
            'unit_price'    => 5000,
        ]);
        $item2->id = 2;
        $item2->setRelation('product', $prod2);
        $item2->setRelation('variant', null);

        // Produit 3 : Rupture totale
        $prod3 = new Product([
            'id'             => 103,
            'status'         => Product::STATUS_VISIBLE,
            'price'          => 8000,
            'stock'          => 0,
            'stock_reserved' => 0,
        ]);
        $prod3->setRelation('shop', $shop);
        $prod3->setRelation('variants', collect());

        $item3 = new OrderItem([
            'product_id'    => 103,
            'product_title' => 'Produit C',
            'quantity'      => 1,
            'unit_price'    => 8000,
        ]);
        $item3->id = 3;
        $item3->setRelation('product', $prod3);
        $item3->setRelation('variant', null);

        $order = new Order([
            'id'       => 50,
            'buyer_id' => 99,
            'status'   => Order::STATUS_COMPLETED,
        ]);
        $order->setRelation('shop', $shop);
        $order->setRelation('items', collect([$item1, $item2, $item3]));

        $prepared = $service->prepare($order);

        $this->assertEquals(2, $prepared['available_count']);
        $this->assertEquals(1, $prepared['unavailable_count']);
        $this->assertTrue($prepared['has_price_changes']);
        $this->assertTrue($prepared['can_reorder_any']);

        $items = collect($prepared['items'])->keyBy('order_item_id');

        // Vérification Item 1 (Prix actualisé)
        $eval1 = $items->get(1);
        $this->assertTrue($eval1['can_reorder']);
        $this->assertEquals('available', $eval1['stock_status']);
        $this->assertEquals(20000, $eval1['current_unit_price']);
        $this->assertEquals(5000, $eval1['price_difference']);
        $this->assertEquals('products/item1_snapshot.jpg', $eval1['product_image']);

        // Vérification Item 2 (Stock partiel)
        $eval2 = $items->get(2);
        $this->assertTrue($eval2['can_reorder']);
        $this->assertEquals('partial_stock', $eval2['stock_status']);
        $this->assertEquals(2, $eval2['available_qty']);

        // Vérification Item 3 (Rupture de stock)
        $eval3 = $items->get(3);
        $this->assertFalse($eval3['can_reorder']);
        $this->assertEquals('out_of_stock', $eval3['stock_status']);
    }
}
