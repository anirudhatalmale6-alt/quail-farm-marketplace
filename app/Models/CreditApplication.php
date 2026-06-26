<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'status',
        'credit_limit',
        'available_credit',
        'term_days',
        'interest_rate',
        'approved_by',
        'approved_at',
        'expires_at',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'available_credit' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the buyer who applied for credit.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the admin who approved this application.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the credit orders for this application.
     */
    public function creditOrders()
    {
        return $this->hasMany(CreditOrder::class);
    }

    /**
     * Scope: active credit applications.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: pending credit applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
