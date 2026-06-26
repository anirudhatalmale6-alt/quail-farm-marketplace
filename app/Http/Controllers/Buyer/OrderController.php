<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\CreditApplication;
use App\Models\CreditOrder;
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
            'payment_method' => 'required|in:paypal,bitcoin,credit,balance',
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

        $isBalanceOrder = $validated['payment_method'] === 'balance';

        if ($isBalanceOrder && !Auth::user()->hasEnoughBalance($total)) {
            return back()->withErrors([
                'payment_method' => 'Insufficient balance. Your balance: $' . number_format(Auth::user()->balance, 2) . ', Order total: $' . number_format($total, 2),
            ])->withInput();
        }

        $isCreditOrder = $validated['payment_method'] === 'credit';

        // For credit orders, validate buyer has approved credit with enough balance
        $creditApplication = null;
        if ($isCreditOrder) {
            $creditApplication = CreditApplication::where('buyer_id', Auth::id())
                ->active()
                ->first();

            if (!$creditApplication) {
                return back()->withErrors([
                    'payment_method' => 'You do not have an active credit line. Please apply for credit first.'
                ])->withInput();
            }

            if ($creditApplication->available_credit < $total) {
                return back()->withErrors([
                    'payment_method' => 'Insufficient credit balance. Available: $' . number_format($creditApplication->available_credit, 2)
                ])->withInput();
            }

            // Check if credit has expired
            if ($creditApplication->expires_at && $creditApplication->expires_at->isPast()) {
                return back()->withErrors([
                    'payment_method' => 'Your credit line has expired. Please apply for a new one.'
                ])->withInput();
            }
        }

        $order = DB::transaction(function () use ($validated, $product, $subtotal, $commissionRate, $commissionAmount, $total, $isCreditOrder, $creditApplication, $isBalanceOrder) {
            $paymentMethod = $isBalanceOrder ? 'balance' : ($isCreditOrder ? 'credit' : $validated['payment_method']);

            $order = Order::create([
                'buyer_id' => Auth::id(),
                'farmer_id' => $product->user_id,
                'subtotal' => $subtotal,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'total' => $total,
                'status' => $isBalanceOrder ? 'confirmed' : 'pending',
                'payment_status' => $isBalanceOrder ? 'paid' : 'pending',
                'payment_method' => $paymentMethod,
                'shipping_address' => $validated['shipping_address'],
                'buyer_notes' => $validated['buyer_notes'] ?? null,
                'payment_type' => $isCreditOrder ? 'credit' : ($isBalanceOrder ? 'balance' : 'direct'),
                'is_credit_order' => $isCreditOrder,
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

            // For credit orders, create CreditOrder record and deduct from available credit
            if ($isCreditOrder && $creditApplication) {
                CreditOrder::create([
                    'order_id' => $order->id,
                    'credit_application_id' => $creditApplication->id,
                    'amount' => $total,
                    'due_date' => now()->addDays($creditApplication->term_days),
                    'status' => 'pending',
                ]);

                $creditApplication->decrement('available_credit', $total);
            }

            if ($isBalanceOrder) {
                $buyer = Auth::user();
                $buyer->deductBalance($total, 'purchase', 'Order #' . $order->order_number, $order->id);

                $farmer = $product->user;
                $farmerAmount = $subtotal - $commissionAmount;
                $farmer->addBalance($farmerAmount, 'sale', 'Sale from Order #' . $order->order_number, $order->id);
            }

            return $order;
        });

        $successMsg = $isBalanceOrder
            ? 'Order placed and paid from your balance! $' . number_format($total, 2) . ' deducted.'
            : ($isCreditOrder
                ? 'Order placed on credit! Payment due in ' . $creditApplication->term_days . ' days.'
                : 'Order placed successfully! Please send payment via ' . ucfirst($validated['payment_method']) . ' and confirm below.');

        return redirect()->route('buyer.orders.show', $order->id)
            ->with('success', $successMsg);
    }

    /**
     * Buyer confirms they have sent payment.
     */
    public function confirmPayment(Request $request, $id)
    {
        $order = Order::where('buyer_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'payment_reference' => 'required|string|max:500',
        ]);

        $order->update([
            'buyer_payment_confirmed' => true,
            'buyer_payment_confirmed_at' => now(),
            'payment_reference' => $validated['payment_reference'],
        ]);

        // Auto-update payment_status if both parties confirmed
        if ($order->seller_payment_confirmed) {
            $order->update(['payment_status' => 'paid']);
        }

        return redirect()->route('buyer.orders.show', $order->id)
            ->with('success', 'Payment confirmed! Waiting for seller to confirm receipt.');
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
