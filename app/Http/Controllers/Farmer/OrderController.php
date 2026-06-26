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

    /**
     * Farmer confirms they received payment.
     */
    public function confirmPayment(Request $request, $id)
    {
        $order = Order::where('farmer_id', Auth::id())
            ->findOrFail($id);

        $order->update([
            'seller_payment_confirmed' => true,
            'seller_payment_confirmed_at' => now(),
        ]);

        // Auto-update payment_status if both parties confirmed
        if ($order->buyer_payment_confirmed) {
            $order->update(['payment_status' => 'paid']);
        }

        return redirect()->route('farmer.orders.show', $order->id)
            ->with('success', 'Payment receipt confirmed!' . ($order->buyer_payment_confirmed ? ' Payment marked as complete.' : ' Waiting for buyer to confirm payment.'));
    }
}
