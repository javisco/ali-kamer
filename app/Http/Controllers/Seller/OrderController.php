<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $orders = Order::where('shop_id', auth()->user()->shop->id)
            ->with(['buyer', 'items', 'payment', 'shipment'])
            ->latest()
            ->paginate(20);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->shop_id === auth()->user()->shop->id, 403);
        $order->load(['buyer', 'items.product', 'payment', 'shipment']);
        return view('seller.orders.show', compact('order'));
    }

    public function markPreparing(Order $order)
    {
        abort_unless($order->shop_id === auth()->user()->shop->id, 403);
        abort_unless($order->status === Order::STATUS_PAID, 403);

        $this->orderService->markPreparing($order);

        return back()->with('success', 'Commande marquée en préparation.');
    }
}