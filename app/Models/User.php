<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'avatar',
        'status',
        'bio',
        'farm_name',
        'business_name',
        'company_name',
        'website',
        'investment_budget',
        'investment_interests',
        'subscription_plan',
        'is_featured',
        'profile_picture',
        'last_seen_at',
        'is_online',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'is_online' => 'boolean',
            'investment_budget' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    /**
     * Get the avatar URL, returning a default if null.
     */
    public function getAvatarAttribute($value): string
    {
        return $value ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    /**
     * Check if the user is a farmer.
     */
    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    /**
     * Check if the user is a buyer.
     */
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInvestor(): bool
    {
        return $this->role === 'investor';
    }

    public function isOnlineNow(): bool
    {
        if (!$this->last_seen_at) return false;
        return $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return $this->avatar;
    }

    /**
     * Get the products listed by this farmer.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the supplies listed by this user.
     */
    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    /**
     * Get orders where this user is the farmer (seller).
     */
    public function farmerOrders()
    {
        return $this->hasMany(Order::class, 'farmer_id');
    }

    /**
     * Get orders where this user is the buyer.
     */
    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Get messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get reviews written by this user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get transport options offered by this farmer.
     */
    public function transportOptions()
    {
        return $this->hasMany(TransportOption::class, 'farmer_id');
    }

    /**
     * Get the user's active subscription.
     */
    public function subscription()
    {
        return $this->hasOne(UserSubscription::class)->where('status', 'active')->latest();
    }

    /**
     * Get the user's investment applications.
     */
    public function investmentApplications()
    {
        return $this->hasMany(InvestmentApplication::class);
    }

    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    public function addBalance(float $amount, string $type, string $description, ?int $orderId = null, ?int $performedBy = null): BalanceTransaction
    {
        $this->increment('balance', $amount);
        $this->refresh();

        return BalanceTransaction::create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'related_order_id' => $orderId,
            'performed_by' => $performedBy,
        ]);
    }

    public function deductBalance(float $amount, string $type, string $description, ?int $orderId = null, ?int $performedBy = null): BalanceTransaction
    {
        $this->decrement('balance', $amount);
        $this->refresh();

        return BalanceTransaction::create([
            'user_id' => $this->id,
            'type' => $type,
            'amount' => -$amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'related_order_id' => $orderId,
            'performed_by' => $performedBy,
        ]);
    }

    public function hasEnoughBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Check if the user has a Pro subscription.
     */
    public function isPro(): bool
    {
        return $this->subscription_plan === 'pro';
    }

    /**
     * Check if the user has a Free subscription.
     */
    public function isFree(): bool
    {
        return $this->subscription_plan === 'free';
    }

    /**
     * Check if the user can access investment features.
     */
    public function canAccessInvestments(): bool
    {
        return $this->isPro();
    }

    /**
     * Check if the user can access credit features.
     */
    public function canAccessCredit(): bool
    {
        return $this->isPro();
    }

    /**
     * Check if the user can access analytics.
     */
    public function canAccessAnalytics(): bool
    {
        return $this->isPro();
    }

    /**
     * Get maximum number of listings allowed.
     */
    public function getMaxListings(): ?int
    {
        return $this->isPro() ? null : 5;
    }
}
