<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Notes reçues des vendeurs
        $reviewsReceived = Review::where('reviewee_type', 'buyer')
            ->where('reviewee_id', $user->id)
            ->with(['reviewer', 'order.shop'])
            ->latest()
            ->paginate(10);

        // Historique transactions (remboursements, frais transport...)
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('buyer.profile.index', compact(
            'user', 'reviewsReceived', 'transactions'
        ));
    }
}