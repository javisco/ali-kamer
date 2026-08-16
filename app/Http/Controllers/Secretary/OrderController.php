<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private ShippingService $shippingService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $secretary = auth()->user();

        $counter = $secretary->primaryCounter();

        $pendingDeposit = collect();

        $pendingArrival = collect();

        $pendingDelivery = collect();

        if ($counter) {

            $pendingDeposit =
                $this->getPendingDeposit($counter);

            $pendingArrival =
                $this->getPendingArrival($counter);

            $pendingDelivery =
                $this->getPendingDelivery($counter);
        }

        return view(
            'secretary.dashboard',
            compact(
                'secretary',
                'counter',
                'pendingDeposit',
                'pendingArrival',
                'pendingDelivery'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DÉPÔT AU DÉPART
    |--------------------------------------------------------------------------
    */

    public function deposit(Request $request)
    {
        $request->validate([
            'deposit_code' => [
                'required',
                'string',
                'size:8',
            ],
        ]);

        try {

            $order =
                $this->shippingService
                ->registerByDepositCode(
                    auth()->user(),
                    $request->deposit_code
                );
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

        return back()->with(
            'success',
            "Colis {$order->reference} enregistré pour le départ."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ARRIVÉE
    |--------------------------------------------------------------------------
    */

    public function arrival(
        Request $request,
        Order $order
    ) {

        $request->validate([
            'transport_fee' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        try {

            $order =
                $this->shippingService
                ->validateArrival(
                    auth()->user(),
                    $order,
                    (int) (
                        $request->transport_fee ?? 0
                    )
                );
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

        return back()->with(
            'success',
            "Le colis {$order->reference} est arrivé. Le code OTP a été généré."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECHERCHE COMMANDE
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $request->validate([
            'ref' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $secretary = auth()->user();

        $counter = $secretary->primaryCounter();

        if (! $counter) {

            return back()->with(
                'error',
                'Aucun comptoir principal ne vous est assigné.'
            );
        }

        $order = Order::query()
            ->where(
                'reference',
                strtoupper(
                    trim($request->ref)
                )
            )
            ->with([
                'buyer',
                'shop',
                'shipment.agency',
                'shipment.originCounter',
                'shipment.destinationCounter',
            ])
            ->first();

        if (! $order) {

            return back()->with(
                'error',
                'Commande introuvable.'
            );
        }

        if (! $order->shipment) {

            return back()->with(
                'error',
                'Cette commande ne possède aucune expédition.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | La recherche est limitée aux colis de ce comptoir
        |--------------------------------------------------------------------------
        */

        $isDestination =
            $order->shipment->destination_counter_id
            ===
            $counter->id;

        $isOrigin =
            $order->shipment->origin_counter_id
            ===
            $counter->id;

        if (! $isDestination && ! $isOrigin) {

            return back()->with(
                'error',
                'Cette commande ne relève pas de votre comptoir.'
            );
        }

        return view(
            'secretary.dashboard',
            [
                'secretary' =>
                $secretary,

                'counter' =>
                $counter,

                'pendingDeposit' =>
                $this->getPendingDeposit(
                    $counter
                ),

                'pendingArrival' =>
                $this->getPendingArrival(
                    $counter
                ),

                'pendingDelivery' =>
                $this->getPendingDelivery(
                    $counter
                ),

                'searchedOrder' =>
                $order,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OTP
    |--------------------------------------------------------------------------
    */

    public function otp(
        Request $request,
        Order $order
    ) {

        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        try {

            $order =
                $this->shippingService
                ->validateOtp(
                    auth()->user(),
                    $order,
                    $request->otp
                );
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

        return redirect()
            ->route('secretary.dashboard')
            ->with(
                'success',
                "Le colis {$order->reference} a été remis à l’acheteur."
            );
    }

    /*
    |--------------------------------------------------------------------------
    | COLIS EN ATTENTE DE DÉPÔT
    |--------------------------------------------------------------------------
    */

    private function getPendingDeposit($counter)
    {
        return Order::query()
            ->where(
                'status',
                Order::STATUS_PREPARING
            )
            ->whereHas(
                'shipment',
                function ($query) use ($counter) {

                    $query->where(
                        'agency_id',
                        $counter->agency_id
                    );
                }
            )
            ->with([
                'shop',
                'buyer',
                'shipment.originCounter',
                'shipment.destinationCounter',
            ])
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COLIS EN ARRIVÉE
    |--------------------------------------------------------------------------
    */

    private function getPendingArrival($counter)
    {
        return Order::query()
            ->where(
                'status',
                Order::STATUS_REGISTERED_ORIGIN
            )
            ->whereHas(
                'shipment',
                function ($query) use ($counter) {

                    $query->where(
                        'destination_counter_id',
                        $counter->id
                    );
                }
            )
            ->with([
                'shop',
                'buyer',
                'shipment.originCounter',
                'shipment.destinationCounter',
            ])
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COLIS PRÊTS À ÊTRE REMIS
    |--------------------------------------------------------------------------
    */

    private function getPendingDelivery($counter)
    {
        return Order::query()
            ->where(
                'status',
                Order::STATUS_AWAITING_BUYER_CONFIRMATION
            )
            ->whereHas(
                'shipment',
                function ($query) use ($counter) {

                    $query->where(
                        'destination_counter_id',
                        $counter->id
                    );
                }
            )
            ->with([
                'shop',
                'buyer',
                'shipment',
            ])
            ->latest()
            ->get();
    }
}
