<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentApplication;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * List all investment applications with status filter.
     */
    public function index(Request $request)
    {
        $query = InvestmentApplication::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderByDesc('created_at')->paginate(15);
        $statusCounts = [
            'all' => InvestmentApplication::count(),
            'submitted' => InvestmentApplication::where('status', 'submitted')->count(),
            'under_review' => InvestmentApplication::where('status', 'under_review')->count(),
            'approved' => InvestmentApplication::where('status', 'approved')->count(),
            'rejected' => InvestmentApplication::where('status', 'rejected')->count(),
            'funded' => InvestmentApplication::where('status', 'funded')->count(),
        ];

        return view('admin.investments.index', compact('applications', 'statusCounts'));
    }

    /**
     * View application detail.
     */
    public function show($id)
    {
        $application = InvestmentApplication::with(['user', 'reviewer'])->findOrFail($id);
        return view('admin.investments.show', compact('application'));
    }

    /**
     * Update status (approve/reject with notes).
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:under_review,approved,rejected,funded',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $application = InvestmentApplication::findOrFail($id);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.investments.show', $id)->with('success', 'Application status updated to ' . str_replace('_', ' ', $request->status) . '.');
    }
}
