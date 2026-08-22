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

    // Liste des litiges de l'acheteur
    public function index()
    {
        $disputes = Dispute::whereHas('order', function ($q) {
            $q->where('buyer_id', Auth::id());
        })
            ->with(['order.shop', 'order.items.product', 'evidences'])
            ->latest()
            ->paginate(20);

        return view('buyer.disputes.index', compact('disputes'));
    }

    // Formulaire d'ouverture du litige
    public function create(Order $order)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);
        abort_unless($order->canBeDisputed(), 403);

        // Vérifier qu'un litige n'est pas déjà ouvert
        if ($order->dispute && ! $order->dispute->isResolved()) {
            return redirect()->route('buyer.disputes.show', $order->dispute)
                ->with('info', 'Un litige est déjà ouvert pour cette commande.');
        }

        return view('buyer.disputes.create', [
            'order' => $order,
            'types' => Dispute::TYPES,
        ]);
    }

    // Soumettre le litige
    public function store(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === Auth::id(), 403);

        $request->validate([
            'type'        => ['required', 'in:' . implode(',', array_keys(Dispute::TYPES))],
            'description' => ['required', 'string', 'min:20'],
            'files.*'     => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);
        $seller = $order->shop->user;

        // crediter du pending
        $seller->increment('wallet_pending', $order->total_amount);

        // debiter le disponible
        $seller->decrement('wallet_available', $order->total_amount);

        $dispute = $this->disputeService->open(
            $order,
            Auth::user(),
            $request->type,
            $request->description,
            $request->file('files', [])
        );

        return redirect()->route('buyer.disputes.show', $dispute)
            ->with('success', 'Litige ouvert. Le vendeur a 48h pour répondre.');
    }

    // Détail du litige
    public function show(Dispute $dispute)
    {
        // Acheteur ou vendeur concerné uniquement
        abort_unless(
            $dispute->order->buyer_id === Auth::id()
                || $dispute->order->shop->user_id === Auth::id(),
            403
        );

        $dispute->load([
            'order.shop',
            'order.buyer',
            'order.items.product',
            'evidences.submitter',
            'initiator',
            'resolver',
        ]);

        return view('buyer.disputes.show', compact('dispute'));
    }
}
