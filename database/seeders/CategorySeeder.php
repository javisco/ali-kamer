<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Textile & Mode'       => ['Vêtements homme', 'Vêtements femme', 'Chaussures', 'Accessoires'],
            'Électronique'         => ['Téléphones', 'Ordinateurs', 'Accessoires tech', 'TV & Audio'],
            'Alimentation'         => ['Épicerie', 'Boissons', 'Produits locaux'],
            'Beauté & Cosmétiques' => ['Soins visage', 'Soins cheveux', 'Parfums'],
            'Maison & Décoration'  => ['Meubles', 'Cuisine', 'Décoration'],
            'Sport & Loisirs'      => ['Sport', 'Jeux', 'Musique'],
            'Auto & Moto'          => ['Pièces auto', 'Accessoires moto'],
            'Autre'                => [],
        ];

        foreach ($categories as $parent => $children) {
            $parentCat = Category::create([
                'name'      => $parent,
                'slug'      => Str::slug($parent),
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                Category::create([
                    'parent_id' => $parentCat->id,
                    'name'      => $child,
                    'slug'      => Str::slug($child),
                    'is_active' => true,
                ]);
            }
        }
    }
}