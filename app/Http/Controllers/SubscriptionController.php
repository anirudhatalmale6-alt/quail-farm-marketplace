<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Show pricing page with Free vs Pro comparison.
     */
    public function plans()
    {
        $plans = SubscriptionPlan::active()->orderBy('price')->get();
        return view('subscription.plans', compact('plans'));
    }

    /**
     * Show payment/subscription form for a plan.
     */
    public function subscribe($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        return view('subscription.checkout', compact('plan'));
    }

    /**
     * Process subscription (manual payment - PayPal/Bitcoin reference).
     */
    public function processSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:paypal,bitcoin',
            'payment_reference' => 'required|string|max:255',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = auth()->user();

        // Cancel any existing active subscription
        UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Create new subscription
        $endsAt = null;
        if ($plan->billing_cycle === 'monthly') {
            $endsAt = now()->addMonth();
        } elseif ($plan->billing_cycle === 'yearly') {
            $endsAt = now()->addYear();
        }

        UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'auto_renew' => true,
        ]);

        // Update user's subscription plan
        $user->update([
            'subscription_plan' => $plan->slug === 'pro' ? 'pro' : 'free',
            'is_featured' => $plan->is_featured,
        ]);

        return redirect()->route('subscription.status')->with('success', 'Subscription activated successfully! Your payment reference has been recorded.');
    }

    /**
     * Show current subscription status.
     */
    public function mySubscription()
    {
        $user = auth()->user();
        $subscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('plan')
            ->first();

        $plans = SubscriptionPlan::active()->orderBy('price')->get();

        return view('subscription.status', compact('subscription', 'plans'));
    }
}
