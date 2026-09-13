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

        $service = app(ProductVariantService::class);

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
        $this->attachImages($tshirt);

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
        $this->attachImages($shoes);

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
        $this->attachImages($phone);

        $this->command->info('3 produits de démo avec variantes créés (T-shirt, Baskets, Smartphone).');
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
            'min_quantity'           => 1,
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

    private function attachImages(Product $product): void
    {
        if ($product->images()->exists()) {
            return;
        }

        $files = Storage::disk('public')->files('products/pindd');
        if ($files === []) {
            return;
        }

        foreach (array_slice($files, 0, 3) as $index => $url) {
            ProductImage::create([
                'product_id' => $product->id,
                'url'        => $url,
                'position'   => $index + 1,
                'is_primary' => $index === 0,
            ]);
        }
    }
}
