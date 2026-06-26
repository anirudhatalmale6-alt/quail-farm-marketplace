<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'min_order_amount',
        'max_order_amount',
        'rate',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_order_amount' => 'decimal:2',
            'max_order_amount' => 'decimal:2',
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the applicable commission rate for a given order amount.
     *
     * @param float $orderAmount
     * @return float|null
     */
    public static function getRate(float $orderAmount): ?float
    {
        $setting = static::where('is_active', true)
            ->where('min_order_amount', '<=', $orderAmount)
            ->where('max_order_amount', '>=', $orderAmount)
            ->first();

        return $setting?->rate;
    }
}
