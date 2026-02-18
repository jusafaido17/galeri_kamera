<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'duration',
        'rental_date',
        'rental_start',
        'rental_end',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'rental_date' => 'date',
        'rental_start' => 'datetime',
        'rental_end' => 'datetime'
    ];

    // Relasi: Order item punya 1 order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Order item punya 1 produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
