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



        // Récupération des deux boutiques créées précédemment
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
            $catFashion = Category::create(['name' => 'Mode & Vêtements', 'slug' => 'mode-vetements']);
            $categories = collect([$catTech, $catFashion]);
        }

        $techCategory = $categories->first();
        $fashionCategory = $categories->skip(1)->first() ?? $techCategory;

        // --- CATALOGUE BOUTIQUE 1 : HIGH-TECH (250 Produits) ---
        $techBases = [
            // Smartphones & Tablettes
            'Smartphone Samsung Galaxy',
            'iPhone Apple',
            'Xiaomi Redmi Note',
            'Infinix Note Pro',
            'Tecno Camon',
            'Tablette Tactile Android',
            'iPad Air Retina',
            'Tablette Graphique USB',
            'Liseuse Électronique HD',
            // Informatique & Périphériques
            'Ordinateur Portatif HP ProBook',
            'MacBook Air M2',
            'PC Portable Dell Latitude',
            'PC Gamer Asus ROG',
            'Écran PC 27 pouces',
            'Écran PC 24 pouces Full HD',
            'Souris Sans Fil Ergonomique',
            'Clavier Mécanique RGB',
            'Tapis de Souris XXL Gaming',
            'Support PC Portable Ventilè',
            'Hub USB-C Multiports 8-en-1',
            'Webcam HD 1080p',
            // Audio & Multimédia
            'Écouteurs Sans Fil Bluetooth',
            'Casque Audio Réduction de Bruit',
            'Enceinte Bluetooth Portable',
            'Barre de Son TV Bass',
            'Microphone Condensateur USB',
            'Lecteur MP3 Sport Bluetooth',
            // Stockage & Énergie
            'Clé USB 3.0 Haute Vitesse',
            'Disque Dur Externe Toshiba',
            'Disque SSD Externe Rapide',
            'Carte Mémoire Micro SD',
            'PowerBank Charge Rapide',
            'Chargeur Rapide Type-C 65W',
            'Station de Charge Sans Fil',
            'Câble Magnétique 3-en-1',
            // Smart Home, Réseau & Gadgets
            'Montre Connectée Sport',
            'Bracelet Connecté Fitness',
            'Caméra de Surveillance WiFi',
            'Routeur WiFi 4G SIM',
            'Repeteur WiFi Puissant',
            'Projecteur LED Mini HD',
            'Trépied Ring Light Télécommande',
            'Pochette Protection MacBook',
            'Manette de Jeu Bluetooth',
            'Stabilisateur Gimbal Smartphone',
            'Convertisseur HDMI vers VGA'
        ];

        $techVariants = ['Pro', 'Ultra', 'Max', 'Plus', 'Edition Limitée', 'Gamer RGB', 'Waterproof', 'Compact', 'Haute Vitesse', 'Reconditionné', 'Série X', 'Titanium', 'Slim', 'Smart', 'Elite', 'V2', 'Prime'];
        $techSpecs = ['64Go', '128Go', '256Go', '512Go', '1To', '8GB RAM', '16GB RAM', '32GB RAM', '10000mAh', '20000mAh', '30000mAh', '45W', '65W', '100W', '4K Ultra HD', 'Full HD 1080p', 'Class 10 V30'];

        for ($i = 1; $i <= 250; $i++) {
            $base = $techBases[array_rand($techBases)];
            $variant = $techVariants[array_rand($techVariants)];
            $spec = $techSpecs[array_rand($techSpecs)];

            $title = "{$base} {$variant} {$spec}";
            $price = rand(5, 25);
            $hasDiscount = (bool) rand(0, 1);

            Product::create([
                'shop_id' => $shop1->id,
                'category_id' => $techCategory->id,
                'title' => $title,
                'description' => "Produit High-Tech garanti d'excellente qualité. {$title} est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.",
                'city' => $shop1->city ?? 'Douala',
                'price' => $price,
                'old_price' => $hasDiscount ? $price + rand(5, 50) : null,
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

        // --- CATALOGUE BOUTIQUE 2 : MODE & VÊTEMENTS (250 Produits) ---
        $fashionBases = [
            // Hauts & Vêtements Homme
            'T-Shirt Col V Coton',
            'Polo Sport Respirant',
            'Chemise Manches Longues',
            'Chemise Cintrée Slim Fit',
            'Veste Blazer Chic',
            'Gilet Sans Manches',
            'Sweat à Capuche Urban',
            'Ensemble Survêtement Sport',
            // Hauts & Vêtements Femme
            'Robe de Soirée Élégante',
            'Robe d\'Été Imprimée',
            'Robe Maxi Plissée',
            'Jupe Longue Tendance',
            'Jupe Courte Évasée',
            'Blouse en Soie',
            'Top Dentelle Raffiné',
            'Combinaison Pantalon Chic',
            // Tenues Traditionnelles & Spéciales
            'Ensemble Bazin Riche Brodé 3 Pièces',
            'Boubou Africain Moderne',
            'Tunique Traditionnelle Motif Wax',
            'Ensemble Pyjama Coton Doux',
            'Peignoir Satin Élégant',
            // Chaussures Homme & Femme
            'Chaussures en Cuir Véritable',
            'Baskets Sneakers Streetwear',
            'Mocassins Cuir Suédé',
            'Sandales Cuir Confort',
            'Escarpins Talons Hauts',
            'Bottines en Cuir',
            'Claquettes Style Tendance',
            // Sacs & Accessoires
            'Sac à Main Cuir Synthétique',
            'Pochette de Soirée Dorée',
            'Sac à Dos Voyage/Ordi',
            'Sac Cabas Cuir Grande Capacité',
            'Portefeuille Compact Cuir',
            'Ceinture Cuir Boucle Automatique',
            'Montre Bracelet en Acier',
            'Montre Cuir Classique',
            'Lunettes de Soleil Polarisées',
            'Chapeau Fedora Vintage',
            'Casquette Style Baseball',
            'Écharpe / Foulard en Soie',
            'Cravate & Boutons de Manchette'
        ];

        $fashionStyles = ['Chic', 'Slim Fit', 'Tendance', 'Confort Extra', 'Original', 'Vintage', 'Élégant', 'Urbain', 'Qualité Supérieure', 'Motif Imprimé', 'Moderne', 'Luxe Prestige', 'Casual', 'Coupe Droite', 'Broderie Hand-Made', 'Collection Été'];
        $fashionColors = ['Noir Proche', 'Blanc Pur', 'Bleu Marine', 'Beige Sable', 'Marron Chocolat', 'Rouge Bordeau', 'Doré Éclatant', 'Gris Anthracite', 'Vert Olive', 'Rose Poudré', 'Jaune Moutarde', 'Multicolore Wax'];

        for ($i = 1; $i <= 250; $i++) {
            $base = $fashionBases[array_rand($fashionBases)];
            $style = $fashionStyles[array_rand($fashionStyles)];
            $color = $fashionColors[array_rand($fashionColors)];

            $title = "{$base} {$style} - {$color}";
            $price = rand(5, 25);
            $hasDiscount = (bool) rand(0, 1);

            Product::create([
                'shop_id' => $shop2->id,
                'category_id' => $fashionCategory->id,
                'title' => $title,
                'description' => "Découvrez notre superbe {$title}. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.",
                'city' => $shop2->city ?? 'Yaoundé',
                'price' => $price,
                'old_price' => $hasDiscount ? $price + rand(5, 30) : null,
                'stock' => rand(10, 100),
                'stock_reserved' => 0,
                'min_quantity' => 1,
                'shipping_included' => rand(0,1),
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
