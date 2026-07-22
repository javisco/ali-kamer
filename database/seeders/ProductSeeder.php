<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Sécurité : il faut des catégories avant de créer des produits
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        // Crée 10 boutiques actives si aucune n'existe encore
        $shops = Shop::active()->count() > 0
            ? Shop::active()->get()
            : Shop::factory()->count(10)->create();

        // 80 produits visibles répartis aléatoirement sur ces boutiques
        Product::factory()
            ->count(80)
            ->create()
            ->each(function (Product $product) use ($shops) {
                // Réassigner à une boutique existante (au lieu de la factory qui en crée une neuve à chaque fois)
                $product->update(['shop_id' => $shops->random()->id]);

                // 1 à 4 images par produit
                $imageCount = fake()->numberBetween(1, 4);

                for ($i = 0; $i < $imageCount; $i++) {
                    $product->images()->create([
                        'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
                        'position'   => $i + 1,
                        'is_primary' => $i === 0,
                    ]);
                }
            });

        // Quelques produits cachés et en rupture pour tester les filtres
        Product::factory()->hidden()->count(5)->create()->each(function (Product $product) use ($shops) {
            $product->update(['shop_id' => $shops->random()->id]);
            $product->images()->create([
                'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
                'position'   => 1,
                'is_primary' => true,
            ]);
        });

        Product::factory()->soldOut()->count(5)->create()->each(function (Product $product) use ($shops) {
            $product->update(['shop_id' => $shops->random()->id]);
            $product->images()->create([
                'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
                'position'   => 1,
                'is_primary' => true,
            ]);
        });
    }
}