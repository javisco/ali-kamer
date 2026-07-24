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







// 1. Récupération des deux boutiques créées précédemment
        $shop1 = Shop::where('slug', 'kamer-tech-store')->first() ?? Shop::first();
        $shop2 = Shop::where('slug', 'fashion-kamer')->first() ?? Shop::skip(1)->first();

        // Sécurité si les boutiques n'existent pas
        if (!$shop1 || !$shop2) {
            $this->command->error("Veuillez d'abord exécuter ShopSeeder (boutiques introuvables).");
            return;
        }

        // 2. Récupération des catégories (ou création de secours si vide)
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $catTech = Category::create(['name' => 'Électronique & High-Tech', 'slug' => 'electronique-high-tech']);
            $catFashion = Category::create(['name' => 'Mode & Vétements', 'slug' => 'mode-vetements']);
            $categories = collect([$catTech, $catFashion]);
        }

        $techCategory = $categories->first();
        $fashionCategory = $categories->skip(1)->first() ?? $techCategory;

        // --- CATALOGUE BOUTIQUE 1 : HIGH-TECH (25 Produits) ---
        $techItems = [
            'Smartphone Samsung Galaxy A54 128Go', 'iPhone 13 Pro Max 256Go Reconditionné', 'Écouteurs Sans Fil Bluetooth Pro',
            'Casque Audio Bluetooth Réduction de Bruit', 'Montre Connectée Sport Waterproof', 'Ordinateur Portatif HP Core i5 16GB RAM',
            'MacBook Air M1 256GB SSD', 'Tablette Tactile Android 10 pouces', 'Clé USB 128Go USB 3.0 Haute Vitesse',
            'Disque Dur Externe 1To Toshiba', 'PowerBank 20000mAh Charge Rapide', 'Chargeur Rapide Type-C 65W',
            'Souris Sans Fil Ergonomique', 'Clavier Mécanique Gamer RGB', 'Écran PC 24 pouces Full HD',
            'Enceinte Bluetooth Portable Waterproof', 'Caméra de Surveillance WiFi 1080p', 'Routeur WiFi 4G Carte SIM',
            'Support Téléphone Portable pour Voiture', 'Câble de Charge Magnétique 3-en-1', 'Trépied Ring Light avec Télécommande',
            'Carte Mémoire Micro SD 64Go Class 10', 'Convertisseur HDMI vers VGA', 'Pochette de Protection MacBook 13"',
            'Manette de Jeu PC/Android Bluetooth'
        ];

        foreach ($techItems as $index => $title) {
            $price = rand(15, 450) * 1000; // Prix entre 15 000 FCFA et 450 000 FCFA
            $hasDiscount = rand(0, 1);

            Product::create([
                'shop_id' => $shop1->id,
                'category_id' => $techCategory->id,
                'title' => $title,
                'description' => "Produit High-Tech garanti d'excellente qualité. " . $title . " est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.",
                'city' => $shop1->city ?? 'Douala',
                'price' => $price,
                'old_price' => $hasDiscount ? $price + rand(5, 50) * 1000 : null,
                'stock' => rand(5, 50),
                'stock_reserved' => 0,
                'min_quantity' => 1,
                'shipping_included' => (bool) rand(0, 1),
                'shipping_threshold_qty' => rand(2, 5),
                'specifications' => json_encode(['Marque' => 'Générique/Original', 'Garantie' => '6 Mois', 'État' => 'Neuf']),
                'status' => 'visible',
                'views_count' => rand(10, 500),
                'orders_count' => rand(0, 30),
            ]);
        }

        // --- CATALOGUE BOUTIQUE 2 : MODE & VÊTEMENTS (25 Produits) ---
        $fashionItems = [
            'T-shirt Homme Coton Qualité Supérieure', 'Chemise Homme Manches Longues Slim Fit', 'Jean Homme Original Coupe Droite',
            'Robe de Soirée Élégante Africaine', 'Ensemble Bazin Riche Brodé 3 Pièces', 'Chaussures en Cuir Homme Véritable',
            'Baskets Sneakers Style Urbain', 'Sac à Main Femme Cuir Synthétique', 'Pochette de Soirée Dorée',
            'Polo Homme Sport Respirant', 'Veste Blazer Homme Chic', 'Jupe Longue Plissée Tendance',
            'Pantalon Chino Homme Beige', 'Ceinture Homme Cuir Noir Boucle Automatique', 'Montre Homme Bracelet en Acier',
            'Lunettes de Soleil Polarisées Homme/Femme', 'Chapeau Fedora Style Vintage', 'Sandales Cuir Homme Confort',
            'Escarpins Femme Talons Hauts 8cm', 'Ensemble Sport Survêtement Homme', 'Pyjama Coton Doux 2 Pièces',
            'Sac à Dos Voyage/Ordi 15 pouces', 'Portefeuille Cuir Compact Homme', 'Casquette Style Baseball Réglable',
            'Écharpe / Foulard en Soie Imprimé'
        ];

        foreach ($fashionItems as $index => $title) {
            $price = rand(5, 80) * 1000; // Prix entre 5 000 FCFA et 80 000 FCFA
            $hasDiscount = rand(0, 1);

            Product::create([
                'shop_id' => $shop2->id,
                'category_id' => $fashionCategory->id,
                'title' => $title,
                'description' => "Découvrez notre superbe " . $title . ". Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.",
                'city' => $shop2->city ?? 'Yaoundé',
                'price' => $price,
                'old_price' => $hasDiscount ? $price + rand(2, 15) * 1000 : null,
                'stock' => rand(10, 100),
                'stock_reserved' => 0,
                'min_quantity' => 1,
                'shipping_included' => false,
                'shipping_threshold_qty' => 3,
                'specifications' => json_encode(['Taille' => 'S, M, L, XL', 'Matière' => 'Coton / Cuir', 'Origine' => 'Importation']),
                'status' => 'visible',
                'views_count' => rand(15, 300),
                'orders_count' => rand(0, 20),
            ]);
        }











        // // Sécurité : il faut des catégories avant de créer des produits
        // if (Category::count() === 0) {
        //     $this->call(CategorySeeder::class);
        // }

        // // Crée 10 boutiques actives si aucune n'existe encore
        // $shops = Shop::active()->count() > 0
        //     ? Shop::active()->get()
        //     : Shop::factory()->count(10)->create();

        // // 80 produits visibles répartis aléatoirement sur ces boutiques
        // Product::factory()
        //     ->count(80)
        //     ->create()
        //     ->each(function (Product $product) use ($shops) {
        //         // Réassigner à une boutique existante (au lieu de la factory qui en crée une neuve à chaque fois)
        //         $product->update(['shop_id' => $shops->random()->id]);

        //         // 1 à 4 images par produit
        //         $imageCount = fake()->numberBetween(1, 4);

        //         for ($i = 0; $i < $imageCount; $i++) {
        //             $product->images()->create([
        //                 'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
        //                 'position'   => $i + 1,
        //                 'is_primary' => $i === 0,
        //             ]);
        //         }
        //     });

        // // Quelques produits cachés et en rupture pour tester les filtres
        // Product::factory()->hidden()->count(5)->create()->each(function (Product $product) use ($shops) {
        //     $product->update(['shop_id' => $shops->random()->id]);
        //     $product->images()->create([
        //         'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
        //         'position'   => 1,
        //         'is_primary' => true,
        //     ]);
        // });

        // Product::factory()->soldOut()->count(5)->create()->each(function (Product $product) use ($shops) {
        //     $product->update(['shop_id' => $shops->random()->id]);
        //     $product->images()->create([
        //         'url'        => 'https://picsum.photos/seed/' . fake()->uuid() . '/600/600',
        //         'position'   => 1,
        //         'is_primary' => true,
        //     ]);
        // });
    }
}