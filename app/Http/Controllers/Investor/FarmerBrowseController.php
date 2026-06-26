<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvestmentApplication;
use Illuminate\Http\Request;

class FarmerBrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'farmer')->where('status', 'active');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('farm_name', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('state', 'LIKE', "%{$search}%");
            });
        }

        $farmers = $query->withCount('products')
            ->orderByDesc('products_count')
            ->paginate(12);

        return view('investor.farmers.index', compact('farmers'));
    }

    public function show($id)
    {
        $farmer = User::where('role', 'farmer')
            ->withCount(['products', 'reviews'])
            ->findOrFail($id);

        $products = $farmer->products()->where('status', 'active')->latest()->get();
        $applications = InvestmentApplication::where('user_id', $farmer->id)
            ->whereIn('status', ['submitted', 'under_review', 'approved', 'funded'])
            ->latest()
            ->get();

        return view('investor.farmers.show', compact('farmer', 'products', 'applications'));
    }

    public function invest(Request $request, $applicationId)
    {
        $application = InvestmentApplication::findOrFail($applicationId);

        if (!in_array($application->status, ['submitted', 'under_review', 'approved'])) {
            return back()->with('error', 'This application is not available for investment.');
        }

        $application->update([
            'status' => 'funded',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->input('notes', 'Funded by investor ' . auth()->user()->name),
        ]);

        return back()->with('success', 'Investment confirmed! The farmer has been notified.');
    }
}
