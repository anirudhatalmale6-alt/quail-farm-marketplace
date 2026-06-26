<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\InvestmentApplication;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * List farmer's investment applications.
     */
    public function index()
    {
        $applications = InvestmentApplication::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('farmer.investments.index', compact('applications'));
    }

    /**
     * Show application form (Pro only).
     */
    public function create()
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('pricing')->with('error', 'Investment applications require a Pro subscription. Please upgrade your plan.');
        }

        return view('farmer.investments.create');
    }

    /**
     * Submit investment application.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('pricing')->with('error', 'Investment applications require a Pro subscription.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'business_plan' => 'required|string|min:50',
            'financial_projections' => 'nullable|string',
            'amount_requested' => 'required|numeric|min:100|max:999999999999',
            'current_farm_size' => 'nullable|string|max:255',
            'expected_roi' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240',
            'status' => 'nullable|in:draft,submitted',
        ]);

        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documentPaths[] = $file->store('investments/documents', 'public');
            }
        }

        $application = InvestmentApplication::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'business_plan' => $validated['business_plan'],
            'financial_projections' => $validated['financial_projections'] ?? null,
            'amount_requested' => $validated['amount_requested'],
            'current_farm_size' => $validated['current_farm_size'] ?? null,
            'expected_roi' => $validated['expected_roi'] ?? null,
            'timeline' => $validated['timeline'] ?? null,
            'documents' => $documentPaths ?: null,
            'status' => $request->input('status', 'submitted'),
        ]);

        $message = $application->status === 'draft'
            ? 'Investment application saved as draft.'
            : 'Investment application submitted successfully!';

        return redirect()->route('farmer.investments.show', $application->id)->with('success', $message);
    }

    /**
     * View application status and details.
     */
    public function show($id)
    {
        $application = InvestmentApplication::where('user_id', auth()->id())
            ->with('reviewer')
            ->findOrFail($id);

        return view('farmer.investments.show', compact('application'));
    }

    /**
     * Edit draft application.
     */
    public function edit($id)
    {
        $application = InvestmentApplication::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        return view('farmer.investments.create', compact('application'));
    }

    /**
     * Update draft application.
     */
    public function update(Request $request, $id)
    {
        $application = InvestmentApplication::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'business_plan' => 'required|string|min:50',
            'financial_projections' => 'nullable|string',
            'amount_requested' => 'required|numeric|min:100|max:999999999999',
            'current_farm_size' => 'nullable|string|max:255',
            'expected_roi' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240',
            'status' => 'nullable|in:draft,submitted',
        ]);

        $documentPaths = $application->documents ?? [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documentPaths[] = $file->store('investments/documents', 'public');
            }
        }

        $application->update([
            'title' => $validated['title'],
            'business_plan' => $validated['business_plan'],
            'financial_projections' => $validated['financial_projections'] ?? null,
            'amount_requested' => $validated['amount_requested'],
            'current_farm_size' => $validated['current_farm_size'] ?? null,
            'expected_roi' => $validated['expected_roi'] ?? null,
            'timeline' => $validated['timeline'] ?? null,
            'documents' => $documentPaths ?: null,
            'status' => $request->input('status', 'draft'),
        ]);

        $message = $application->status === 'draft'
            ? 'Application updated and saved as draft.'
            : 'Application updated and submitted!';

        return redirect()->route('farmer.investments.show', $application->id)->with('success', $message);
    }
}
