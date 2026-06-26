<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * List farmer's received orders.
     */
    public function index(Request $request)
    {
        $query = Order::where('farmer_id', Auth::id())
            ->with(['buyer', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('farmer.orders.index', compact('orders'));
    }

    /**
     * View order detail.
     */
    public function show($id)
    {
        $order = Order::where('farmer_id', Auth::id())
            ->with(['buyer', 'items.product', 'reviews'])
            ->findOrFail($id);

        return view('farmer.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('farmer_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
            'farmer_notes' => 'nullable|string|max:1000',
        ]);

        $updateData = ['status' => $validated['status']];

        if (isset($validated['farmer_notes'])) {
            $updateData['farmer_notes'] = $validated['farmer_notes'];
        }

        if ($validated['status'] === 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        return redirect()->route('farmer.orders.show', $order->id)
            ->with('success', 'Order status updated to ' . ucfirst($validated['status']) . '.');
    }
}
