<?php

namespace App\Listeners;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class MergeSessionCartOnLogin
{
    /**
     * Dipanggil otomatis saat user berhasil login.
     * Menggabungkan cart dari session (jika ada) ke cart database user.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $sessionCart = session('cart', []);

        if (empty($sessionCart)) {
            return; // Tidak ada cart di session, tidak perlu merge
        }

        foreach ($sessionCart as $productId => $item) {
            $quantity   = (int) ($item['quantity'] ?? 1);
            $rentalDays = (int) ($item['rental_days'] ?? 1);

            // Cek apakah sudah ada di DB cart user
            $existing = CartItem::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                // Gabungkan: tambahkan quantity dari session ke yang sudah ada
                $existing->update([
                    'quantity'    => $existing->quantity + $quantity,
                    'rental_days' => $rentalDays, // pakai rental_days terbaru
                ]);
            } else {
                // Buat entry baru di DB
                CartItem::create([
                    'user_id'    => $user->id,
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'rental_days' => $rentalDays,
                ]);
            }
        }

        // Hapus session cart setelah di-merge ke DB
        session()->forget('cart');
    }
}
