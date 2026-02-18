<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
    'order_id',
    'payment_method',
    'payment_type',      // TAMBAHKAN INI
    'dp_amount',         // TAMBAHKAN INI
    'remaining_amount',  // TAMBAHKAN INI
    'amount',
    'status',
    'proof_image',
    'notes',
    'paid_at'
];

    protected $casts = [
        'paid_at' => 'datetime'
    ];

    // Relasi: Payment punya 1 order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
