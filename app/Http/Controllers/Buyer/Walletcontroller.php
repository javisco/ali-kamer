<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function history()
    {
        $user         = Auth::user();
        $transactions = $this->walletService->history($user);

        return view('buyer.wallet.history', compact('user', 'transactions'));
    }
}