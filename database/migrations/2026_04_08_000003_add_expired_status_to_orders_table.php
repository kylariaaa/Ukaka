<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah nilai 'expired' ke enum status di tabel orders.
     * Kompatibel dengan MySQL (menggunakan MODIFY COLUMN).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('process', 'finished', 'rejected', 'expired') DEFAULT 'process'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('process', 'finished', 'rejected') DEFAULT 'process'");
    }
};
