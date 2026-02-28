<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tipe sale: none, flash_sale, lunar_day
            $table->enum('sale_type', ['none', 'flash_sale', 'lunar_day'])->default('none')->after('is_new');
            // Harga sewa per hari untuk kategori costume
            $table->integer('price_per_day')->nullable()->after('sale_type');
            // Kategori tunggal langsung di tabel products
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('price_per_day');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['sale_type', 'price_per_day', 'category_id']);
        });
    }
};
