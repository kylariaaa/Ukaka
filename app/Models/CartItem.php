<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'rental_days',
    ];

    /**
     * Relasi ke user pemilik cart item ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke produk dalam cart item ini.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
