<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Str;

class ShopService
{
    public function create(User $user, array $data): Shop
    {
        return Shop::create([
            'user_id'     => $user->id,
            'name'        => $data['name'],
            'slug'        => $this->uniqueSlug($data['name']),
            'city'        => $data['city'],
            'phone'       => $data['phone'],
            'address'     => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
            'status'      => Shop::STATUS_PENDING,
        ]);
    }

    public function update(Shop $shop, array $data): Shop
    {
        $shop->update([
            'name'        => $data['name'],
            'city'        => $data['city'],
            'phone'       => $data['phone'],
            'address'     => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['logo'])) {
            $shop->update([
                'logo' => $data['logo']->store('shops/logos', 'public'),
            ]);
        }

        return $shop->fresh();
    }

    public function suspend(Shop $shop): void
    {
        $shop->update(['status' => Shop::STATUS_SUSPENDED]);
    }

    public function reactivate(Shop $shop): void
    {
        $shop->update([
            'status'      => Shop::STATUS_ACTIVE,
            'verified_at' => now(),
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $slug     = Str::slug($name);
        $original = $slug;
        $i        = 1;

        while (Shop::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}