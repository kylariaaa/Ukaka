<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'discount_price',
        'stock', 'image', 'is_new', 'sale_type', 'price_per_day', 'category_id',
    ];

    protected $casts = [
        'is_new' => 'boolean',
    ];

    // Relasi kategori tunggal (baru)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi kategori pivot (lama, dipertahankan)
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Apakah produk ini kostum? (cocok dengan slug yang mengandung 'kostum' atau 'costume')
    public function isCostume(): bool
    {
        return $this->category &&
            (str_contains(strtolower($this->category->slug), 'kostum') ||
            str_contains(strtolower($this->category->slug), 'costume'));
    }

    // Apakah produk ini flash sale?
    public function isFlashSale(): bool
    {
        return $this->sale_type === 'flash_sale';
    }

    // Apakah produk ini lunar day?
    public function isLunarDay(): bool
    {
        return $this->sale_type === 'lunar_day';
    }

    public function getRupiahPriceAttribute(): string
    {
        return 'IDR ' . number_format($this->price, 0, ',', '.');
    }

    public function getRupiahDiscountPriceAttribute(): ?string
    {
        if ($this->discount_price) {
            return 'IDR ' . number_format($this->discount_price, 0, ',', '.');
        }
        return null;
    }

    public function getRupiahPricePerDayAttribute(): ?string
    {
        if ($this->price_per_day) {
            return 'IDR ' . number_format($this->price_per_day, 0, ',', '.');
        }
        return null;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->discount_price && $this->price > 0) {
            return (int)round((1 - $this->discount_price / $this->price) * 100);
        }
        return null;
    }

    // Harga efektif — jika ada discount_price selalu pakai itu, untuk kostum tanpa diskon pakai price_per_day
    public function getEffectivePriceAttribute(): int
    {
        if ($this->discount_price) {
            return $this->discount_price;
        }
        if ($this->isCostume() && $this->price_per_day) {
            return $this->price_per_day;
        }
        return $this->price;
    }
}
