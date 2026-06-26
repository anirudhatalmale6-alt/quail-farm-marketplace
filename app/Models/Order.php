<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'buyer_id',
        'farmer_id',
        'subtotal',
        'commission_rate',
        'commission_amount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'buyer_notes',
        'farmer_notes',
        'delivered_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'commission_rate' => 'decimal:2',
        ];
    }

    /**
     * Boot the model and auto-generate order_number on creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'QFM-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the buyer who placed this order.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the farmer fulfilling this order.
     */
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * Get the items in this order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the reviews for this order.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the messages related to this order.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the transactions for this order.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
