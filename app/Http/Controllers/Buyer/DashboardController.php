<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the buyer dashboard with stats and recent orders.
     */
    public function index()
    {
        $user = Auth::user();

        $totalOrders = Order::where('buyer_id', $user->id)->count();

        $pendingDeliveries = Order::where('buyer_id', $user->id)
            ->whereIn('status', ['confirmed', 'processing', 'shipped'])
            ->count();

        $totalSpent = Order::where('buyer_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total');

        $favoriteFarmers = Order::where('buyer_id', $user->id)
            ->distinct('farmer_id')
            ->count('farmer_id');

        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['farmer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('buyer.dashboard', compact(
            'totalOrders',
            'pendingDeliveries',
            'totalSpent',
            'favoriteFarmers',
            'recentOrders'
        ));
    }
}
