<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name'       => $this->faker->randomElement([
                'Couleur', 'Taille', 'Pointure', 'Stockage', 'RAM', 'Modèle',
            ]),
            'sort_order' => 0,
        ];
    }

    public function named(string $name, int $sortOrder = 0): static
    {
        return $this->state(fn () => [
            'name'       => $name,
            'sort_order' => $sortOrder,
        ]);
    }

    public function withValues(array $values): static
    {
        return $this->afterCreating(function (ProductAttribute $attribute) use ($values) {
            foreach (array_values($values) as $index => $value) {
                ProductAttributeValue::factory()->create([
                    'product_attribute_id' => $attribute->id,
                    'value'                => $value,
                    'sort_order'           => $index,
                ]);
            }
        });
    }
}
