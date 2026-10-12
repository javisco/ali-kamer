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

        $qty = (int) ($request->quantity ?? $quantity);

        if (Auth::check()) {
            $this->cartService->addItem(
                Auth::user(),
                $product,
                $qty,
                $variant
            );
        } else {
            // Panier en session invité
            $sessionCart = session()->get('guest_cart', []);
            $key = $product->id . '_' . ($variant?->id ?? '0');
            if (isset($sessionCart[$key])) {
                $sessionCart[$key]['quantity'] += $qty;
            } else {
                $sessionCart[$key] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'quantity'   => $qty,
                ];
            }
            session()->put('guest_cart', $sessionCart);
        }

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

    // Basculer la sélection d'un article
    public function toggleItem(Request $request, CartItem $item)
    {
        abort_unless($item->cart->user_id === Auth::id(), 403);

        $selected = $request->has('selected') ? $request->boolean('selected') : null;
        $newVal = $this->cartService->toggleSelection($item, $selected);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'        => true,
                'is_selected'    => $newVal,
                'selected_total' => $this->cartService->selectedTotal(Auth::user()),
                'selected_count' => $this->cartService->selectedCount(Auth::user()),
            ]);
        }

        return back();
    }

    // Sélectionner ou désélectionner tout
    public function selectAll(Request $request)
    {
        $selected = $request->boolean('selected', true);
        $this->cartService->selectAll(Auth::user(), $selected);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'        => true,
                'selected'       => $selected,
                'selected_total' => $this->cartService->selectedTotal(Auth::user()),
                'selected_count' => $this->cartService->selectedCount(Auth::user()),
            ]);
        }

        return back();
    }

    // Page checkout — passer la commande depuis le panier
    public function checkout()
    {
        $user = Auth::user();
        $cart = $this->cartService->getOrCreate($user);
        $selectedItems = $this->cartService->getSelectedItems($user);

        if ($selectedItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Veuillez sélectionner au moins un article pour passer commande.');
        }

        // Regrouper les articles sélectionnés par boutique pour l'affichage du récapitulatif
        $itemsByShop = $selectedItems->groupBy(fn($item) => $item->product->shop_id);

        $cities = $this->agencyService->getActiveCities();

        return view('buyer.cart.checkout', compact('cart', 'selectedItems', 'itemsByShop', 'cities'));
    }

    // Confirmer la commande depuis le panier
    public function confirmOrder(Request $request)
    {
        $validated = $request->validate([
            'destination_city' => ['required', 'string'],
            'payer_phone'      => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'payer_operator'   => ['required', 'in:mtn,orange'],
        ]);

        $user = Auth::user();
        $selectedCount = $this->cartService->selectedCount($user);

        if ($selectedCount === 0) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Veuillez sélectionner au moins un article pour passer commande.');
        }

        $cart = $this->cartService->getOrCreate($user);

        $result = $this->orderService->createFromCart($user, $cart, $validated);

        // Panier persistant : nous NE vidons PAS le panier.
        // Les articles commandés restent dans le panier jusqu'à suppression manuelle.

        return $result instanceof \App\Models\OrderGroup
            ? redirect()->route('buyer.payment.group.initiate', $result)
            : redirect()->route('buyer.payment.initiate', $result);
    }
}
