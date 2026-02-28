<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Figure & Model Kit', 'slug' => 'figure-model-kit'],
            ['name' => 'Kostum / Cosplay', 'slug' => 'kostum-cosplay'],
            ['name' => 'Merchandise', 'slug' => 'merchandise'],
            ['name' => 'Acrylic Stand', 'slug' => 'acrylic-stand'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], ['name' => $cat['name']]);
        }
    }
}
