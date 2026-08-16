<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\AgencyService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService, private AgencyService $agencyService) {}

    public function index()
    {
        $orders = Order::where('buyer_id', Auth::user()->id)
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

        // Récupérer les villes actives desservies par au moins une agence active
        $cities = $this->agencyService->getActiveCities();

        return view('buyer.orders.create', compact('product', 'cities'));
    }

    // Passer la commande
    public function store(OrderRequest $request)
    {

        $order = $this->orderService->create(Auth::user(), $request->validated());

        // Vérifier que c'est bien la commande de cet acheteur
        abort_unless($order->buyer_id === Auth::user()->id, 403);

        // Vérifier que le paiement est bien en attente
        abort_unless(
            in_array($order->status, ['pending', 'awaiting_payment', 'failed']),
            404
        );

        return redirect()->route('buyer.payment.initiate', $order);
    }

    public function show(Order $order)
    {
        abort_unless($order->buyer_id === Auth::user()->id, 403);
        $order->load(['items.product', 'payment', 'shipment', 'shop']);
        return view('buyer.orders.show', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        abort_unless($order->buyer_id === Auth::user()->id, 403);
        abort_unless(in_array($order->status, ['pending', 'awaiting_payment']), 403);

        $this->orderService->cancel($order, 'Annulé par l\'acheteur.');

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Commande annulée.');
    }
    // Page paiement frais transport
    public function transportPayment(Order $order)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);

        // Vérifier que des frais transport sont bien dus
        abort_unless(
            ! $order->shipment->shipping_included &&
                $order->shipment->transport_fee > 0 &&
                ! $order->shipment->transport_fee_paid,
            404
        );

        return view('buyer.orders.transport-payment', compact('order'));
    }


    // Initier le paiement Campay pour les frais transport
    public function payTransport(Request $request, Order $order, PaymentService $paymentService)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);
        abort_unless(
            ! $order->shipment->transport_fee_paid &&
                $order->shipment->transport_fee > 0,
            403
        );

        $request->validate([
            'transport_phone'    => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'transport_operator' => ['required', 'in:mtn,orange'],
        ]);

        $paymentService->initiateTransportPayment(
            $order,
            $request->transport_phone,
            $request->transport_operator
        );

        return redirect()->route('buyer.orders.transport.waiting', $order)
            ->with('success', 'Vérifiez votre téléphone pour confirmer le paiement du transport.');
    }

    // Page d'attente dédiée au paiement transport
    public function transportWaiting(Order $order)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);
        return view('buyer.orders.transport-waiting', compact('order'));
    }

    // Vérification statut paiement transport (polling JS)
    public function transportStatus(Order $order, PaymentService $paymentService)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);

        $shipment = $order->shipment;

        // Si le transport n'est pas encore marqué comme payé, on tente une synchronisation active
        if ($shipment && ! $shipment->transport_fee_paid) {
            $paymentService->synchronizeTransportPayment($order);
            $shipment->refresh();
        }

        return response()->json([
            // true si transport payé
            'paid' => (bool) $shipment->transport_fee_paid,

            // OTP disponible si transport payé et OTP généré
            'otp_ready' => $shipment->transport_fee_paid && $order->otp_code !== null,
        ]);
    }
}
