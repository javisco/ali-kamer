<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with(['shop', 'items', 'payment'])
            ->latest()
            ->paginate(20);

        return view('buyer.orders.index', compact('orders'));
    }

    // Formulaire de commande
    public function create(Product $product)
    {
        abort_unless($product->isVisible(), 404);
        abort_unless($product->availableStock() > 0, 404);

        return view('buyer.orders.create', compact('product'));
    }

    // Passer la commande
    public function store(OrderRequest $request)
    {

        $order = $this->orderService->create(auth()->user(), $request->validated());

        // Vérifier que c'est bien la commande de cet acheteur
        abort_unless($order->buyer_id === auth()->id(), 403);

        // Vérifier que le paiement est bien en attente
        abort_unless(
            in_array($order->status, ['pending', 'awaiting_payment', 'failed']),
            404
        );

        return redirect()->route('buyer.payment.initiate', $order);
    }

    public function show(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        $order->load(['items.product', 'payment', 'shipment', 'shop']);
        return view('buyer.orders.show', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['pending', 'awaiting_payment']), 403);

        $this->orderService->cancel($order, 'Annulé par l\'acheteur.');

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Commande annulée.');
    }
}
