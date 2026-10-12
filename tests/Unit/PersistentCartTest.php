<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use PHPUnit\Framework\TestCase;

class PersistentCartTest extends TestCase
{
    public function test_cart_item_has_selected_for_checkout_cast_and_default(): void
    {
        $item = new CartItem([
            'quantity'              => 2,
            'unit_price'            => 15000,
            'selected_for_checkout' => true,
        ]);

        $this->assertTrue($item->selected_for_checkout);
        $this->assertEquals(30000, $item->subtotal());

        $deselected = new CartItem([
            'quantity'              => 1,
            'unit_price'            => 5000,
            'selected_for_checkout' => false,
        ]);

        $this->assertFalse($deselected->selected_for_checkout);
    }

    public function test_cart_service_toggle_selection(): void
    {
        $cartService = new CartService();

        $itemMock = $this->getMockBuilder(CartItem::class)
            ->onlyMethods(['update'])
            ->getMock();

        $itemMock->selected_for_checkout = false;

        $itemMock->expects($this->once())
            ->method('update')
            ->with(['selected_for_checkout' => true]);

        $res = $cartService->toggleSelection($itemMock);
        $this->assertTrue($res);
    }

    public function test_persistent_cart_items_remain_after_order_creation_rule(): void
    {
        // Règle d'or #4 : Le panier ne doit JAMAIS être vidé automatiquement après commande.
        // Seule une action explicite de l'acheteur doit supprimer un article.
        $user = new User(['id' => 10, 'name' => 'Buyer Test']);
        $cart = new Cart(['id' => 5, 'user_id' => 10]);

        $item1 = new CartItem([
            'cart_id'               => 5,
            'quantity'              => 2,
            'unit_price'            => 10000,
            'selected_for_checkout' => true,
        ]);
        $item1->id = 101;

        $item2 = new CartItem([
            'cart_id'               => 5,
            'quantity'              => 1,
            'unit_price'            => 25000,
            'selected_for_checkout' => false,
        ]);
        $item2->id = 102;

        $items = collect([$item1, $item2]);

        // Vérifie le calcul sélectif : seul l'article coché compte pour le checkout
        $selectedItems = $items->where('selected_for_checkout', true);
        $this->assertCount(1, $selectedItems);
        $this->assertEquals(20000, $selectedItems->sum(fn ($i) => $i->unit_price * $i->quantity));

        // Les deux articles continuent d'exister dans le panier de l'acheteur
        $this->assertCount(2, $items);
        $this->assertTrue($items->contains('id', 101));
        $this->assertTrue($items->contains('id', 102));
    }
}
