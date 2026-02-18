<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'duration',
        'rental_date',
        'rental_start',
        'rental_end',
        'price'
    ];

    protected $casts = [
        'rental_date' => 'date',
        'rental_start' => 'datetime',
        'rental_end' => 'datetime'
    ];

    // Relasi: Cart punya 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Cart punya 1 produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Method untuk hitung subtotal
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
