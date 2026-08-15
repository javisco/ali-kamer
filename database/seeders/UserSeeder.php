<?php

namespace Database\Seeders;

use App\Models\KycDocument;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {

        // Un mot de passe unique et facile à retenir pour TOUS tes comptes de test
        $defaultPassword = Hash::make('11111111');

        // 1. ADMIN (1 compte)
        User::create([
            'name' => 'Admin System',
            'email' => 'admin@test.com',
            'phone' => '+237673538767',
            'role' => 'admin',
            'password' => $defaultPassword,
            'email_verified_at' => now(),
        ]);

        // 2. VENDEURS (2 comptes)
        User::create([
            'name' => 'Vendeur Paul',
            'email' => 'vendeur1@test.com',
            'phone' => '+237676538747',
            'role' => 'seller',
            'password' => $defaultPassword,
            'email_verified_at' => now(),
        ]);


        User::create([
            'name' => 'Vendeur Marie',
            'email' => 'vendeur2@test.com',
            'phone' => '+237673538746',
            'role' => 'seller',
            'password' => $defaultPassword,
            'email_verified_at' => now(),
        ]);

        KycDocument::create([
            'cni_front_url' => 'okay',
            'cni_back_url' => "rien",
            'selfie_url' => 'rien du tout',
            'rccm_url' => 'noting',
            'user_id' => 2,
            'status' => 'approved',
        ]);
        KycDocument::create([
            'cni_front_url' => 'okay',
            'cni_back_url' => "rien",
            'selfie_url' => 'rien du tout',
            'rccm_url' => 'noting',
            'user_id' => 3,
            'status' => 'approved',
        ]);
        // 3. ACHETEURS (2 comptes)
        User::create([
            'name' => 'Acheteur Eric',
            'email' => 'acheteur1@test.com',
            'phone' => '+237678538747',
            'role' => 'buyer',
            'password' => $defaultPassword,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Acheteur Alice',
            'email' => 'acheteur2@test.com',
            'phone' => '+237674538747',
            'role' => 'buyer',
            'password' => $defaultPassword,
            'email_verified_at' => now(),
        ]);


        // // Comptes fixes, faciles à retenir pour tester
        // User::factory()->admin()->create([
        //     'name'  => 'Admin Ali-Kamer',
        //     'email' => 'admin@alikamer.test',
        //     'phone' => '677000001',
        // ]);

        // User::factory()->secretary()->create([
        //     'name'  => 'Secrétaire Test',
        //     'email' => 'secretaire@alikamer.test',
        //     'phone' => '677000002',
        // ]);

        // User::factory()->sellerActive()->create([
        //     'name'  => 'Vendeur Actif Test',
        //     'email' => 'vendeur@alikamer.test',
        //     'phone' => '677000003',
        // ]);

        // // Celui-ci sert à tester le flow KYC de bout en bout
        // User::factory()->seller()->create([
        //     'name'  => 'Vendeur En Attente Test',
        //     'email' => 'vendeur.pending@alikamer.test',
        //     'phone' => '677000004',
        // ]);

        // User::factory()->buyer()->create([
        //     'name'  => 'Acheteur Test',
        //     'email' => 'acheteur@alikamer.test',
        //     'phone' => '677000005',
        // ]);

        // // Volume pour tester pagination, filtres admin, etc.
        // User::factory()->buyer()->count(30)->create();
        // User::factory()->sellerActive()->count(10)->create();
        // User::factory()->seller()->count(5)->create();     // vendeurs candidats, en attente de KYC
        // User::factory()->secretary()->count(2)->create();
        // User::factory()->banned()->count(2)->create();
    }
}
