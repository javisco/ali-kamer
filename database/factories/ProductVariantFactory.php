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

    public function configure(): static
    {
        return $this->afterCreating(function (ProductVariant $variant) {
            if ($variant->attributeValues()->count() === 0) {
                $product = $variant->product;
                if ($product) {
                    $attribute = \App\Models\ProductAttribute::firstOrCreate(
                        ['product_id' => $product->id, 'name' => 'Option'],
                        ['sort_order' => 0]
                    );
                    $value = \App\Models\ProductAttributeValue::firstOrCreate(
                        ['product_attribute_id' => $attribute->id, 'value' => 'Standard #' . $variant->id],
                        ['sort_order' => 0]
                    );
                    $variant->attributeValues()->syncWithoutDetaching([$value->id]);
                }
            }
        });
    }

    public function withAttributes(array $attributes): static
    {
        return $this->afterCreating(function (ProductVariant $variant) use ($attributes) {
            $product = $variant->product;
            if (! $product) {
                return;
            }

            $valueIds = [];
            $order = 0;
            foreach ($attributes as $attrName => $valName) {
                $attribute = \App\Models\ProductAttribute::firstOrCreate(
                    ['product_id' => $product->id, 'name' => $attrName],
                    ['sort_order' => $order++]
                );
                $value = \App\Models\ProductAttributeValue::firstOrCreate(
                    ['product_attribute_id' => $attribute->id, 'value' => $valName],
                    ['sort_order' => 0]
                );
                $valueIds[] = $value->id;
            }

            $variant->attributeValues()->sync($valueIds);
        });
    }

    public function inStock(int $stock = 15): static
    {
        return $this->state(fn () => [
            'stock' => max(1, $stock),
            'stock_reserved' => 0,
            'is_active' => true,
        ]);
    }

    public function discounted(?int $oldPrice = null): static
    {
        return $this->state(function (array $attributes) use ($oldPrice) {
            $price = $attributes['price'] ?? 5000;
            return [
                'old_price' => $oldPrice && $oldPrice > $price ? $oldPrice : (int) ($price * 1.25),
            ];
        });
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
