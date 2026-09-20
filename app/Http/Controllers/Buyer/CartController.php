<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use App\Services\ProductVariantService;
use App\Services\AgencyService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private AgencyService $agencyService,
        private OrderService $orderService,
    ) {}

    // Afficher le panier
    public function index()
    {
        $cart  = $this->cartService->getOrCreate(Auth::user());
        $cart->load('items.product.images', 'items.variant.attributeValues.attribute');

        return view('buyer.cart.index', compact('cart'));
    }

    // Ajouter au panier
    public function add(Request $request, Product $product)
    {
        $quantity = 1;
        $request->validate([
            'quantity'   => ['nullable', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $variant = null;
        if ($request->variant_id) {
            $variant = $product->activeVariants()->findOrFail($request->variant_id);
        } elseif ($product->hasVariants()) {
            return back()->withErrors(['variant' => 'Veuillez choisir une variante.']);
        }

        $this->cartService->addItem(
            Auth::user(),
            $product,
            $request->quantity ?? $quantity,
            $variant
        );

        return back()->with('success', 'Article ajouté au panier.');
    }

    // Mettre à jour la quantité
    public function update(Request $request, CartItem $item)
    {
        abort_unless($item->cart->user_id === Auth::id(), 403);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateQuantity($item, $request->quantity);

        return back()->with('success', 'Panier mis à jour.');
    }

    // Supprimer un article
    public function remove(CartItem $item)
    {
        abort_unless($item->cart->user_id === Auth::id(), 403);

        $this->cartService->removeItem($item);

        return back()->with('success', 'Article retiré du panier.');
    }

    // Page checkout — passer la commande depuis le panier
    public function checkout()
    {
        $cart = $this->cartService->getOrCreate(Auth::user());
        $cart->load('items.product.shop', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Regrouper les articles par boutique pour l'affichage du récapitulatif
        $itemsByShop = $cart->items->groupBy(fn($item) => $item->product->shop_id);

        $cities = $this->agencyService->getActiveCities();

        return view('buyer.cart.checkout', compact('cart', 'itemsByShop', 'cities'));
    }

    // Confirmer la commande depuis le panier
    public function confirmOrder(Request $request)
    {
        $validated = $request->validate([
            'destination_city' => ['required', 'string'],
            'payer_phone'      => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'payer_operator'   => ['required', 'in:mtn,orange'],
        ]);

        $cart = $this->cartService->getOrCreate(Auth::user());
        $cart->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('buyer.cart.index');
        }

        $result = $this->orderService->createFromCart(Auth::user(), $cart, $validated);

        $this->cartService->clear(Auth::user());

        return $result instanceof \App\Models\OrderGroup
            ? redirect()->route('buyer.payment.group.initiate', $result)
            : redirect()->route('buyer.payment.initiate', $result);
    }
}
