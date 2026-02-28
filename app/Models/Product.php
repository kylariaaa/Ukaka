<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'discount_price',
        'stock', 'image', 'is_new',
    ];

    protected $casts = [
        'is_new' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
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

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->discount_price && $this->price > 0) {
            return (int)round((1 - $this->discount_price / $this->price) * 100);
        }
        return null;
    }

    public function getEffectivePriceAttribute(): int
    {
        return $this->discount_price ?? $this->price;
    }
}
