<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Services\DisputeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function __construct(private DisputeService $disputeService) {}

    // Liste des litiges du vendeur
    public function index()
    {
        $disputes = Dispute::whereHas('order', function ($q) {
            $q->where('shop_id', Auth::user()->shop->id);
        })
            ->with(['order.buyer', 'order.items.product', 'initiator', 'evidences'])
            ->latest()
            ->paginate(20);

        return view('seller.disputes.index', compact('disputes'));
    }

    // Détail d'un litige — vendeur
    public function show(Dispute $dispute)
    {
        abort_unless(
            $dispute->order->shop->user_id === Auth::id(),
            403
        );

        $dispute->load([
            'order.buyer',
            'order.items.product',
            'order.shipment',
            'evidences.submitter',
            'initiator',
            'resolver',
        ]);

        return view('seller.disputes.show', compact('dispute'));
    }

    // Répondre au litige
    public function reply(Request $request, Dispute $dispute)
    {
        // ← Correction bug : Auth::id() pas Auth::user()
        abort_unless($dispute->order->shop->user_id === Auth::id(), 403);

        // Vérifier que le litige attend une réponse
        abort_unless($dispute->isOpen(), 403);

        $request->validate([
            'response' => ['required', 'string', 'min:20'],
            'files.*'  => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $this->disputeService->reply(
            $dispute,
            Auth::user(),
            $request->response,
            $request->file('files', [])
        );

        return redirect()->route('seller.disputes.show', $dispute)
            ->with('success', 'Réponse envoyée. L\'admin va examiner le dossier.');
    }
}
