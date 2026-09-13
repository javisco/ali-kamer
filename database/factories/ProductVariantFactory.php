<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $price = $this->faker->numberBetween(2000, 25000);

        return [
            'product_id'     => Product::factory(),
            'price'          => $price,
            'old_price'      => $this->faker->boolean(25) ? $price + $this->faker->numberBetween(500, 5000) : null,
            'stock'          => $this->faker->numberBetween(0, 30),
            'stock_reserved' => 0,
            'sku'            => strtoupper($this->faker->bothify('AK-???-##')),
            'is_active'      => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0, 'stock_reserved' => 0]);
    }
}
