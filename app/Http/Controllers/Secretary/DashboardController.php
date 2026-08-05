<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private ShippingService $shippingService) {}

    // Dashboard principal du secrétaire
    // Affiche les colis en attente selon la ville du guichet
    public function index()
    {
        $secretary = auth()->user();

        // Récupérer le guichet principal du secrétaire
        $counter = $secretary->assignedCounters()
            ->wherePivot('is_primary', true)
            ->with('agency')
            ->first();

        // Colis en attente de dépôt (commandes en statut preparing)
        // Filtrées par ville du guichet
        $pendingDeposit = collect();

        // Colis arrivés à valider (commandes en transit vers ce guichet)
        $pendingArrival = collect();

        if ($counter) {
            // Colis à enregistrer au départ
            $pendingDeposit = Order::where('status', Order::STATUS_PREPARING)
                ->whereHas(
                    'shipment',
                    fn($q) =>
                    $q->where('destination_city', $counter->city)
                        ->orWhere('destination_city', '!=', $counter->city)
                )
                ->with(['shop', 'shipment', 'buyer'])
                ->latest()
                ->get();

            // Colis arrivés à ce guichet à valider
            $pendingArrival = Order::whereIn('status', [
                Order::STATUS_REGISTERED_ORIGIN,
                Order::STATUS_IN_TRANSIT,
            ])
                ->whereHas(
                    'shipment',
                    fn($q) =>
                    $q->where('destination_city', $counter->city)
                )
                ->with(['shop', 'shipment', 'buyer'])
                ->latest()
                ->get();
        }

        return view('secretary.dashboard', compact(
            'secretary',
            'counter',
            'pendingDeposit',
            'pendingArrival'
        ));
    }

    // Enregistrer un colis au départ via le code de dépôt
    public function registerDeposit(Request $request)
    {
        $request->validate([
            // Code de dépôt unique généré à la création de la commande
            'deposit_code' => ['required', 'string', 'size:8'],
        ]);

        $order = $this->shippingService->registerByDepositCode(
            auth()->user(),
            strtoupper($request->deposit_code)
        );

        return redirect()->route('secretary.dashboard')
            ->with(
                'success',
                "Colis enregistré — Commande {$order->reference} pour {$order->shipment->destination_city}."
            );
    }

    // Valider l'arrivée d'un colis
    public function validateArrival(Request $request, Order $order)
    {
        $request->validate([
            // Frais de transport saisis par le secrétaire
            // Payés en main propre par le vendeur — pas via la plateforme
            'transport_fee' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->shippingService->validateArrival(
            auth()->user(),
            $order,
            $request->transport_fee ?? 0
        );

        return redirect()->route('secretary.dashboard')
            ->with(
                'success',
                "Arrivée validée — OTP envoyé à l'acheteur. Timer 72h démarré."
            );
    }

    // Valider l'OTP donné verbalement par l'acheteur lors du retrait
    public function validateOtp(Request $request, Order $order)
    {
        $request->validate([
            // Code à 6 chiffres donné verbalement par l'acheteur
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $this->shippingService->validateOtp(
            auth()->user(),
            $order,
            $request->otp
        );

        return redirect()->route('secretary.dashboard')
            ->with('success', "Colis remis — Commande {$order->reference} terminée.");
    }
}
