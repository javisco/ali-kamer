<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyCounter;
use App\Models\Order;
use App\Services\AgencyService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // Agences qui desservent la ville de destination
        $agencies = collect();
        if ($order->status === 'paid' && $order->shipment) {
            $agencies = app(AgencyService::class)
                ->getAgenciesServingCity($order->shipment->destination_city);
        }
        //dd($agencies->toArray());
        return view('seller.orders.show', compact('order', 'agencies'));
    }

    public function prepare(Request $request, Order $order)
    {
        abort_unless(
            $order->shop_id === auth()->user()->shop->id,
            403
        );

        abort_unless(
            $order->status === Order::STATUS_PAID,
            403
        );

        $request->validate([
            'agency_id'  => ['required', 'exists:agencies,id'],
            'counter_id' => ['required', 'exists:agency_counters,id'],
        ]);

        // Vérifier le comptoir
        $counter = AgencyCounter::where('id', $request->counter_id)
            ->where('agency_id', $request->agency_id)
            ->where('is_active', true)
            ->firstOrFail();

        // Vérifier l'agence
        $agency = Agency::findOrFail($request->agency_id);

        abort_unless(
            $agency->servesCity($order->shipment->destination_city),
            422,
            "Cette agence ne dessert pas {$order->shipment->destination_city}."
        );

        DB::transaction(function () use ($order, $agency, $counter) {

            // Générer le code de dépôt
            $depositCode = strtoupper(Str::random(8));

            // Enregistrer l'agence et le comptoir
            $order->shipment->update([
                'agency_id'         => $agency->id,
                'origin_counter_id' => $counter->id,
            ]);

            // Passer la commande en préparation
            $order->update([
                'status'       => Order::STATUS_PREPARING,
                'preparing_at' => now(),
                'deposit_code' => $depositCode,
            ]);
        });

        return back()->with(
            'success',
            "Commande en préparation. Déposez le colis à {$counter->full_name}."
        );
    }
}
