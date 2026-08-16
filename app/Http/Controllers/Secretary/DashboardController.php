<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private ShippingService $shippingService) {}

    // ── Dashboard principal ───────────────────────────────────────────

    public function index()
    {
        $secretary = auth()->user();

        try {
            // Comptoir du secrétaire
            $counter = $this->shippingService->getSecretaryCounterPublic($secretary);

            // Stats rapides
            $stats = [
                'pending_arrivals' => $this->shippingService->getPendingArrivals($secretary)->count(),
                'pending_handovers' => $this->shippingService->getPendingHandovers($secretary)->count(),
            ];
        } catch (\Exception $e) {
            $counter = null;
            $stats   = ['pending_arrivals' => 0, 'pending_handovers' => 0];
        }

        return view('secretary.dashboard', compact('secretary', 'counter', 'stats'));
    }

    // ── PAGE 1 : Dépôt ───────────────────────────────────────────────

    public function depositPage()
    {
        return view('secretary.deposit.index');
    }

    // Rechercher la commande par deposit_code
    public function searchDeposit(Request $request)
    {
        $request->validate([
            'deposit_code' => ['required', 'string', 'size:8'],
        ]);

        try {
            $order = $this->shippingService->findByDepositCode(
                auth()->user(),
                $request->deposit_code
            );
        } catch (\Exception $e) {
            return back()->withErrors(['deposit_code' => $e->getMessage()]);
        }

        return redirect()->route('found', ['order' => $order]);
        //return view('secretary.deposit.found', compact('order'));
    }
    public function found(Order $order)
    {
        return view('secretary.deposit.found', compact('order'));
    }

    // Valider le dépôt
    public function registerDeposit(Request $request, Order $order)
    {
        $request->validate([
            'transport_fee' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        try {
            $this->shippingService->registerAtOrigin(
                auth()->user(),
                $order,
                $request->transport_fee ?? 0
            );
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('secretary.deposit.page')
            ->with(
                'success',
                "Colis enregistré — Commande {$order->reference}. " .
                    "Destination : {$order->shipment->destination_city}."
            );
    }

    // ── PAGE 2 : Arrivées ─────────────────────────────────────────────

    public function arrivalsPage(Request $request)
    {
        $orders = collect();
        $searched = null;

        try {
            if ($request->filled('ref')) {
                // Recherche par référence
                $searched = $this->shippingService->findByReference(
                    auth()->user(),
                    $request->ref
                );
            } else {
                // Liste complète
                $orders = $this->shippingService->getPendingArrivals(auth()->user());
            }
        } catch (\Exception $e) {
            return back()->withErrors(['ref' => $e->getMessage()]);
        }

        return view('secretary.arrivals.index', compact('orders', 'searched'));
    }

    // Valider l'arrivée
    public function validateArrival(Request $request, Order $order)
    {
        try {
            $this->shippingService->validateArrival(auth()->user(), $order);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        $msg = $order->shipment->shipping_included || $order->shipment->transport_fee_paid
            ? "Arrivée validée — OTP envoyé à l'acheteur."
            : "Arrivée validée — L'acheteur doit payer les frais transport avant de recevoir son OTP.";

        return redirect()->route('secretary.arrivals.page')
            ->with('success', $msg);
    }

    // ── PAGE 3 : Remises OTP ──────────────────────────────────────────

    public function handoverPage()
    {
        try {
            $orders = $this->shippingService->getPendingHandovers(auth()->user());
        } catch (\Exception $e) {
            $orders = collect();
        }

        return view('secretary.handover.index', compact('orders'));
    }

    // Valider l'OTP et remettre le colis
    public function validateOtp(Request $request, Order $order)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ]);

        try {
            $this->shippingService->validateOtp(
                auth()->user(),
                $order,
                $request->otp
            );
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }

        return redirect()->route('secretary.handover.page')
            ->with('success', "Colis remis — Commande {$order->reference} terminée. Vendeur payé.");
    }
}
