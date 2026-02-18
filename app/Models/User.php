<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'member_level',
        'total_completed_orders',
        'member_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================================
    // RELASI
    // ========================================

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ========================================
    // ROLE & PERMISSION
    // ========================================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ========================================
    // MEMBER CODE MANAGEMENT
    // ========================================

    /**
     * Generate unique member code
     */
    public static function generateMemberCode($level = 'bronze')
    {
        $prefix = strtoupper(substr($level, 0, 3)); // BRO, SIL, PLA

        do {
            $number = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $code = "MBR-{$prefix}-{$number}";
        } while (self::where('member_code', $code)->exists());

        return $code;
    }

    // ========================================
    // MEMBER LEVEL MANAGEMENT
    // ========================================

    /**
     * Update member level based on completed orders
     */
   public function updateMemberLevel()
{
    $completedOrders = $this->orders()->where('status', 'completed')->count();

    $this->total_completed_orders = $completedOrders;

    $oldLevel = $this->member_level;

    if ($completedOrders >= 200) {
        $this->member_level = 'platinum';
    } elseif ($completedOrders >= 100) {
        $this->member_level = 'silver';
    } else {
        $this->member_level = 'bronze';
    }

    // Generate new member code if level changed
    if ($oldLevel != $this->member_level) {
        $this->member_code = self::generateMemberCode($this->member_level);
    }

    $this->save();
}

    /**
     * Get member level info
     */
    public function getMemberLevelInfo()
    {
        $levels = [
            'bronze' => [
                'name' => 'Bronze',
                'icon' => 'fas fa-medal',
                'color' => '#CD7F32',
                'bg_color' => '#FEF3C7',
                'text_color' => '#92400E',
                'min_orders' => 0,
                'max_orders' => 99,
                'benefits' => [
                    'Akses katalog lengkap',
                    'Dukungan pelanggan standar',
                ]
            ],
            'silver' => [
                'name' => 'Silver',
                'icon' => 'fas fa-trophy',
                'color' => '#C0C0C0',
                'bg_color' => '#E5E7EB',
                'text_color' => '#374151',
                'min_orders' => 100,
                'max_orders' => 199,
                'benefits' => [
                    'Semua benefit Bronze',
                    'Diskon 5% untuk setiap transaksi',
                    'Prioritas booking',
                    'Dukungan pelanggan prioritas',
                ]
            ],
            'platinum' => [
                'name' => 'Platinum',
                'icon' => 'fas fa-crown',
                'color' => '#E5E4E2',
                'bg_color' => '#EDE9FE',
                'text_color' => '#5B21B6',
                'min_orders' => 200,
                'max_orders' => null,
                'benefits' => [
                    'Semua benefit Silver',
                    'Diskon 10% untuk setiap transaksi',
                    'Akses early bird produk baru',
                    'Free upgrade alat (jika tersedia)',
                    'Dukungan pelanggan VIP 24/7',
                ]
            ],
        ];

        return $levels[$this->member_level];
    }

    /**
     * Get progress to next level
     */
    public function getProgressToNextLevel()
    {
        $current = $this->total_completed_orders;

        if ($this->member_level == 'bronze') {
            $target = 100;
            $progress = $target > 0 ? ($current / $target) * 100 : 0;
            return [
                'current' => $current,
                'target' => $target,
                'remaining' => max(0, $target - $current),
                'percentage' => min(100, $progress),
                'next_level' => 'Silver'
            ];
        } elseif ($this->member_level == 'silver') {
            $target = 200;
            $progress = $target > 0 ? ($current / $target) * 100 : 0;
            return [
                'current' => $current,
                'target' => $target,
                'remaining' => max(0, $target - $current),
                'percentage' => min(100, $progress),
                'next_level' => 'Platinum'
            ];
        } else {
            // Platinum - sudah max level
            return [
                'current' => $current,
                'target' => null,
                'remaining' => 0,
                'percentage' => 100,
                'next_level' => null
            ];
        }
    }

    // ========================================
    // DISCOUNT CALCULATION (FUTURE FEATURE)
    // ========================================

    /**
     * Get discount percentage based on member level
     */
    public function getDiscountPercentage()
    {
        $discounts = [
            'bronze' => 0,
            'silver' => 5,
            'platinum' => 10,
        ];

        return $discounts[$this->member_level] ?? 0;
    }

    /**
     * Calculate discounted price
     */
    public function calculateDiscountedPrice($originalPrice)
    {
        $discountPercentage = $this->getDiscountPercentage();
        $discountAmount = ($originalPrice * $discountPercentage) / 100;
        $finalPrice = $originalPrice - $discountAmount;

        return [
            'original_price' => $originalPrice,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ];
    }
}
