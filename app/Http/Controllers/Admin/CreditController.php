<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use App\Models\CreditOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    /**
     * List all credit applications with filters.
     */
    public function index(Request $request)
    {
        $query = CreditApplication::with(['buyer', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return view('admin.credits.index', compact('applications'));
    }

    /**
     * View credit application details.
     */
    public function show($id)
    {
        $application = CreditApplication::with(['buyer', 'approver', 'creditOrders.order'])
            ->findOrFail($id);

        return view('admin.credits.show', compact('application'));
    }

    /**
     * Approve a credit application.
     */
    public function approve(Request $request, $id)
    {
        $application = CreditApplication::where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'credit_limit' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => 'active',
            'credit_limit' => $validated['credit_limit'],
            'available_credit' => $validated['credit_limit'],
            'interest_rate' => $validated['interest_rate'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'expires_at' => now()->addDays($application->term_days),
            'notes' => $validated['notes'] ?? $application->notes,
        ]);

        return redirect()->route('admin.credits.show', $application->id)
            ->with('success', 'Credit application approved successfully.');
    }

    /**
     * Reject a credit application.
     */
    public function reject(Request $request, $id)
    {
        $application = CreditApplication::where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => 'rejected',
            'notes' => $validated['notes'] ?? 'Application rejected.',
        ]);

        return redirect()->route('admin.credits.index')
            ->with('success', 'Credit application rejected.');
    }
}
