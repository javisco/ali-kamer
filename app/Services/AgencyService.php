<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\AgencyCity;
use App\Models\AgencyCounter;
use App\Models\SecretaryCounter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencyService
{
    // ── CRÉER UNE AGENCE ──────────────────────────────────────────────

    public function createAgency(array $data): Agency
    {
        return Agency::create([
            'name'          => $data['name'],
            'slug'          => Str::slug($data['name']),
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'is_active'     => true,
        ]);
    }

    // ── MODIFIER UNE AGENCE ───────────────────────────────────────────

    public function updateAgency(Agency $agency, array $data): Agency
    {
        $agency->update([
            'name'          => $data['name'],
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
        ]);

        return $agency->fresh();
    }

    // ── ACTIVER / DÉSACTIVER UNE AGENCE ──────────────────────────────

    public function toggleAgency(Agency $agency): void
    {
        $agency->update(['is_active' => ! $agency->is_active]);
    }

    // ── AJOUTER UNE VILLE DESSERVIE ───────────────────────────────────

    public function addCity(Agency $agency, string $city): AgencyCity
    {
        return AgencyCity::firstOrCreate(
            ['agency_id' => $agency->id, 'city' => $city],
            ['is_active' => true]
        );
    }

    // ── ACTIVER / DÉSACTIVER UNE VILLE ───────────────────────────────

    public function toggleCity(AgencyCity $city): void
    {
        $city->update(['is_active' => ! $city->is_active]);
    }

    // ── CRÉER UN COMPTOIR ─────────────────────────────────────────────

    public function createCounter(Agency $agency, array $data): AgencyCounter
    {
        // Vérifier que la ville du comptoir est bien desservie par l'agence
        if (! $agency->servesCity($data['city'])) {
            throw new \Exception(
                "L'agence {$agency->name} ne dessert pas la ville {$data['city']}. " .
                    "Ajoutez d'abord cette ville dans les villes desservies."
            );
        }

        return AgencyCounter::create([
            'agency_id' => $agency->id,
            'city'      => $data['city'],
            'district'  => $data['district'] ?? null,
            'landmark'  => $data['landmark'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'is_active' => true,
        ]);
    }

    // ── ACTIVER / DÉSACTIVER UN COMPTOIR ─────────────────────────────

    public function toggleCounter(AgencyCounter $counter): void
    {
        $counter->update(['is_active' => ! $counter->is_active]);
    }

    // ── CRÉER UN COMPTE SECRÉTAIRE ────────────────────────────────────

    public function createSecretary(array $data, AgencyCounter $counter): User
    {
        return DB::transaction(function () use ($data, $counter) {

            // Vérifier que le comptoir n'a pas déjà un secrétaire
            if ($counter->secretary) {
                throw new \Exception(
                    "Ce comptoir a déjà un secrétaire assigné."
                );
            }

            // Créer le compte utilisateur
            $secretary = User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'email'    => $data['email'] ?? null,
                'password' => bcrypt($data['password']),
                'role'     => User::ROLE_SECRETARY,
                'status'   => User::STATUS_ACTIVE,
            ]);

            // Affecter au comptoir
            SecretaryCounter::create([
                'user_id'           => $secretary->id,
                'agency_counter_id' => $counter->id,
                'is_primary'        => true,
            ]);

            return $secretary;
        });
    }

    // ── RÉCUPÉRER LES AGENCES QUI DESSERVENT UNE VILLE ───────────────

    // Utilisé dans le formulaire vendeur pour filtrer les agences
    // disponibles selon la ville de destination de l'acheteur
    public function getAgenciesServingCity(string $city)
    {
        return Agency::active()
            ->whereHas('cities', function ($q) use ($city) {
                $q->where('city', $city)
                    ->where('is_active', true);
            })
            ->with(['counters' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();
    }

    // ── RÉCUPÉRER LES COMPTOIRS D'UNE AGENCE DANS UNE VILLE ──────────

    // Utilisé pour afficher les comptoirs de départ disponibles
    // dans la ville du vendeur pour l'agence choisie
    public function getCountersInCity(Agency $agency, string $city)
    {
        return AgencyCounter::where('agency_id', $agency->id)
            ->where('city', $city)
            ->where('is_active', true)
            ->get();
    }
   

    /**
     * Récupère la liste des noms de villes actives uniques desservies par les agences.
     */
    public function getActiveCities(): \Illuminate\Support\Collection
    {
        return AgencyCity::where('is_active', true)
            ->whereHas('agency', function ($query) {
                $query->where('is_active', true);
            })
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
    }
}
