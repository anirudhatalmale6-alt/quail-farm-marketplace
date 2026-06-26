@extends('layouts.app')

@section('title', 'My Subscription - QuailConnect')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-8">My <span class="text-[#d4a853]">Subscription</span></h1>

    {{-- Current Plan Card --}}
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <div class="flex items-center space-x-3">
                    <h2 class="text-xl font-semibold text-white">
                        {{ auth()->user()->isPro() ? 'Pro' : 'Free' }} Plan
                    </h2>
                    @if(auth()->user()->isPro())
                        <span class="px-3 py-1 text-xs font-bold bg-[#d4a853] text-[#0f1419] rounded-full uppercase">Active</span>
                    @else
                        <span class="px-3 py-1 text-xs font-bold bg-[#374151] text-[#9ca3af] rounded-full uppercase">Free Tier</span>
                    @endif
                </div>
                <p class="text-sm text-[#9ca3af] mt-1">
                    @if(auth()->user()->isPro())
                        You have access to all premium features.
                    @else
                        Upgrade to Pro to unlock all features.
                    @endif
                </p>
            </div>
            @if(auth()->user()->isPro() && $subscription)
                <div class="mt-4 sm:mt-0 text-right">
                    <p class="text-2xl font-bold text-[#d4a853]">${{ number_format($subscription->plan->price ?? 0, 2) }}</p>
                    <p class="text-xs text-[#9ca3af]">per {{ $subscription->plan->billing_cycle ?? 'month' }}</p>
                </div>
            @endif
        </div>

        {{-- Subscription Details --}}
        @if($subscription)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-[#0f1419] rounded-xl p-4">
                    <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">Started</p>
                    <p class="text-sm font-medium text-white">{{ $subscription->starts_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-[#0f1419] rounded-xl p-4">
                    <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">{{ $subscription->ends_at ? 'Renews' : 'Duration' }}</p>
                    <p class="text-sm font-medium text-white">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'Lifetime' }}</p>
                </div>
                <div class="bg-[#0f1419] rounded-xl p-4">
                    <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">Payment Method</p>
                    <p class="text-sm font-medium text-white capitalize">{{ $subscription->payment_method ?? 'N/A' }}</p>
                </div>
            </div>
        @endif

        {{-- Features List --}}
        <div class="border-t border-[#374151] pt-6">
            <h3 class="text-sm font-semibold text-[#9ca3af] uppercase tracking-wider mb-4">Your Features</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 text-[#10b981] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[#9ca3af]">{{ auth()->user()->isPro() ? 'Unlimited' : 'Up to 5' }} product listings</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 text-[#10b981] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[#9ca3af]">Marketplace access</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 text-[#10b981] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[#9ca3af]">Messaging system</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 {{ auth()->user()->isPro() ? 'text-[#10b981]' : 'text-[#374151]' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if(auth()->user()->isPro())
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        @endif
                    </svg>
                    <span class="{{ auth()->user()->isPro() ? 'text-[#9ca3af]' : 'text-[#6b7280]' }}">Priority search</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 {{ auth()->user()->isPro() ? 'text-[#10b981]' : 'text-[#374151]' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if(auth()->user()->isPro())
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        @endif
                    </svg>
                    <span class="{{ auth()->user()->isPro() ? 'text-[#9ca3af]' : 'text-[#6b7280]' }}">Investment applications</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 {{ auth()->user()->isPro() ? 'text-[#10b981]' : 'text-[#374151]' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if(auth()->user()->isPro())
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        @endif
                    </svg>
                    <span class="{{ auth()->user()->isPro() ? 'text-[#9ca3af]' : 'text-[#6b7280]' }}">Advanced analytics</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="border-t border-[#374151] pt-6 mt-6 flex flex-col sm:flex-row gap-3">
            @if(auth()->user()->isFree())
                @php $proPlan = $plans->firstWhere('slug', 'pro'); @endphp
                @if($proPlan)
                    <a href="{{ route('subscription.subscribe', $proPlan->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Upgrade to Pro
                    </a>
                @endif
            @endif
            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#374151] text-white font-medium rounded-xl hover:bg-[#4b5563] transition-colors">
                View All Plans
            </a>
        </div>
    </div>
</div>
@endsection
