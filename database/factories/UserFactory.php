<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'phone'             => '6' . $this->faker->numerify('########'),
            'role'              => 'buyer',
            'status'            => 'active',
            'phone_momo'        => null,
            'is_banned'         => false,
            'kyc_level'         => 0,
            'trust_score'       => 100,
            'wallet_pending'    => 0,
            'wallet_available'  => 0,
            'agency_id'         => null,
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function buyer(): static
    {
        return $this->state(fn () => [
            'role'   => 'buyer',
            'status' => 'active',
        ]);
    }

    // Vendeur qui vient de s'inscrire, en attente de validation KYC
    public function seller(): static
    {
        return $this->state(fn () => [
            'role'      => 'seller',
            'status'    => 'candidate',
            'kyc_level' => 0,
        ]);
    }

    // Vendeur déjà validé (KYC approuvé)
    public function sellerActive(): static
    {
        return $this->state(fn () => [
            'role'      => 'seller',
            'status'    => 'active',
            'kyc_level' => 1,
        ]);
    }

    public function secretary(): static
    {
        return $this->state(fn () => [
            'role'   => 'secretary',
            'status' => 'active',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role'   => 'admin',
            'status' => 'active',
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn () => [
            'status'    => 'banned',
            'is_banned' => true,
        ]);
    }
}