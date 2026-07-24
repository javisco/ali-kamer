<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Récupérer les vendeurs de test créés précédemment
        $seller1 = User::where('email', 'vendeur1@test.com')->first();
        $seller2 = User::where('email', 'vendeur2@test.com')->first();

        // 2. Attribuer une boutique active au 1er vendeur
        if ($seller1) {
            Shop::create([
                'user_id' => $seller1->id,
                'name' => 'Kamer Tech Store',
                'slug' => Str::slug('Kamer Tech Store'),
                'city' => 'Douala',
                'phone' => '+237690000001',
                'address' => 'Akwa, Rue Joyeuse',
                'description' => 'Vente de matériel informatique et accessoires high-tech.',
                'status' => 'active',
                'verified_at' => now(),
            ]);
        }

        // 3. Attribuer une boutique en attente (pending) au 2e vendeur pour tester la validation admin
        if ($seller2) {
            Shop::create([
                'user_id' => $seller2->id,
                'name' => 'Fashion Kamer',
                'slug' => Str::slug('Fashion Kamer'),
                'city' => 'Yaoundé',
                'phone' => '+237670000002',
                'address' => 'Bastos, Avenue des Palmiers',
                'description' => 'Boutique de vêtements modernes et accessoires de mode.',
                'status' => 'pending',
                'verified_at' => null,
            ]);
        }
    }
}
