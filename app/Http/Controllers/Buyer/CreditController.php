<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use App\Models\CreditOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    /**
     * Show buyer's credit dashboard.
     */
    public function index()
    {
        $activeCredit = CreditApplication::where('buyer_id', Auth::id())
            ->whereIn('status', ['active', 'approved'])
            ->first();

        $pendingApplication = CreditApplication::where('buyer_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        $creditOrders = CreditOrder::whereHas('creditApplication', function ($q) {
            $q->where('buyer_id', Auth::id());
        })->with(['order', 'creditApplication'])
          ->latest()
          ->get();

        $allApplications = CreditApplication::where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('buyer.credit.index', compact('activeCredit', 'pendingApplication', 'creditOrders', 'allApplications'));
    }

    /**
     * Show credit application form.
     */
    public function apply()
    {
        // Check if buyer already has an active or pending application
        $existing = CreditApplication::where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'active', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('buyer.credit.index')
                ->with('error', 'You already have an active or pending credit application.');
        }

        return view('buyer.credit.apply');
    }

    /**
     * Submit a credit application.
     */
    public function store(Request $request)
    {
        // Check if buyer already has an active or pending application
        $existing = CreditApplication::where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'active', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('buyer.credit.index')
                ->with('error', 'You already have an active or pending credit application.');
        }

        $validated = $request->validate([
            'credit_limit' => 'required|numeric|min:100|max:100000',
            'term_days' => 'required|in:30,60,90,120',
            'notes' => 'nullable|string|max:2000',
        ]);

        CreditApplication::create([
            'buyer_id' => Auth::id(),
            'status' => 'pending',
            'credit_limit' => $validated['credit_limit'],
            'available_credit' => 0, // Will be set on approval
            'term_days' => $validated['term_days'],
            'interest_rate' => 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('buyer.credit.index')
            ->with('success', 'Credit application submitted successfully! We will review it shortly.');
    }
}
