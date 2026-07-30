<?php

namespace Database\Factories;

use App\Models\Model;
use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class KycDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cni_front_url' => $this->faker->name(),
            'cni_back_url' => $this->faker->name(),
            'selfie_url' => $this->faker->name(),
            'rccm_url' => $this->faker->name(),
            'user_id' => $this->faker->numberBetween(2,3),
            'status' => 'approved',
        ];
    }
}
