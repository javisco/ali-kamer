<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformWalletTransaction;
use App\Services\TreasuryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TreasuryController extends Controller
{
    public function __construct(private TreasuryService $treasury) {}

    public function index()
    {
        $snapshot = $this->treasury->getSnapshot();

        $history = PlatformWalletTransaction::with('admin')
            ->latest()
            ->paginate(20);

        return view('admin.treasury.index', compact('snapshot', 'history'));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount'   => ['required', 'integer', 'min:1000'],
            'phone'    => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'operator' => ['required', 'in:mtn,orange'],
            'password' => ['required', 'string'],
            'note'     => ['nullable', 'string', 'max:255'],
        ]);

        if (! Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.'])->withInput();
        }

        try {
            $this->treasury->withdraw(
                Auth::user(),
                (int) $request->amount,
                $request->phone,
                $request->operator,
                $request->note
            );
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.treasury.index')
            ->with('success', 'Retrait de ' . number_format($request->amount, 0, ',', ' ') . ' FCFA effectué avec succès.');
    }
}