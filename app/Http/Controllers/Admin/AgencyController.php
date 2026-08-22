<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyCity;
use App\Models\AgencyCounter;
use App\Models\AdminLog;
use App\Models\User;
use App\Services\AgencyService;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function __construct(private AgencyService $agencyService) {}


    // Créer un compte manager pour une agence
    public function storeManager(Request $request, Agency $agency)
    {
        $request->validate([
            'name'     => ['required', 'string'],
            'phone'    => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Vérifier qu'il n'y a pas déjà un manager pour cette agence
        $existing = User::where('role', 'agency_manager')
            ->where('agency_id', $agency->id)
            ->exists();

        if ($existing) {
            return back()->withErrors(['manager' => 'Cette agence a déjà un compte manager.']);
        }

        $manager = User::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => User::ROLE_AGENCY_MANAGER,
            'status'    => User::STATUS_ACTIVE,
            'agency_id' => $agency->id,
        ]);

        AdminLog::record(
            auth()->user(),
            'agency_manager.created',
            'user',
            $manager->id,
            "Manager créé pour l'agence {$agency->name}"
        );

        return back()->with(
            'success',
            "Compte manager créé pour {$manager->name}. " .
                "Il peut se connecter avec le numéro {$manager->phone}."
        );
    }

    // Configurer le MoMo de l'agence pour les retraits
    public function updateAgencyMomo(Request $request, Agency $agency)
    {
        $request->validate([
            'phone_momo'    => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'momo_operator' => ['required', 'in:mtn,orange'],
        ]);

        $agency->update([
            'phone_momo'    => $request->phone_momo,
            'momo_operator' => $request->momo_operator,
        ]);

        return back()->with('success', 'Numéro MoMo de l\'agence mis à jour.');
    }


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
    // Modifier une agence (déjà codée, route manquait)
    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name'          => ['required', 'string', 'min:3'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
        ]);

        $this->agencyService->updateAgency($agency, $request->all());

        AdminLog::record(auth()->user(), 'agency.updated', 'agency', $agency->id);

        return back()->with('success', 'Agence mise à jour.');
    }

    // Modifier le compte agency_manager
    public function updateManager(Request $request, User $manager)
    {
        // Vérifier que c'est bien un agency_manager
        abort_unless($manager->role === 'agency_manager', 403);

        $request->validate([
            'name'     => ['required', 'string'],
            'phone'    => ['required', 'string', 'unique:users,phone,' . $manager->id],
            'email'    => ['nullable', 'email', 'unique:users,email,' . $manager->id],
            'password' => ['nullable', 'string', 'min:8'],
            'status'   => ['required', 'in:active,suspended'],
        ]);

        $data = $request->only(['name', 'phone', 'email', 'status']);

        // Ne mettre à jour le mot de passe que s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $manager->update($data);

        AdminLog::record(
            auth()->user(),
            'agency_manager.updated',
            'user',
            $manager->id
        );

        return back()->with('success', 'Compte manager mis à jour.');
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

        return back()->with(
            'success',
            "Secrétaire {$secretary->name} créé et affecté à {$counter->full_name}."
        );
    }

    // ── HELPER PRIVÉ ─────────────────────────────────────────────────

    private function getCities(): array
    {
        return [
            'Douala',
            'Yaoundé',
            'Bafoussam',
            'Bamenda',
            'Buea',
            'Limbé',
            'Garoua',
            'Maroua',
            'Ngaoundéré',
            'Bertoua',
            'Ebolowa',
            'Kribi',
            'Kumba',
            'Edéa',
            'Nkongsamba',
            'Dschang',
        ];
    }
}
