<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure categories exist
        $this->call(CategorySeeder::class);

        $categoryIds = Category::pluck('id')->toArray();

        // 8 available mech-produk images — cycle through them
        $totalImages = 8;

        $prefixes = [
            'Mecha',
            'HG',
            'MG',
            'RG',
            'PG',
            'SD',
            'RE',
            'HGUC',
            'HGCE',
            'MG Ver.Ka',
            'RG Hi-Nu',
            'MG Astray',
            'HG Zaku',
        ];

        $suffixes = [
            'Gundam',
            'Zaku II',
            'Strike Freedom',
            'Destiny',
            'Wing Zero',
            'Exia',
            'Barbatos',
            'Tallgeese',
            'Epyon',
            'Nu Gundam',
            'Sazabi',
            'F91',
            'Sinanju',
            'Unicorn',
            'Banshee',
            'Crossbone',
            'Turn A',
            'Heavyarms',
            'Deathscythe',
            'Sandrock',
        ];

        $descriptions = [
            'Model kit skala tinggi dengan detail part premium dan decal waterslide.',
            'Kit edisi terbatas dengan runner warna ekstra dan aksesoris eksklusif.',
            'Versi remaster dengan sambungan yang lebih fleksibel dan tampilan modern.',
            'High Grade kit dengan kemasan spesial anniversary, detail tajam dan presisi.',
            'Master Grade dengan inner frame lengkap dan kaki posable multi-sendi.',
            'Perfect Grade detail tertinggi dengan LED kit opsional dan panel line halus.',
            'Versi SD chibi koleksi, ringan dan mudah dirakit cocok untuk pemula.',
            'Real Grade dengan frame terpisah dan akurasi desain mendekati skala 1:1.',
        ];

        $salTypes = ['none', 'none', 'none', 'flash_sale', 'lunar_day'];
        $isNewFlags = [true, true, false, false, false];

        $used = Product::pluck('slug')->toArray();

        $count = 0;
        $i = 1;

        while ($count < 50) {
            $prefix = $prefixes[array_rand($prefixes)];
            $suffix = $suffixes[array_rand($suffixes)];
            $vol = rand(1, 99);
            $name = "{$prefix} {$suffix} Vol.{$vol}";
            $slug = Str::slug($name);

            // Skip if slug already exists (collision)
            if (in_array($slug, $used)) {
                $i++;
                continue;
            }

            $used[] = $slug;

            $imageNum = (($count) % $totalImages) + 1;
            $imagePath = "images/mech-produk ({$imageNum}).webp";

            $price = rand(200, 3500) * 1000;
            $hasDiscount = rand(0, 2) === 0; // ~33% chance
            $discountPrice = $hasDiscount ? (int) ($price * rand(70, 90) / 100 / 1000) * 1000 : null;

            $saleType = $salTypes[array_rand($salTypes)];
            $isNew = $isNewFlags[array_rand($isNewFlags)];
            $stock = rand(1, 30);
            $catId = $categoryIds[array_rand($categoryIds)];

            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $descriptions[array_rand($descriptions)],
                'price' => $price,
                'discount_price' => $discountPrice,
                'stock' => $stock,
                'image' => $imagePath,
                'is_new' => $isNew,
                'sale_type' => $saleType,
                'price_per_day' => null,
                'category_id' => $catId,
            ]);

            // Also sync pivot if product has categories() many-to-many
            if (method_exists($product, 'categories')) {
                try {
                    $product->categories()->syncWithoutDetaching([$catId]);
                } catch (\Exception $e) {
                    // categories() might be a belongsTo, ignore if not pivot
                }
            }

            $count++;
            $i++;
        }
    }
}
