<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestmentApplication;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_farmers' => User::where('role', 'farmer')->where('status', 'active')->count(),
            'open_applications' => InvestmentApplication::where('status', 'submitted')->orWhere('status', 'under_review')->count(),
            'my_investments' => InvestmentApplication::where('reviewed_by', $user->id)->where('status', 'funded')->count(),
            'total_invested' => InvestmentApplication::where('reviewed_by', $user->id)->where('status', 'funded')->sum('amount_requested'),
        ];

        $recentApplications = InvestmentApplication::with('user:id,name,farm_name,avatar,city,state')
            ->whereIn('status', ['submitted', 'under_review'])
            ->latest()
            ->take(5)
            ->get();

        $topFarmers = User::where('role', 'farmer')
            ->where('status', 'active')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        $myInvestments = InvestmentApplication::with('user:id,name,farm_name,avatar')
            ->where('reviewed_by', $user->id)
            ->where('status', 'funded')
            ->latest()
            ->take(5)
            ->get();

        return view('investor.dashboard', compact('stats', 'recentApplications', 'topFarmers', 'myInvestments'));
    }
}
