<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_code', 'total_price', 'status', 'payment_method',
        'address', 'sla_deadline',
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRupiahTotalPriceAttribute(): string
    {
        return 'IDR ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Jam tersisa sebelum SLA expired. Negatif jika sudah lewat.
     */
    public function getRemainingHoursAttribute(): int
    {
        if (!$this->sla_deadline) {
            return 0;
        }
        return (int) now()->diffInHours($this->sla_deadline, false);
    }

    /**
     * Apakah pesanan ini sudah melewati batas SLA?
     */
    public function getIsSlaExpiredAttribute(): bool
    {
        return $this->sla_deadline && now()->isAfter($this->sla_deadline);
    }
}
