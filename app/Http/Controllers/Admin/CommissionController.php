<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * List all commission brackets.
     */
    public function index()
    {
        $commissions = CommissionSetting::orderBy('min_order_amount')->get();

        return view('admin.commissions.index', compact('commissions'));
    }

    /**
     * Store a new commission bracket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_order_amount' => 'required|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|gt:min_order_amount',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        CommissionSetting::create($validated);

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission bracket created successfully.');
    }

    /**
     * Update an existing commission bracket.
     */
    public function update(Request $request, $id)
    {
        $commission = CommissionSetting::findOrFail($id);

        $validated = $request->validate([
            'min_order_amount' => 'required|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|gt:min_order_amount',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $commission->update($validated);

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission bracket updated successfully.');
    }

    /**
     * Delete a commission bracket.
     */
    public function destroy($id)
    {
        $commission = CommissionSetting::findOrFail($id);
        $commission->delete();

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission bracket deleted successfully.');
    }
}
