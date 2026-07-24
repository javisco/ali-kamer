<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    protected $model = Shop::class;


    public function definition(): array
    {
        $name = fake()->company() . ' Store';

        return [
            // Associe automatiquement à un utilisateur existant ou en crée un
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'city' => fake()->randomElement(['Douala', 'Yaoundé', 'Bafoussam', 'Garoua', 'Bamenda']),
            'phone' => '+2376' . fake()->numerify('########'),
            'address' => fake()->streetAddress(),
            'description' => fake()->paragraph(),
            'logo' => null,
            'status' => 'active',
            'verified_at' => now(),
        ];
    }

    /**
     * État pour une boutique en attente de validation
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'verified_at' => null,
        ]);
    }
    // public function definition(): array
    // {
    //     $name = $this->faker->company();

    //     return [
    //         'user_id'     => User::factory(),
    //         'name'        => $name,
    //         'slug'        => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 99999),
    //         'city'        => $this->faker->randomElement(['Douala', 'Yaoundé', 'Bafoussam', 'Bamenda']),
    //         'phone'       => '6' . $this->faker->numerify('########'),
    //         'address'     => $this->faker->address(),
    //         'description' => $this->faker->sentence(15),
    //         'status'      => 'active',
    //         'verified_at' => now(),
    //     ];
    // }
}
