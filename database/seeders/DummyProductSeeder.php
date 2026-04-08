<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyProductSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua produk lama agar tidak dobel saat seed ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Pastikan kategori ada
        $this->call(CategorySeeder::class);

        $catFigure      = Category::where('slug', 'figure-model-kit')->first();
        $catCosplay     = Category::where('slug', 'kostum-cosplay')->first();
        $catMerchandise = Category::where('slug', 'merchandise')->first();
        $catAcrylic     = Category::where('slug', 'acrylic-stand')->first();

        // ─────────────────────────────────────────────────────────────────────
        // 1. FIGURE & MODEL KIT
        //    Gambar: figure.webp, figure1.png, figure3.webp,
        //            figure4.webp, figure5.webp, modelkit.webp, modelkit1.jpg
        // ─────────────────────────────────────────────────────────────────────
        $figures = [
            [
                'name'          => 'HG 1/144 RX-78-2 Gundam',
                'image'         => 'images/figure.webp',
                'price'         => 250000,
                'discount_price'=> null,
                'stock'         => 20,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'High Grade kit ikonik RX-78-2, cocok untuk pemula. Detail panel line tajam dengan aksesoris beam rifle dan shield.',
            ],
            [
                'name'          => 'MG 1/100 Strike Freedom Gundam',
                'image'         => 'images/figure1.png',
                'price'         => 850000,
                'discount_price'=> 720000,
                'stock'         => 10,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Master Grade dengan inner frame lengkap, wing buster bisa deploy, dan fleksibilitas sendi tinggi.',
            ],
            [
                'name'          => 'RG 1/144 Unicorn Gundam',
                'image'         => 'images/figure3.webp',
                'price'         => 450000,
                'discount_price'=> null,
                'stock'         => 15,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Real Grade Unicorn dengan psycoframe yang bisa berganti mode Unicorn ke Destroy.',
            ],
            [
                'name'          => 'PG 1/60 Exia Gundam',
                'image'         => 'images/figure4.webp',
                'price'         => 3200000,
                'discount_price'=> 2800000,
                'stock'         => 3,
                'sale_type'     => 'lunar_day',
                'is_new'        => false,
                'description'   => 'Perfect Grade Exia dengan LED kit built-in, sendi GN Drive bergerak, dan detail tertinggi.',
            ],
            [
                'name'          => 'SD BB Sazabi Ver.Ka',
                'image'         => 'images/figure5.webp',
                'price'         => 180000,
                'discount_price'=> null,
                'stock'         => 25,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Super Deformed Sazabi versi chibi dengan finnel yang bisa dilepas, ringan dan mudah dirakit.',
            ],
            [
                'name'          => 'MG 1/100 Sinanju Stein Ver.Ka',
                'image'         => 'images/modelkit.webp',
                'price'         => 1100000,
                'discount_price'=> 950000,
                'stock'         => 8,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Model kit Sinanju Stein dengan decal waterslide premium dan warna dua tone abu-putih eksklusif.',
            ],
            [
                'name'          => 'HG 1/144 Barbatos Lupus Rex',
                'image'         => 'images/modelkit1.jpg',
                'price'         => 320000,
                'discount_price'=> null,
                'stock'         => 18,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'HG Barbatos Lupus Rex dari IBO season 2 dengan Chainsaw Mace dan detail cakar besar.',
            ],
        ];

        foreach ($figures as $data) {
            Product::create(array_merge($data, [
                'slug'          => Str::slug($data['name']),
                'price_per_day' => null,
                'category_id'   => $catFigure->id,
            ]));
        }

        // ─────────────────────────────────────────────────────────────────────
        // 2. KOSTUM / COSPLAY
        //    Gambar: cosplay.png, cosplay1.webp, cosplay2.jpg,
        //            cosplay3.jpg, cosplay4.avif
        // ─────────────────────────────────────────────────────────────────────
        $cosplays = [
            [
                'name'          => 'Kostum Naruto Uzumaki – Sage Mode',
                'image'         => 'images/cosplay.png',
                'price'         => 350000,
                'discount_price'=> null,
                'stock'         => 5,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Kostum cosplay Naruto Sage Mode lengkap dengan jubah, headband, dan sandal ninja.',
            ],
            [
                'name'          => 'Kostum Demon Slayer – Tanjiro Kamado',
                'image'         => 'images/cosplay1.webp',
                'price'         => 420000,
                'discount_price'=> 370000,
                'stock'         => 7,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Kostum Tanjiro Kamado lengkap dengan haori kotak-kotak khas dan aksesori pedang.',
            ],
            [
                'name'          => 'Kostum Spy x Family – Yor Forger',
                'image'         => 'images/cosplay2.jpg',
                'price'         => 480000,
                'discount_price'=> null,
                'stock'         => 4,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Kostum Yor Forger dengan gaun merah elegan dan mahkota duri ikonik.',
            ],
            [
                'name'          => 'Kostum Attack on Titan – Survey Corps',
                'image'         => 'images/cosplay3.jpg',
                'price'         => 550000,
                'discount_price'=> 480000,
                'stock'         => 6,
                'sale_type'     => 'lunar_day',
                'is_new'        => false,
                'description'   => 'Seragam lengkap Survey Corps dari AoT dengan jaket hijau Wings of Freedom.',
            ],
            [
                'name'          => 'Kostum Chainsaw Man – Makima',
                'image'         => 'images/cosplay4.avif',
                'price'         => 400000,
                'discount_price'=> null,
                'stock'         => 3,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Kostum Makima dari Chainsaw Man dengan kemeja putih dan tali kontrol ikonik.',
            ],
        ];

        foreach ($cosplays as $data) {
            Product::create(array_merge($data, [
                'slug'          => Str::slug($data['name']),
                'price_per_day' => null,
                'category_id'   => $catCosplay->id,
            ]));
        }

        // ─────────────────────────────────────────────────────────────────────
        // 3. MERCHANDISE
        //    Gambar: merchhendise.jpg, merch endise2.jpg, merchendise3.jpg,
        //            merchendise4.webp, merchendise5.webp, mechendise6.webp
        // ─────────────────────────────────────────────────────────────────────
        $merchandises = [
            [
                'name'          => 'Kaos Anime – One Piece Luffy Gear 5',
                'image'         => 'images/merchhendise.jpg',
                'price'         => 120000,
                'discount_price'=> null,
                'stock'         => 30,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Kaos premium bahan cotton combed 30s dengan print DTF Luffy Gear 5 full color.',
            ],
            [
                'name'          => 'Mug Keramik – Demon Slayer Hashira',
                'image'         => 'images/merchendise2.jpg',
                'price'         => 95000,
                'discount_price'=> 80000,
                'stock'         => 20,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Mug keramik 350ml dengan desain seluruh Hashira dari Demon Slayer, dishwasher safe.',
            ],
            [
                'name'          => 'Gantungan Kunci – Bocchi the Rock',
                'image'         => 'images/merchendise3.jpg',
                'price'         => 45000,
                'discount_price'=> null,
                'stock'         => 50,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Gantungan kunci akrilik double-side cetak karakter Bocchi dan Kikuri.',
            ],
            [
                'name'          => 'Poster A2 – Jujutsu Kaisen Season 2',
                'image'         => 'images/merchendise4.webp',
                'price'         => 65000,
                'discount_price'=> 55000,
                'stock'         => 40,
                'sale_type'     => 'lunar_day',
                'is_new'        => false,
                'description'   => 'Poster premium A2 glossy Jujutsu Kaisen Season 2 arc Shibuya, tahan lama.',
            ],
            [
                'name'          => 'Totebag Canvas – Haikyuu Characters',
                'image'         => 'images/merchendise5.webp',
                'price'         => 85000,
                'discount_price'=> null,
                'stock'         => 25,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Totebag canvas tebal dengan print karakter Haikyuu, cocok untuk daily use.',
            ],
            [
                'name'          => 'Pin Badge Set – Spy x Family (5 pcs)',
                'image'         => 'images/mechendise6.webp',
                'price'         => 55000,
                'discount_price'=> 45000,
                'stock'         => 60,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Set 5 pin badge enamel karakter utama Spy x Family: Loid, Yor, Anya, Bond, Yuri.',
            ],
        ];

        foreach ($merchandises as $data) {
            Product::create(array_merge($data, [
                'slug'          => Str::slug($data['name']),
                'price_per_day' => null,
                'category_id'   => $catMerchandise->id,
            ]));
        }

        // ─────────────────────────────────────────────────────────────────────
        // 4. ACRYLIC STAND
        //    Gambar: arcylicstand1.jpg
        // ─────────────────────────────────────────────────────────────────────
        $acrylics = [
            [
                'name'          => 'Acrylic Stand – Genshin Impact Hu Tao',
                'image'         => 'images/arcylicstand1.jpg',
                'price'         => 75000,
                'discount_price'=> null,
                'stock'         => 35,
                'sale_type'     => 'none',
                'is_new'        => true,
                'description'   => 'Akrilik stand karakter Hu Tao dari Genshin Impact, ukuran 15cm dengan base anti-slip.',
            ],
            [
                'name'          => 'Acrylic Stand – Honkai Star Rail Bronya',
                'image'         => 'images/arcylicstand1.jpg',
                'price'         => 75000,
                'discount_price'=> 65000,
                'stock'         => 20,
                'sale_type'     => 'flash_sale',
                'is_new'        => false,
                'description'   => 'Akrilik stand Bronya dari Honkai Star Rail dengan desain eksklusif limited edition.',
            ],
            [
                'name'          => 'Acrylic Stand – Blue Archive Hina',
                'image'         => 'images/arcylicstand1.jpg',
                'price'         => 70000,
                'discount_price'=> null,
                'stock'         => 28,
                'sale_type'     => 'lunar_day',
                'is_new'        => true,
                'description'   => 'Akrilik stand Hina dari Blue Archive, cetak UV full color tahan pudar.',
            ],
        ];

        foreach ($acrylics as $data) {
            Product::create(array_merge($data, [
                'slug'          => Str::slug($data['name']),
                'price_per_day' => null,
                'category_id'   => $catAcrylic->id,
            ]));
        }

        $this->command->info('✅ DummyProductSeeder selesai: ' . Product::count() . ' produk berhasil dibuat.');
    }
}
