<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\Services\DisputeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function __construct(private DisputeService $disputeService) {}

    // Formulaire d'ouverture du litige
    public function create(Order $order)
    {
        // Seul l'acheteur de la commande peut ouvrir un litige
        abort_unless($order->buyer_id === Auth::user(), 403);
        abort_unless($order->canBeDisputed(), 403);

        return view('buyer.disputes.create', [
            'order' => $order,
            'types' => Dispute::TYPES,
        ]);
    }

    // Soumettre le litige
    public function store(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === Auth::user(), 403);

        $request->validate([
            'type'        => ['required', 'in:' . implode(',', array_keys(Dispute::TYPES))],
            'description' => ['required', 'string', 'min:20'],
            'files.*'     => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $this->disputeService->open(
            $order,
            Auth::user(),
            $request->type,
            $request->description,
            $request->file('files', [])
        );

        return redirect()->route('buyer.disputes.show', $order->dispute)
            ->with('success', 'Litige ouvert. Le vendeur a 48h pour répondre.');
    }

    // Détail du litige
    public function show(Dispute $dispute)
    {
        // Acheteur ou vendeur concerné
        abort_unless(
            $dispute->order->buyer_id === Auth::user()
            || $dispute->order->shop->user_id === Auth::user(),
            403
        );

        $dispute->load([
            'order.shop', 'order.buyer',
            'evidences.submitter',
            'initiator', 'resolver',
        ]);

        return view('buyer.disputes.show', compact('dispute'));
    }
}