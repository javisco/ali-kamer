<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   //1. recuperer tous les fichiers de products/pindd
        $images = Storage::disk('public')->files('products/pindd');

        if (empty($images)) {
            $this->command->warn('aucune image trouve');
            return;
        }
        $totalImages = count($images);
        $imageIndex = 0;
        $products = Product::all();
        foreach ($products as $product) {

            for ($index = 0; $index < 5; $index++) {
                $url  = $images[$imageIndex % $totalImages];


                ProductImage::create([
                    'product_id' => $product->id,
                    'url'        => $url,
                    'position'   => $index + 1,
                    'is_primary' => $index === 0, // première image = principale
                ]);
                $imageIndex++;
            }
        }
    }
}
