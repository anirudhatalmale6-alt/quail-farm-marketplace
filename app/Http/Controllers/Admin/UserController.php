<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * List all users with role filter, search, and pagination.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('farm_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * View a user's profile details.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $totalOrders = 0;
        $totalRevenue = 0;

        if ($user->isFarmer()) {
            $totalOrders = Order::where('farmer_id', $user->id)->count();
            $totalRevenue = Order::where('farmer_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total');
        } elseif ($user->isBuyer()) {
            $totalOrders = Order::where('buyer_id', $user->id)->count();
            $totalRevenue = Order::where('buyer_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total');
        }

        $recentOrders = Order::where('buyer_id', $user->id)
            ->orWhere('farmer_id', $user->id)
            ->with(['buyer', 'farmer'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'totalOrders', 'totalRevenue', 'recentOrders'));
    }

    /**
     * Approve or suspend a user.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,pending,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', "User {$user->name} status updated to {$validated['status']}.");
    }

    public function adjustBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'action' => 'required|in:add,deduct',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'reason' => 'required|string|max:500',
        ]);

        $amount = (float) $validated['amount'];

        if ($validated['action'] === 'deduct' && !$user->hasEnoughBalance($amount)) {
            return back()->with('error', 'User does not have enough balance. Current: $' . number_format($user->balance, 2));
        }

        if ($validated['action'] === 'add') {
            $user->addBalance($amount, 'admin_credit', $validated['reason'], null, Auth::id());
        } else {
            $user->deductBalance($amount, 'admin_debit', $validated['reason'], null, Auth::id());
        }

        return back()->with('success', ($validated['action'] === 'add' ? 'Added' : 'Deducted') . ' $' . number_format($amount, 2) . ' ' . ($validated['action'] === 'add' ? 'to' : 'from') . ' ' . $user->name . "'s balance.");
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} has been deleted.");
    }
}
