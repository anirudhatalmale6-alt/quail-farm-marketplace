<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with platform statistics.
     */
    public function index()
    {
        $totalFarmers = User::where('role', 'farmer')->count();
        $totalBuyers = User::where('role', 'buyer')->count();
        $totalOrders = Order::count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

        $totalCommission = Order::where('payment_status', 'paid')->sum('commission_amount');

        $pendingUsers = User::where('status', 'pending')->count();

        $recentSignups = User::latest()
            ->take(10)
            ->get();

        $pendingApprovals = User::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with(['buyer', 'farmer'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'totalBuyers',
            'totalOrders',
            'totalRevenue',
            'totalCommission',
            'pendingUsers',
            'recentSignups',
            'pendingApprovals',
            'recentOrders'
        ));
    }
}
