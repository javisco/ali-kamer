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

        // Récupérer le comptoir principal du secrétaire
        $counter = $secretary->assignedCounters()
            ->wherePivot('is_primary', true)
            ->with('agency')
            ->first();

        $pendingDeposit = collect();
        $pendingArrival = collect();

        if ($counter) {

            // Colis à enregistrer au départ
            $pendingDeposit = $this->getPendingDeposit($counter);

            // Colis destinés précisément à ce comptoir
            $pendingArrival = $this->getPendingArrival($counter);
        }

        return view('secretary.dashboard', compact(
            'secretary',
            'counter',
            'pendingDeposit',
            'pendingArrival'
        ));
    }


    // Rechercher une commande par référence pour la remise OTP
    public function searchOrder(Request $request)
    {
        $request->validate([
            'ref' => ['required', 'string', 'max:50'],
        ]);

        $secretary = auth()->user();

        // Récupérer le comptoir principal de la secrétaire
        $counter = $secretary->assignedCounters()
            ->wherePivot('is_primary', true)
            ->with('agency')
            ->first();

        if (! $counter) {
            return redirect()
                ->route('secretary.dashboard')
                ->with('error', 'Aucun comptoir principal n’est assigné à votre compte.');
        }

        // Rechercher uniquement une commande
        // actuellement disponible pour la remise
        $order = Order::where('reference', strtoupper(trim($request->ref)))
            ->where('status', Order::STATUS_AWAITING_BUYER_CONFIRMATION)
            ->with(['buyer', 'shipment'])
            ->first();

        if (! $order) {
            return redirect()
                ->route('secretary.dashboard')
                ->with('error', 'Commande introuvable ou non disponible pour la remise.');
        }

        /*
     * IMPORTANT :
     *
     * Le colis doit appartenir à l'agence sélectionnée
     * par le vendeur ET être destiné précisément
     * au comptoir de cette secrétaire.
     */

        if ($order->shipment->agency_id !== $counter->agency_id) {
            return redirect()
                ->route('secretary.dashboard')
                ->with(
                    'error',
                    'Ce colis appartient à une autre agence.'
                );
        }

        if ($order->shipment->destination_counter_id !== $counter->id) {
            return redirect()
                ->route('secretary.dashboard')
                ->with(
                    'error',
                    'Ce colis est destiné à un autre comptoir de cette agence.'
                );
        }

        // Tout est correct.
        // On renvoie le dashboard avec le colis trouvé.
        return view('secretary.dashboard', [
            'secretary'     => $secretary,
            'counter'       => $counter,
            'pendingDeposit' => $this->getPendingDeposit($counter),
            'pendingArrival' => $this->getPendingArrival($counter),
            'searchedOrder'  => $order,
        ]);
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
    private function getPendingDeposit($counter)
    {
        return Order::where('status', Order::STATUS_PREPARING)
            ->whereHas(
                'shipment',
                fn($q) =>
                $q->where('agency_id', $counter->agency_id)
            )
            ->with(['shop', 'shipment', 'buyer'])
            ->latest()
            ->get();
    }
    private function getPendingArrival($counter)
    {
        return Order::whereIn('status', [
            Order::STATUS_REGISTERED_ORIGIN,
            Order::STATUS_IN_TRANSIT,
        ])
            ->whereHas(
                'shipment',
                fn($q) =>
                $q->where('destination_counter_id', $counter->id)
            )
            ->with(['shop', 'shipment', 'buyer'])
            ->latest()
            ->get();
    }
}
