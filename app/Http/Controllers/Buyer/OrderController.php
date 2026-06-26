<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * List buyer's orders.
     */
    public function index(Request $request)
    {
        $query = Order::where('buyer_id', Auth::id())
            ->with(['farmer', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Create a new order from product page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:500',
            'buyer_notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::where('status', 'active')
            ->where('quantity_available', '>=', $validated['quantity'])
            ->findOrFail($validated['product_id']);

        // Enforce minimum order
        if ($validated['quantity'] < $product->min_order) {
            return back()->withErrors([
                'quantity' => "Minimum order is {$product->min_order} {$product->unit}(s)."
            ])->withInput();
        }

        $subtotal = $product->price * $validated['quantity'];

        // Calculate commission
        $commissionRate = CommissionSetting::getRate($subtotal) ?? 5.00;
        $commissionAmount = $subtotal * ($commissionRate / 100);
        $total = $subtotal + $commissionAmount;

        $order = DB::transaction(function () use ($validated, $product, $subtotal, $commissionRate, $commissionAmount, $total) {
            $order = Order::create([
                'buyer_id' => Auth::id(),
                'farmer_id' => $product->user_id,
                'subtotal' => $subtotal,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cod',
                'shipping_address' => $validated['shipping_address'],
                'buyer_notes' => $validated['buyer_notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            // Decrease product stock
            $product->decrement('quantity_available', $validated['quantity']);

            return $order;
        });

        return redirect()->route('buyer.orders.show', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    /**
     * View order details.
     */
    public function show($id)
    {
        $order = Order::where('buyer_id', Auth::id())
            ->with(['farmer', 'items.product', 'reviews'])
            ->findOrFail($id);

        return view('buyer.orders.show', compact('order'));
    }
}
