<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyCounter;
use App\Models\User;
use App\Services\AgencyManagerService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private AgencyManagerService $managerService) {}

    // Dashboard principal du manager d'agence
    public function index()
    {
        $agency = auth()->user()->managedAgency;

        abort_unless($agency, 403, 'Aucune agence assignée.');

        $agency->load([
            'counters.secretary.secretary',
            'cities',
        ]);

        $stats = $this->managerService->getStats($agency);

        return view('agency.dashboard', compact('agency', 'stats'));
    }

    // Créer un comptoir
    public function storeCounter(Request $request)
    {
        $agency = auth()->user()->managedAgency;

        $request->validate([
            'city'     => ['required', 'string'],
            'district' => ['required', 'string'],
            'landmark' => ['nullable', 'string'],
            'phone'    => ['nullable', 'string'],
        ]);

        try {
            $this->managerService->createCounter($agency, $request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['city' => $e->getMessage()]);
        }

        return back()->with('success', 'Comptoir créé.');
    }

    // Activer/désactiver un comptoir
    public function toggleCounter(AgencyCounter $counter)
    {
        $agency = auth()->user()->managedAgency;

        try {
            $this->managerService->toggleCounter($agency, $counter);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Statut du comptoir mis à jour.');
    }

    // Créer et affecter un secrétaire
    public function storeSecretary(Request $request, AgencyCounter $counter)
    {
        $agency = auth()->user()->managedAgency;

        $request->validate([
            'name'     => ['required', 'string'],
            'phone'    => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        try {
            $this->managerService->createSecretary($agency, $counter, $request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Secrétaire créé et affecté.');
    }

    // Activer/désactiver un secrétaire
    public function toggleSecretary(User $secretary)
    {
        $agency = auth()->user()->managedAgency;

        try {
            $this->managerService->toggleSecretary($agency, $secretary);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Statut du secrétaire mis à jour.');
    }

    // Supprimer un secrétaire
    public function deleteSecretary(User $secretary)
    {
        $agency = auth()->user()->managedAgency;

        try {
            $this->managerService->deleteSecretary($agency, $secretary);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Secrétaire supprimé.');
    }

    // Page wallet + historique
    public function wallet()
    {
        $agency  = auth()->user()->managedAgency;
        $stats   = $this->managerService->getStats($agency);
        $history = $this->managerService->getHistory($agency);

        return view('agency.wallet', compact('agency', 'stats', 'history'));
    }

    // Retrait
    public function withdraw(Request $request)
    {
        $agency = auth()->user()->managedAgency;

        $request->validate([
            'amount' => ['required', 'integer', 'min:1000'],
        ]);

        try {
            $this->managerService->requestWithdrawal($agency, $request->amount);
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }

        return back()->with('success', 'Retrait effectué avec succès.');
    }
}
