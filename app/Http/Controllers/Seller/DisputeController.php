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
        ->with(['order', 'initiator'])
        ->latest()
        ->paginate(20);

        return view('seller.disputes.index', compact('disputes'));
    }

    // Répondre au litige
    public function reply(Request $request, Dispute $dispute)
    {
        abort_unless(
            $dispute->order->shop->user_id === Auth::user(),
            403
        );

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

        return back()->with('success', 'Réponse envoyée. L\'admin va examiner le dossier.');
    }
}