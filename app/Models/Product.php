<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'is_on_sale',
        'unit',
        'quantity_available',
        'min_order',
        'images',
        'featured',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_on_sale' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    public function getActivePriceAttribute(): float
    {
        return ($this->is_on_sale && $this->sale_price) ? (float)$this->sale_price : (float)$this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->is_on_sale || !$this->sale_price || $this->price <= 0) return null;
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Get the farmer who owns this product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the reviews for this product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
