<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AutoRejectStaleOrders extends Command
{
    /**
     * Nama command artisan.
     */
    protected $signature = 'orders:auto-reject';

    /**
     * Deskripsi command.
     */
    protected $description = 'Otomatis menolak (expired) pesanan yang melebihi batas SLA 7 hari tanpa respons admin.';

    public function handle(): int
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Order> $stale */
        $stale = Order::where('status', 'process')
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->with('orderItems.product')
            ->get();

        if ($stale->isEmpty()) {
            $this->info('Tidak ada pesanan expired. Semua dalam batas SLA.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($stale as $order) {
            // Kembalikan stok untuk produk biasa (bukan kostum/rental)
            foreach ($order->orderItems as $item) {
                if ($item->product && is_null($item->rental_days)) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Tandai sebagai expired
            Order::where('id', $order->id)->update(['status' => 'expired']);
            $count++;
            $this->line("  → Order #{$order->order_code} ditandai EXPIRED (SLA: {$order->sla_deadline})");
        }

        $this->info("✅ {$count} pesanan telah di-expire karena melewati batas SLA 7 hari.");
        return self::SUCCESS;
    }
}
