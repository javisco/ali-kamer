<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
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
}