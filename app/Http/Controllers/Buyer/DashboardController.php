<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Statistiques
        $stats = [
            'active_orders'    => Order::where('buyer_id', $user->id)
                                    ->whereIn('status', [
                                        Order::STATUS_PENDING,
                                        Order::STATUS_AWAITING_PAYMENT,
                                        Order::STATUS_PAID,
                                        Order::STATUS_PREPARING,
                                        Order::STATUS_REGISTERED_ORIGIN,
                                        Order::STATUS_IN_TRANSIT,
                                        Order::STATUS_ARRIVED_DESTINATION,
                                        Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                                    ])->count(),

            'completed_orders' => Order::where('buyer_id', $user->id)
                                    ->whereIn('status', [
                                        Order::STATUS_COMPLETED,
                                        Order::STATUS_AUTO_COMPLETED,
                                    ])->count(),

            'disputed_orders'  => Order::where('buyer_id', $user->id)
                                    ->where('status', Order::STATUS_DISPUTED)
                                    ->count(),

            'total_spent'      => Order::where('buyer_id', $user->id)
                                    ->whereIn('status', [
                                        Order::STATUS_COMPLETED,
                                        Order::STATUS_AUTO_COMPLETED,
                                    ])->sum('total_amount'),
        ];

        // 2. Dernières commandes (5 max)
        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['shop', 'items.product', 'payment', 'shipment'])
            ->latest()
            ->take(5)
            ->get();

        // 3. La commande active à suivre en priorité (si elle existe)
        $activeOrder = Order::where('buyer_id', $user->id)
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PREPARING,
                Order::STATUS_REGISTERED_ORIGIN,
                Order::STATUS_IN_TRANSIT,
                Order::STATUS_ARRIVED_DESTINATION,
                Order::STATUS_AWAITING_BUYER_CONFIRMATION,
            ])
            ->with(['shop', 'items.product', 'shipment.destinationCounter'])
            ->latest()
            ->first();

        return view('buyer.dashboard', compact('stats', 'recentOrders', 'activeOrder'));
    }
}