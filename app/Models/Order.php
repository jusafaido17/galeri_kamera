<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'notes'
    ];

    // Relasi: Order punya 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order punya banyak order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order punya 1 payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Method untuk generate order number otomatis
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
