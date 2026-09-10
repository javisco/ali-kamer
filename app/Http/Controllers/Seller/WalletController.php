<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    // Page portefeuille vendeur avec historique des transactions
    public function index()
    {
        $user = auth()->user();
        $this->walletService->syncSellerBalances($user);
        $user->refresh();
        $transactions = $this->walletService->history($user);

        return view('seller.wallet.index', compact('user', 'transactions'));
    }

    // Formulaire de demande de retrait
    public function withdrawForm()
    {
        $user = auth()->user();
        return view('seller.wallet.withdraw', compact('user'));
    }

    // Traitement de la demande de retrait
    public function withdraw(Request $request)
    {
        $request->validate([
            // Le montant doit être un entier positif
            'amount' => ['required', 'integer', 'min:1000'],
        ]);

        $this->walletService->requestWithdrawal(
            auth()->user(),
            $request->amount
        );

        return redirect()->route('seller.wallet.index')
            ->with('success', 'Demande de retrait enregistrée. Traitement sous 24h.');
    }
}