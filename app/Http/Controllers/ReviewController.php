<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Submit a review after delivery.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Ensure the reviewer is the buyer of this order
        if ($order->buyer_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only review orders you placed.');
        }

        // Ensure order is delivered
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'You can only review delivered orders.');
        }

        // Check for existing review
        $existing = Review::where('order_id', $order->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'order_id' => $validated['order_id'],
            'reviewer_id' => Auth::id(),
            'reviewed_user_id' => $order->farmer_id,
            'product_id' => $validated['product_id'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully. Thank you for your feedback!');
    }
}
