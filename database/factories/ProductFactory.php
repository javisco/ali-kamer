<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Services\ProductVariantService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $villes = ['Douala', 'Yaoundé', 'Bafoussam', 'Bamenda', 'Garoua', 'Maroua', 'Ngaoundéré', 'Kribi'];

        $price = $this->faker->numberBetween(10, 15000);
        $hasDiscount = $this->faker->boolean(30); // 30% des produits en promo

        return [
            'shop_id'                => Shop::factory(),
            'category_id'            => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title'                  => ucfirst($this->faker->words(4, true)),
            'description'            => $this->faker->paragraphs(3, true),
            'city'                   => $this->faker->randomElement($villes),
            'price'                  => $price,
            'old_price'              => $hasDiscount ? $price + $this->faker->numberBetween(500, 20000) : null,
            'stock'                  => $this->faker->numberBetween(0, 200),
            'stock_reserved'         => 0,
            'min_quantity'           => $this->faker->randomElement([1, 1, 1, 2, 5]),
            'shipping_included'      => $this->faker->boolean(50),
            'shipping_threshold_qty' => $this->faker->optional(0.3)->numberBetween(2, 10),
            'specifications'         => $this->faker->boolean(60) ? [
                'Couleur'  => $this->faker->safeColorName(),
                'Poids'    => $this->faker->numberBetween(1, 20) . ' kg',
                'Garantie' => $this->faker->randomElement(['3 mois', '6 mois', '1 an', 'Aucune']),
            ] : null,
            'status'       => 'visible',
            'views_count'  => $this->faker->numberBetween(0, 500),
            'orders_count' => $this->faker->numberBetween(0, 50),
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['status' => 'hidden']);
    }

    public function soldOut(): static
    {
        return $this->state(fn () => ['status' => 'sold_out', 'stock' => 0]);
    }

    public function withColorAndSize(): static
    {
        return $this->afterCreating(function (Product $product) {
            $this->syncVariants($product, [
                ['name' => 'Couleur', 'values' => ['Noir', 'Blanc', 'Rouge']],
                ['name' => 'Taille', 'values' => ['S', 'M', 'L']],
            ]);
        });
    }

    public function withStorage(): static
    {
        return $this->afterCreating(function (Product $product) {
            $this->syncVariants($product, [
                ['name' => 'Couleur', 'values' => ['Noir', 'Bleu']],
                ['name' => 'Stockage', 'values' => ['128 Go', '256 Go']],
            ]);
        });
    }

    public function withSizeOnly(): static
    {
        return $this->afterCreating(function (Product $product) {
            $this->syncVariants($product, [
                ['name' => 'Pointure', 'values' => ['39', '40', '41', '42', '43']],
            ]);
        });
    }

    /**
     * @param  list<array{name: string, values: list<string>}>  $attributes
     */
    private function syncVariants(Product $product, array $attributes): void
    {
        $variants = [];
        $combos = [[]];

        foreach ($attributes as $attribute) {
            $next = [];
            foreach ($combos as $prefix) {
                foreach ($attribute['values'] as $value) {
                    $next[] = [...$prefix, $value];
                }
            }
            $combos = $next;
        }

        foreach ($combos as $index => $values) {
            $price = max(100, (int) $product->price + ($index * 500));
            $variants[] = [
                'values'    => $values,
                'price'     => $price,
                'old_price' => $product->old_price && $product->old_price > $price ? $product->old_price : null,
                'stock'     => $index === 1 ? 0 : fake()->numberBetween(2, 12),
                'sku'       => strtoupper(substr($product->title, 0, 3)) . '-' . implode('-', $values),
                'is_active' => $index !== count($combos) - 1,
            ];
        }

        app(ProductVariantService::class)->sync($product, $attributes, $variants);
    }
}