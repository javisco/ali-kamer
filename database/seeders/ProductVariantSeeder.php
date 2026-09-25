<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Services\ProductVariantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductVariantSeeder extends Seeder
{
    /**
     * Nombre d'images à rattacher par produit.
     */
    protected int $imagesPerProduct = 5;

    public function run(): void
    {
        $shopTech = Shop::where('slug', 'kamer-tech-store')->first() ?? Shop::first();
        $shopFashion = Shop::where('slug', 'fashion-kamer')->first() ?? Shop::skip(1)->first() ?? $shopTech;

        if (! $shopTech) {
            $this->command->error('Aucune boutique trouvée. Exécutez ShopSeeder d’abord.');
            return;
        }

        $categories = Category::all();
        $techCategory = $categories->first();
        $fashionCategory = $categories->skip(1)->first() ?? $techCategory;

        if (! $techCategory) {
            $this->command->error('Aucune catégorie trouvée. Exécutez CategorySeeder d’abord.');
            return;
        }

        // --- Préparation des images depuis le stockage public ---
        $allImages = Storage::disk('public')->files('products/pindd');
        $totalImages = count($allImages);

        if ($totalImages === 0) {
            $this->command->warn('Aucune image trouvée dans storage/app/public/products/pindd.');
        }

        // Le pointeur démarre à la FIN du fichier d'images pour éviter les doublons avec ProductSeeder
        $imagePointer = $totalImages > 0 ? $totalImages - 1 : 0;

        $service = app(ProductVariantService::class);

        // ==========================================
        // 1. T-shirt coton homme
        // ==========================================
        $tshirt = $this->makeProduct(
            $shopFashion,
            $fashionCategory->id,
            'T-shirt coton homme',
            'T-shirt basique en coton. Choisissez la couleur et la taille.',
            10000,
            12000
        );
        $service->sync($tshirt, [
            ['name' => 'Couleur', 'values' => ['Noir', 'Blanc', 'Rouge']],
            ['name' => 'Taille', 'values' => ['S', 'M', 'L', 'XL']],
        ], [
            $this->variant(['Noir', 'S'], 10000, 12000, 8, 'TS-NOIR-S'),
            $this->variant(['Noir', 'M'], 10000, 12000, 12, 'TS-NOIR-M'),
            $this->variant(['Noir', 'L'], 11000, 13000, 5, 'TS-NOIR-L'),
            $this->variant(['Noir', 'XL'], 11000, 13000, 3, 'TS-NOIR-XL'),
            $this->variant(['Blanc', 'S'], 10000, 12000, 4, 'TS-BLANC-S'),
            $this->variant(['Blanc', 'M'], 10000, 12000, 9, 'TS-BLANC-M'),
            $this->variant(['Blanc', 'L'], 11000, 13000, 0, 'TS-BLANC-L'),
            $this->variant(['Blanc', 'XL'], 11000, 13000, 2, 'TS-BLANC-XL'),
            $this->variant(['Rouge', 'S'], 10000, 12000, 6, 'TS-ROUGE-S'),
            $this->variant(['Rouge', 'M'], 10000, 12000, 7, 'TS-ROUGE-M'),
            $this->variant(['Rouge', 'L'], 11000, 13000, 1, 'TS-ROUGE-L'),
            $this->variant(['Rouge', 'XL'], 11000, 13000, 0, 'TS-ROUGE-XL', false),
        ]);
        $this->attachImagesFromEnd($tshirt, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 2. Baskets Streetwear
        // ==========================================
        $shoes = $this->makeProduct(
            $shopFashion,
            $fashionCategory->id,
            'Baskets Streetwear',
            'Baskets urbaines. Chaque pointure a son stock.',
            25000,
            30000
        );
        $service->sync($shoes, [
            ['name' => 'Pointure', 'values' => ['39', '40', '41', '42', '43']],
        ], [
            $this->variant(['39'], 25000, 30000, 2, 'BK-39'),
            $this->variant(['40'], 25000, 30000, 0, 'BK-40'),
            $this->variant(['41'], 25000, 30000, 5, 'BK-41'),
            $this->variant(['42'], 27000, 32000, 3, 'BK-42'),
            $this->variant(['43'], 27000, 32000, 1, 'BK-43'),
        ]);
        $this->attachImagesFromEnd($shoes, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 3. Smartphone Infinix Note
        // ==========================================
        $phone = $this->makeProduct(
            $shopTech,
            $techCategory->id,
            'Smartphone Infinix Note',
            'Choisissez la couleur et le stockage. Chaque combinaison a son prix.',
            150000,
            175000
        );
        $service->sync($phone, [
            ['name' => 'Couleur', 'values' => ['Noir', 'Bleu']],
            ['name' => 'Stockage', 'values' => ['128 Go', '256 Go']],
        ], [
            $this->variant(['Noir', '128 Go'], 150000, 165000, 6, 'INF-NOIR-128'),
            $this->variant(['Noir', '256 Go'], 180000, 195000, 4, 'INF-NOIR-256'),
            $this->variant(['Bleu', '128 Go'], 150000, 165000, 2, 'INF-BLEU-128'),
            $this->variant(['Bleu', '256 Go'], 180000, 195000, 0, 'INF-BLEU-256'),
        ]);
        $this->attachImagesFromEnd($phone, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 4. Casque Audio sans Fil
        // ==========================================
        $casque = $this->makeProduct(
            $shopTech,
            $techCategory->id,
            'Casque Audio sans Fil',
            'Casque circum-auriculaire haute fidélité avec réduction de bruit et connexion hybride.',
            24990,
            29990
        );
        $service->sync($casque, [
            ['name' => 'Couleur', 'values' => ['Noir', 'Blanc', 'Argent']],
            ['name' => 'Connexion', 'values' => ['Sans fil', 'Filaire']],
        ], [
            $this->variant(['Noir', 'Sans fil'], 24990, 29990, 15, 'CASQ-NOIR-BT'),
            $this->variant(['Noir', 'Filaire'], 19990, 24990, 8, 'CASQ-NOIR-FIL'),
            $this->variant(['Blanc', 'Sans fil'], 24990, 29990, 10, 'CASQ-BLC-BT'),
            $this->variant(['Blanc', 'Filaire'], 19990, 24990, 5, 'CASQ-BLC-FIL'),
            $this->variant(['Argent', 'Sans fil'], 26990, 31990, 7, 'CASQ-ARG-BT'),
            $this->variant(['Argent', 'Filaire'], 21990, 26990, 3, 'CASQ-ARG-FIL'),
        ]);
        $this->attachImagesFromEnd($casque, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 5. Baskets Adidas Stan Smith
        // ==========================================
        $baskets = $this->makeProduct(
            $shopFashion,
            $fashionCategory->id,
            'Baskets Adidas Stan Smith',
            'Icône intemporelle de la mode urbaine en cuir souple et finitions soignées.',
            59990,
            69990
        );
        $service->sync($baskets, [
            ['name' => 'Couleur', 'values' => ['Vert Foncé', 'Blanc', 'Bleu Marine']],
            ['name' => 'Pointure', 'values' => ['40', '41', '42', '43', '44']],
        ], [
            $this->variant(['Vert Foncé', '40'], 59990, 69990, 6, 'AD-STAN-VF-40'),
            $this->variant(['Vert Foncé', '41'], 59990, 69990, 9, 'AD-STAN-VF-41'),
            $this->variant(['Vert Foncé', '42'], 59990, 69990, 14, 'AD-STAN-VF-42'),
            $this->variant(['Vert Foncé', '43'], 59990, 69990, 8, 'AD-STAN-VF-43'),
            $this->variant(['Vert Foncé', '44'], 59990, 69990, 4, 'AD-STAN-VF-44'),
            $this->variant(['Blanc', '40'], 59990, 69990, 5, 'AD-STAN-BLC-40'),
            $this->variant(['Blanc', '41'], 59990, 69990, 8, 'AD-STAN-BLC-41'),
            $this->variant(['Blanc', '42'], 59990, 69990, 12, 'AD-STAN-BLC-42'),
            $this->variant(['Blanc', '43'], 59990, 69990, 6, 'AD-STAN-BLC-43'),
            $this->variant(['Blanc', '44'], 59990, 69990, 3, 'AD-STAN-BLC-44'),
            $this->variant(['Bleu Marine', '40'], 59990, 69990, 4, 'AD-STAN-BM-40'),
            $this->variant(['Bleu Marine', '41'], 59990, 69990, 7, 'AD-STAN-BM-41'),
            $this->variant(['Bleu Marine', '42'], 59990, 69990, 10, 'AD-STAN-BM-42'),
            $this->variant(['Bleu Marine', '43'], 59990, 69990, 5, 'AD-STAN-BM-43'),
            $this->variant(['Bleu Marine', '44'], 59990, 69990, 2, 'AD-STAN-BM-44'),
        ]);
        $this->attachImagesFromEnd($baskets, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 6. Parfum Dior Sauvage
        // ==========================================
        $parfum = $this->makeProduct(
            $shopFashion,
            $fashionCategory->id,
            'Parfum Dior Sauvage',
            'Sillage puissant, frais et boisé. Choisissez la concentration et le format.',
            79990,
            89990
        );
        $service->sync($parfum, [
            ['name' => 'Concentration', 'values' => ['Eau de Parfum', 'Eau de Toilette']],
            ['name' => 'Format', 'values' => ['60ml', '100ml', '200ml']],
        ], [
            $this->variant(['Eau de Parfum', '60ml'], 65990, 75990, 10, 'DIOR-EDP-60'),
            $this->variant(['Eau de Parfum', '100ml'], 79990, 89990, 18, 'DIOR-EDP-100'),
            $this->variant(['Eau de Parfum', '200ml'], 119990, 129990, 6, 'DIOR-EDP-200'),
            $this->variant(['Eau de Toilette', '60ml'], 55990, 65990, 8, 'DIOR-EDT-60'),
            $this->variant(['Eau de Toilette', '100ml'], 69990, 79990, 14, 'DIOR-EDT-100'),
            $this->variant(['Eau de Toilette', '200ml'], 99990, 109990, 4, 'DIOR-EDT-200'),
        ]);
        $this->attachImagesFromEnd($parfum, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        // ==========================================
        // 7. Sac à Main Cuir
        // ==========================================
        $sac = $this->makeProduct(
            $shopFashion,
            $fashionCategory->id,
            'Sac à Main Cuir',
            'Sac élégant en cuir pleine fleur avec bandoulière réglable et fermoir métallique.',
            34990,
            42990
        );
        $service->sync($sac, [
            ['name' => 'Couleur', 'values' => ['Marron', 'Noir', 'Camel']],
            ['name' => 'Taille', 'values' => ['Medium', 'Large']],
        ], [
            $this->variant(['Marron', 'Medium'], 34990, 42990, 11, 'SAC-MARR-MED'),
            $this->variant(['Marron', 'Large'], 39990, 47990, 6, 'SAC-MARR-LRG'),
            $this->variant(['Noir', 'Medium'], 34990, 42990, 14, 'SAC-NOIR-MED'),
            $this->variant(['Noir', 'Large'], 39990, 47990, 7, 'SAC-NOIR-LRG'),
            $this->variant(['Camel', 'Medium'], 34990, 42990, 9, 'SAC-CAM-MED'),
            $this->variant(['Camel', 'Large'], 39990, 47990, 4, 'SAC-CAM-LRG'),
        ]);
        $this->attachImagesFromEnd($sac, $allImages, $totalImages, $imagePointer, $this->imagesPerProduct);

        $this->command->info('Produits de démonstration avec variantes créés avec succès.');
    }

    /**
     * Attache un nombre spécifique d'images à un produit en décrémentant le pointeur d'images depuis la fin.
     */
    private function attachImagesFromEnd(
        Product $product,
        array $images,
        int $totalImages,
        int &$imagePointer,
        int $count
    ): void {
        if ($product->images()->exists() || $totalImages === 0) {
            return;
        }

        for ($index = 0; $index < $count; $index++) {
            // Modulo sécurisé pour le bouclage circulaire (même avec des valeurs négatives)
            $normalizedIndex = (($imagePointer % $totalImages) + $totalImages) % $totalImages;
            $url = $images[$normalizedIndex];

            ProductImage::create([
                'product_id' => $product->id,
                'url'        => $url,
                'position'   => $index + 1,
                'is_primary' => $index === 0,
            ]);

            // Décrémentation du pointeur pour dépiler les images par la fin
            $imagePointer--;
        }
    }

    public static function seedVariantsForProduct(Product $product, int $variantCount = 4, ?array $attributes = null): void
    {
        $count = max(1, min(24, $variantCount));
        $attributes = $attributes ?? self::defaultAttributesForCount($count);
        $service = app(ProductVariantService::class);

        $combos = [[]];
        foreach ($attributes as $attr) {
            $next = [];
            foreach ($combos as $prefix) {
                foreach ($attr['values'] as $val) {
                    $next[] = [...$prefix, $val];
                }
            }
            $combos = $next;
        }

        if ($count < count($combos)) {
            $combos = array_slice($combos, 0, $count);
        }

        $cleanTitle = preg_replace('/[^A-Za-z0-9]/', '', $product->title) ?: 'PRD';
        $variants = [];
        foreach ($combos as $index => $values) {
            $price = max(100, (int) $product->price + ($index * 500));
            $price=round($price/5)*5;
            $skuSuffix = implode('-', array_map(fn ($v) => substr(preg_replace('/[^A-Za-z0-9]/', '', $v) ?: 'VAR', 0, 4), $values));

            $variants[] = [
                'values'    => $values,
                'price'     => $price,
                'old_price' => $product->old_price && $product->old_price > $price ? $product->old_price : null,
                'stock'     => rand(5, 25),
                'sku'       => strtoupper(substr($cleanTitle, 0, 3)) . '-' . strtoupper($skuSuffix),
                'is_active' => true,
            ];
        }

        $service->sync($product, $attributes, $variants);
    }

    private static function defaultAttributesForCount(int $count): array
    {
        if ($count === 4) {
            return [
                ['name' => 'Couleur', 'values' => ['Noir', 'Blanc']],
                ['name' => 'Taille', 'values' => ['M', 'L']],
            ];
        }
        if ($count === 6) {
            return [
                ['name' => 'Couleur', 'values' => ['Noir', 'Blanc', 'Bleu']],
                ['name' => 'Taille', 'values' => ['M', 'L']],
            ];
        }
        if ($count <= 7) {
            $couleurs = ['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Gris', 'Marron'];
            return [
                ['name' => 'Couleur', 'values' => array_slice($couleurs, 0, $count)],
            ];
        }
        if ($count <= 12) {
            $tailles = ['38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49'];
            return [
                ['name' => 'Pointure', 'values' => array_slice($tailles, 0, $count)],
            ];
        }

        $values = [];
        for ($i = 1; $i <= $count; $i++) {
            $values[] = "Option {$i}";
        }
        return [['name' => 'Version', 'values' => $values]];
    }

    private function makeProduct(
        Shop $shop,
        int $categoryId,
        string $title,
        string $description,
        int $price,
        ?int $oldPrice
    ): Product {
        $existing = Product::where('shop_id', $shop->id)->where('title', $title)->first();
        if ($existing) {
            return $existing;
        }

        return Product::create([
            'shop_id'                => $shop->id,
            'category_id'            => $categoryId,
            'title'                  => $title,
            'description'            => $description,
            'city'                   => $shop->city ?? 'Douala',
            'price'                  => $price,
            'old_price'              => $oldPrice,
            'stock'                  => 0,
            'stock_reserved'         => 0,
            'min_quantity'           => (int)rand(1,50),
            'shipping_included'      => true,
            'shipping_threshold_qty' => 3,
            'specifications'         => ['Garantie' => '6 mois', 'État' => 'Neuf'],
            'status'                 => 'visible',
            'views_count'            => 42,
            'orders_count'           => 3,
        ]);
    }

    private function variant(
        array $values,
        int $price,
        ?int $oldPrice,
        int $stock,
        string $sku,
        bool $active = true
    ): array {
        return [
            'values'    => $values,
            'price'     => $price,
            'old_price' => $oldPrice,
            'stock'     => $stock,
            'sku'       => $sku,
            'is_active' => $active,
        ];
    }
}