<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // Produk yang di-assign melalui category_id langsung (dipakai admin)
    public function productsDirect()
    {
        return $this->hasMany(Product::class);
    }
}
