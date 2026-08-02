<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    // // Page de paiement après création de la commande
    // public function show(Order $order)
    // {
    //     // Vérifier que c'est bien la commande de cet acheteur
    //     abort_unless($order->buyer_id === auth()->id(), 403);

    //     // Vérifier que le paiement est bien en attente
    //     abort_unless(
    //         in_array($order->status, ['pending', 'awaiting_payment', 'failed']),
    //         404
    //     );

    //     return view('buyer.payment.show', compact('order'));
    // }

    // Initier le paiement Campay (push USSD)
    public function initiate(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);

        $this->paymentService->initiate($order);

        return redirect()->route('buyer.payment.waiting', $order)
            ->with(
                'success',
                'Vérifiez votre téléphone ! Un message de confirmation vous a été envoyé.'
            );
    }

    // Page d'attente après initiation du paiement
    public function waiting(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);

        return view('buyer.payment.waiting', compact('order'));
    }

    // ── AJAX : Vérifie le statut du paiement ─────────────────────────────

    public function status(Order $order)
    {
        abort_unless(
            $order->buyer_id === auth()->id(),
            403
        );

        $status = $this->paymentService
            ->synchronize($order);

        return response()->json([
            'status' => $status,
        ]);
    }
}
