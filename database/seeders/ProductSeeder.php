<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $figure = Category::where('slug', 'figure-model-kit')->first();
        $kostum = Category::where('slug', 'kostum-merchandise')->first();

        $products = [
            [
                'name' => 'Leviathan Figure 1/4',
                'slug' => 'leviathan-figure-1-4',
                'description' => 'Figure skala 1/4 karakter Leviathan dengan detail sangat tinggi dan material PVC premium.',
                'price' => 1500000,
                'discount_price' => 1300000,
                'stock' => 5,
                'image' => null,
                'is_new' => false,
                'categories' => [$figure ? $figure->id : null],
            ],
            [
                'name' => 'Megami Figure 1/7',
                'slug' => 'megami-figure-1-7',
                'description' => 'Figure skala 1/7 karakter Megami dengan pose dinamis dan warna cerah.',
                'price' => 1750000,
                'discount_price' => 1500000,
                'stock' => 3,
                'image' => null,
                'is_new' => false,
                'categories' => [$figure ? $figure->id : null],
            ],
            [
                'name' => 'Moryne Theme Acrylic',
                'slug' => 'moryne-theme-acrylic',
                'description' => 'Acrylic stand tema Moryne dengan desain eksklusif dan bingkai hitam elegan.',
                'price' => 600000,
                'discount_price' => 500000,
                'stock' => 10,
                'image' => null,
                'is_new' => false,
                'categories' => [$kostum ? $kostum->id : null],
            ],
            [
                'name' => 'Kaguya Acrylic Stand',
                'slug' => 'kaguya-acrylic-stand',
                'description' => 'Acrylic stand karakter Kaguya dengan ilustrasi full color resolusi tinggi.',
                'price' => 500000,
                'discount_price' => 350000,
                'stock' => 8,
                'image' => null,
                'is_new' => false,
                'categories' => [$kostum ? $kostum->id : null],
            ],
            [
                'name' => 'Saint High Figure 1/7 Vol.1',
                'slug' => 'saint-high-figure-1-7-vol1',
                'description' => 'Figure skala 1/7 edisi terbatas Saint High Volume 1.',
                'price' => 1300000,
                'discount_price' => null,
                'stock' => 7,
                'image' => null,
                'is_new' => true,
                'categories' => [$figure ? $figure->id : null],
            ],
            [
                'name' => 'Saint High Figure 1/7 Vol.2',
                'slug' => 'saint-high-figure-1-7-vol2',
                'description' => 'Figure skala 1/7 edisi terbatas Saint High Volume 2.',
                'price' => 1300000,
                'discount_price' => null,
                'stock' => 6,
                'image' => null,
                'is_new' => true,
                'categories' => [$figure ? $figure->id : null],
            ],
            [
                'name' => 'Saint High Figure 1/7 Vol.3',
                'slug' => 'saint-high-figure-1-7-vol3',
                'description' => 'Figure skala 1/7 edisi terbatas Saint High Volume 3.',
                'price' => 1300000,
                'discount_price' => null,
                'stock' => 4,
                'image' => null,
                'is_new' => true,
                'categories' => [$figure ? $figure->id : null],
            ],
            [
                'name' => 'Saint High Figure 1/7 Vol.4',
                'slug' => 'saint-high-figure-1-7-vol4',
                'description' => 'Figure skala 1/7 edisi terbatas Saint High Volume 4.',
                'price' => 1300000,
                'discount_price' => null,
                'stock' => 3,
                'image' => null,
                'is_new' => true,
                'categories' => [$figure ? $figure->id : null],
            ],
        ];

        foreach ($products as $data) {
            $categoryIds = array_filter($data['categories']);
            unset($data['categories']);
            $product = Product::firstOrCreate(['slug' => $data['slug']], $data);
            if (!empty($categoryIds)) {
                $product->categories()->sync($categoryIds);
            }
        }
    }
}
