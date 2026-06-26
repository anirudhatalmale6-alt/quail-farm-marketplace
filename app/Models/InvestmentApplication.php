<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentApplication extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'business_plan',
        'financial_projections',
        'amount_requested',
        'current_farm_size',
        'expected_roi',
        'timeline',
        'documents',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'amount_requested' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
