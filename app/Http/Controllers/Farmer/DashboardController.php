<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the farmer dashboard with stats and recent orders.
     */
    public function index()
    {
        $user = Auth::user();

        $totalProducts = Product::where('user_id', $user->id)->count();

        $activeOrders = Order::where('farmer_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])
            ->count();

        $totalRevenue = Order::where('farmer_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total');

        $avgRating = Review::where('reviewed_user_id', $user->id)
            ->avg('rating');

        $recentOrders = Order::where('farmer_id', $user->id)
            ->with(['buyer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact(
            'totalProducts',
            'activeOrders',
            'totalRevenue',
            'avgRating',
            'recentOrders'
        ));
    }
}
