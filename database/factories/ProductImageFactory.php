<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'url'        => 'https://picsum.photos/seed/' . $this->faker->uuid() . '/600/600',
            'position'   => 1,
            'is_primary' => true,
        ];
    }
}