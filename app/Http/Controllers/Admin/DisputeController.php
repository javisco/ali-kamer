<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Services\DisputeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function __construct(private DisputeService $disputeService) {}

    // Liste des litiges à traiter
    public function index(Request $request)
    {
        $status   = $request->get('status', 'open');
        $disputes = Dispute::where('status', $status)
            ->with(['order.shop', 'order.buyer', 'initiator'])
            ->latest()
            ->paginate(20);

        $counts = [
            'open'          => Dispute::where('status', 'open')->count(),
            'seller_replied'=> Dispute::where('status', 'seller_replied')->count(),
            'under_review'  => Dispute::where('status', 'under_review')->count(),
            'resolved'      => Dispute::where('status', 'resolved')->count(),
        ];

        return view('admin.disputes.index', compact('disputes', 'counts', 'status'));
    }

    // Dossier complet d'un litige
    public function show(Dispute $dispute)
    {
        // Passer en "under_review" quand l'admin ouvre le dossier
        if ($dispute->status === 'seller_replied') {
            $dispute->update(['status' => 'under_review']);
        }

        $dispute->load([
            'order.items.product',
            'order.shop',
            'order.buyer',
            'order.shipment',
            'order.payment',

            // Conversation liée à la commande pour preuve
            'order.buyer', // messagerie accessible via order

            'evidences.submitter',
            'initiator',
            'resolver',
        ]);

        return view('admin.disputes.show', compact('dispute'));
    }

    // Résoudre le litige
    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'resolution'        => ['required', 'in:' . implode(',', array_keys(Dispute::RESOLUTIONS))],
            'resolution_note'   => ['required', 'string', 'min:10'],
            'resolution_amount' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->disputeService->resolve(
            $dispute,
            Auth::user(),
            $request->resolution,
            $request->resolution_note,
            $request->resolution_amount
        );

        return redirect()->route('admin.disputes.index')
            ->with('success', 'Litige résolu et décision appliquée.');
    }
}