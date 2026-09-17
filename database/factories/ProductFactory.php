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

    public function withVariants(int $count = 4, ?array $attributes = null): static
    {
        return $this->afterCreating(function (Product $product) use ($count, $attributes) {
            $count = max(1, min(24, $count));
            $resolvedAttributes = $attributes ?? $this->generateAttributesForCount($count);
            $this->syncVariants($product, $resolvedAttributes, $count);
        });
    }

    public function hasVariants(int $count = 4): static
    {
        return $this->withVariants($count);
    }

    public function withRandomVariants(int $min = 2, int $max = 6): static
    {
        return $this->withVariants(fake()->numberBetween($min, $max));
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

    private function generateAttributesForCount(int $count): array
    {
        if ($count === 1) {
            return [['name' => 'Option', 'values' => ['Standard']]];
        }
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
        if ($count === 8) {
            return [
                ['name' => 'Couleur', 'values' => ['Noir', 'Blanc']],
                ['name' => 'Taille', 'values' => ['S', 'M', 'L', 'XL']],
            ];
        }
        if ($count === 9) {
            return [
                ['name' => 'Couleur', 'values' => ['Noir', 'Blanc', 'Rouge']],
                ['name' => 'Taille', 'values' => ['S', 'M', 'L']],
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

    /**
     * @param  list<array{name: string, values: list<string>}>  $attributes
     */
    private function syncVariants(Product $product, array $attributes, ?int $maxCount = null): void
    {
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

        if ($maxCount !== null && $maxCount < count($combos)) {
            $combos = array_slice($combos, 0, $maxCount);
        }

        $variants = [];
        foreach ($combos as $index => $values) {
            $price = max(100, (int) $product->price + ($index * 500));
            $cleanTitle = preg_replace('/[^A-Za-z0-9]/', '', $product->title) ?: 'PRD';
            $skuSuffix = implode('-', array_map(fn ($v) => substr(preg_replace('/[^A-Za-z0-9]/', '', $v) ?: 'VAR', 0, 4), $values));

            $variants[] = [
                'values'    => $values,
                'price'     => $price,
                'old_price' => $product->old_price && $product->old_price > $price ? $product->old_price : null,
                'stock'     => fake()->numberBetween(3, 20),
                'sku'       => strtoupper(substr($cleanTitle, 0, 3)) . '-' . strtoupper($skuSuffix),
                'is_active' => true,
            ];
        }

        app(ProductVariantService::class)->sync($product, $attributes, $variants);
    }
}