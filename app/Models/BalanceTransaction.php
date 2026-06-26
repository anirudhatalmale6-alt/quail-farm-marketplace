<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference',
        'related_order_id',
        'performed_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function isCredit(): bool
    {
        return in_array($this->type, ['deposit', 'sale', 'refund', 'admin_credit']);
    }
}
