<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan auto-reject setiap hari tengah malam
// Pesanan yang melebihi SLA 7 hari akan otomatis di-expire
Schedule::command('orders:auto-reject')->dailyAt('00:00');
