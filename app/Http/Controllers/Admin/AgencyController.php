<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyCity;
use App\Models\AgencyCounter;
use App\Models\AdminLog;
use App\Services\AgencyService;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function __construct(private AgencyService $agencyService) {}

    // ── AGENCES ───────────────────────────────────────────────────────

    // Liste de toutes les agences
    public function index()
    {
        $agencies = Agency::withCount(['counters', 'cities'])
            ->with('cities')
            ->latest()
            ->paginate(20);

        return view('admin.agencies.index', compact('agencies'));
    }

    // Formulaire création agence
    public function create()
    {
        return view('admin.agencies.create');
    }

    // Sauvegarder nouvelle agence
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'min:3', 'max:100'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
        ]);

        $agency = $this->agencyService->createAgency($request->all());

        AdminLog::record(auth()->user(), 'agency.created', 'agency', $agency->id);

        return redirect()->route('admin.agencies.show', $agency)
            ->with('success', "Agence {$agency->name} créée.");
    }

    // Détail d'une agence — comptoirs + villes + secrétaires
    public function show(Agency $agency)
    {
        $agency->load([
            'cities',
            'counters.secretary.secretary',
        ]);

        $cities = $this->getCities();

        return view('admin.agencies.show', compact('agency', 'cities'));
    }

    // Modifier une agence
    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name'          => ['required', 'string', 'min:3'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
        ]);

        $this->agencyService->updateAgency($agency, $request->all());

        return back()->with('success', 'Agence mise à jour.');
    }

    // Activer / désactiver une agence
    public function toggle(Agency $agency)
    {
        $this->agencyService->toggleAgency($agency);

        $action = $agency->fresh()->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "Agence {$action}.");
    }

    // ── VILLES DESSERVIES ─────────────────────────────────────────────

    // Ajouter une ville desservie
    public function addCity(Request $request, Agency $agency)
    {
        $request->validate([
            'city' => ['required', 'string'],
        ]);

        $this->agencyService->addCity($agency, $request->city);

        return back()->with('success', "Ville {$request->city} ajoutée.");
    }

    // Activer / désactiver une ville
    public function toggleCity(AgencyCity $city)
    {
        $this->agencyService->toggleCity($city);

        $action = $city->fresh()->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "Ville {$action}.");
    }

    // ── COMPTOIRS ─────────────────────────────────────────────────────

    // Créer un comptoir
    public function storeCounter(Request $request, Agency $agency)
    {
        $request->validate([
            'city'     => ['required', 'string'],
            'district' => ['required', 'string'],
            'landmark' => ['nullable', 'string'],
            'phone'    => ['nullable', 'string'],
        ]);

        $this->agencyService->createCounter($agency, $request->all());

        return back()->with('success', 'Comptoir créé.');
    }

    // Activer / désactiver un comptoir
    public function toggleCounter(AgencyCounter $counter)
    {
        $this->agencyService->toggleCounter($counter);

        $action = $counter->fresh()->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Comptoir {$action}.");
    }

    // ── SECRÉTAIRES ───────────────────────────────────────────────────

    // Créer un secrétaire et l'affecter à un comptoir
    public function storeSecretary(Request $request, AgencyCounter $counter)
    {
        $request->validate([
            'name'     => ['required', 'string'],
            'phone'    => ['required', 'string', 'regex:/^6[0-9]{15}$/', 'unique:users,phone'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $secretary = $this->agencyService->createSecretary(
            $request->all(),
            $counter
        );

        AdminLog::record(
            auth()->user(),
            'secretary.created',
            'user',
            $secretary->id,
            "Affecté au comptoir {$counter->full_name}"
        );

        return back()->with('success',
            "Secrétaire {$secretary->name} créé et affecté à {$counter->full_name}."
        );
    }

    // ── HELPER PRIVÉ ─────────────────────────────────────────────────

    private function getCities(): array
    {
        return [
            'Douala', 'Yaoundé', 'Bafoussam', 'Bamenda',
            'Buea', 'Limbé', 'Garoua', 'Maroua',
            'Ngaoundéré', 'Bertoua', 'Ebolowa', 'Kribi',
            'Kumba', 'Edéa', 'Nkongsamba', 'Dschang',
        ];
    }
}