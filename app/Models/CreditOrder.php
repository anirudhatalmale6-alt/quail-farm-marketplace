<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'credit_application_id',
        'amount',
        'due_date',
        'status',
        'paid_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Get the order associated with this credit order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the credit application for this credit order.
     */
    public function creditApplication()
    {
        return $this->belongsTo(CreditApplication::class);
    }

    /**
     * Scope: overdue credit orders (past due date and still pending).
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', 'pending');
    }
}
