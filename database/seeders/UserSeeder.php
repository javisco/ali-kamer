<?php

namespace Database\Seeders;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Comptes fixes, faciles à retenir pour tester
        User::factory()->admin()->create([
            'name'  => 'Admin Ali-Kamer',
            'email' => 'admin@alikamer.test',
            'phone' => '677000001',
        ]);

        User::factory()->secretary()->create([
            'name'  => 'Secrétaire Test',
            'email' => 'secretaire@alikamer.test',
            'phone' => '677000002',
        ]);

        User::factory()->sellerActive()->create([
            'name'  => 'Vendeur Actif Test',
            'email' => 'vendeur@alikamer.test',
            'phone' => '677000003',
        ]);

        // Celui-ci sert à tester le flow KYC de bout en bout
        User::factory()->seller()->create([
            'name'  => 'Vendeur En Attente Test',
            'email' => 'vendeur.pending@alikamer.test',
            'phone' => '677000004',
        ]);

        User::factory()->buyer()->create([
            'name'  => 'Acheteur Test',
            'email' => 'acheteur@alikamer.test',
            'phone' => '677000005',
        ]);

        // Volume pour tester pagination, filtres admin, etc.
        User::factory()->buyer()->count(30)->create();
        User::factory()->sellerActive()->count(10)->create();
        User::factory()->seller()->count(5)->create();     // vendeurs candidats, en attente de KYC
        User::factory()->secretary()->count(2)->create();
        User::factory()->banned()->count(2)->create();
    }
}