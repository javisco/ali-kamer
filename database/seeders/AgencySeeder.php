<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyCity;
use App\Models\AgencyCounter;
use App\Models\SecretaryCounter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        // Données de configuration des deux agences
        $agenciesData = [
            [
                'name' => 'General',
                'contact_phone' => '699001122',
                'contact_email' => 'contact@general.cm',
                'cities' => ['Douala', 'Yaoundé', 'Bafoussam', 'Dschang'],
                'counters' => [
                    ['city' => 'Douala', 'district' => 'Akwa', 'landmark' => 'Face Ancien Dalip', 'phone' => '699001123'],
                    ['city' => 'Yaoundé', 'district' => 'Mvan', 'landmark' => 'Près du carrefour Mvan', 'phone' => '699001124'],
                    ['city' => 'Bafoussam', 'district' => 'Marché B', 'landmark' => 'Gare routière', 'phone' => '699001125'],
                ],
            ],
            [
                'name' => 'Global Voyage',
                'contact_phone' => '677334455',
                'contact_email' => 'contact@globalvoyage.cm',
                'cities' => ['Douala', 'Yaoundé', 'Bamenda', 'Kribi'],
                'counters' => [
                    ['city' => 'Douala', 'district' => 'Bessengue', 'landmark' => 'À côté de la gare Camrail', 'phone' => '677334456'],
                    ['city' => 'Yaoundé', 'district' => 'Biyem-Assi', 'landmark' => 'Carrefour Acacia', 'phone' => '677334457'],
                    ['city' => 'Kribi', 'district' => 'Centre-ville', 'landmark' => 'Près de la poste', 'phone' => '677334458'],
                ],
            ],
        ];

        $secretaryIndex = 1;
        $i = 1;
        foreach ($agenciesData as $data) {
            // 1. Création de l'agence
            $agency = Agency::create([
                'name'          => $data['name'],
                'slug'          => Str::slug($data['name']),
                'contact_phone' => $data['contact_phone'],
                'contact_email' => $data['contact_email'],
                'is_active'     => true,
            ]);


            // 2. Ajout des villes desservies
            foreach ($data['cities'] as $cityName) {
                AgencyCity::create([
                    'agency_id' => $agency->id,
                    'city'      => $cityName,
                    'is_active' => true,
                ]);
            }

            // 3. Création des comptoirs et affectation des secrétaires
            foreach ($data['counters'] as $counterData) {
                $counter = AgencyCounter::create([
                    'agency_id' => $agency->id,
                    'city'      => $counterData['city'],
                    'district'  => $counterData['district'],
                    'landmark'  => $counterData['landmark'],
                    'phone'     => $counterData['phone'],
                    'is_active' => true,
                ]);

                // 4. Création du compte Secrétaire (Email: secretaire1@test.com, secretaire2@test.com, etc.)
                $secretary = User::create([
                    'name'     => "Secrétaire {$agency->name} - {$counter->city}",
                    'phone_momo'    => '6' . str_pad((string) $secretaryIndex, 8, '0', STR_PAD_LEFT), // Génère un num unique
                    'email'    => "secretaire{$secretaryIndex}@test.com",
                    'password' => bcrypt('11111111'),
                    'role'     => User::ROLE_SECRETARY,
                    'status'   => User::STATUS_ACTIVE,
                    'email_verified_at' => now(),
                ]);

                // 5. Liaison du secrétaire au comptoir
                SecretaryCounter::create([
                    'user_id'           => $secretary->id,
                    'agency_counter_id' => $counter->id,
                    'is_primary'        => true,
                ]);

                $secretaryIndex++;
            }
        }
        User::create([
            'name'      => "toto",
            'phone_momo'     => "677777777",
            'email'     => "toto@test.com",
            'password'  => bcrypt("11111111"),
            'role'      => User::ROLE_AGENCY_MANAGER,
            'status'    => User::STATUS_ACTIVE,
            'agency_id' => 1,
        ]);

        User::create([
            'name'      => "tutu",
            'phone_momo'     => "677777770",
            'email'     => "tutu@test.com",
            'password'  => bcrypt("11111111"),
            'role'      => User::ROLE_AGENCY_MANAGER,
            'status'    => User::STATUS_ACTIVE,
            'agency_id' => 2,
        ]);
    }
}
