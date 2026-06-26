<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List all orders with filters.
     */
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'farmer', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('farmer', function ($fq) use ($search) {
                      $fq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * View order details.
     */
    public function show($id)
    {
        $order = Order::with(['buyer', 'farmer', 'items.product', 'reviews', 'messages.sender'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Resolve a disputed order.
     */
    public function resolveDispute(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,refunded,delivered',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $updateData = ['status' => $validated['status']];

        if (isset($validated['admin_notes'])) {
            $updateData['farmer_notes'] = ($order->farmer_notes ? $order->farmer_notes . "\n\n" : '')
                . "[Admin Resolution] " . $validated['admin_notes'];
        }

        if ($validated['status'] === 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order dispute resolved. Status updated to ' . ucfirst($validated['status']) . '.');
    }
}
