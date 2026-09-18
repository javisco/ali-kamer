<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGroup;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

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
    public function initiateGroup(OrderGroup $group)
    {
        abort_unless($group->buyer_id === auth()->id(), 403);

        $this->paymentService->initiateGroup($group);

        return redirect()->route('buyer.payment.group.waiting', $group)
            ->with('success', 'Vérifiez votre téléphone ! Un message de confirmation vous a été envoyé.');
    }

    public function waitingGroup(OrderGroup $group)
    {
        abort_unless($group->buyer_id === auth()->id(), 403);

        return view('buyer.payment.group-waiting', compact('group'));
    }

    public function statusGroup(OrderGroup $group)
    {
        abort_unless($group->buyer_id === auth()->id(), 403);

        return response()->json([
            'status' => $this->paymentService->synchronizeGroup($group),
        ]);
    }
}
