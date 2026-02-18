<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'specifications',
        'image',
        'stock',
        'price_6_hours',
        'price_12_hours',
        'price_24_hours',
        'price_1_5_days',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * ACCESSOR
     * Menjamin specifications selalu ARRAY
     * walaupun data di DB masih string / JSON ber-escape
     */
    public function getSpecificationsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        // Jika masih string (seperti di DB kamu)
        if (is_string($value)) {
            // buang tanda kutip luar
            $value = trim($value, '"');

            // hilangkan escape karakter \"
            $value = stripslashes($value);
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }

    // ================= RELATIONSHIPS =================

    // Produk punya 1 kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Produk bisa ada di banyak cart
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Produk bisa ada di banyak order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ================= HELPER =================

    // Ambil harga berdasarkan durasi sewa
    public function getPriceByDuration($duration)
    {
        return match ($duration) {
            '6_hours'   => $this->price_6_hours,
            '12_hours'  => $this->price_12_hours,
            '24_hours'  => $this->price_24_hours,
            '1_5_days'  => $this->price_1_5_days,
            default     => 0,
        };
    }
}
