@extends('layouts.app')

@section('title', 'Pricing Plans - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-white">Choose Your <span class="text-[#d4a853]">Plan</span></h1>
        <p class="mt-4 text-lg text-[#9ca3af] max-w-2xl mx-auto">Unlock premium features to grow your quail farming business. Start free, upgrade when you're ready.</p>
    </div>

    {{-- Plan Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
        {{-- FREE Plan --}}
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-8 flex flex-col">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-white">Free</h3>
                <p class="text-sm text-[#9ca3af] mt-1">Get started with the basics</p>
            </div>
            <div class="mb-6">
                <span class="text-4xl font-bold text-white">$0</span>
                <span class="text-[#9ca3af] text-sm">/month</span>
            </div>
            <ul class="space-y-3 mb-8 flex-1">
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#10b981] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Up to 5 product listings
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#10b981] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Basic marketplace access
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#10b981] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Messaging system
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#10b981] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Community feed access
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#374151] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-[#6b7280]">Priority search placement</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#374151] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-[#6b7280]">Investment applications</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#374151] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-[#6b7280]">Advanced analytics</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#374151] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-[#6b7280]">Featured profile badge</span>
                </li>
            </ul>
            @auth
                @if(auth()->user()->isFree())
                    <div class="px-6 py-3 bg-[#374151]/50 text-center rounded-xl text-sm font-medium text-[#9ca3af]">
                        Current Plan
                    </div>
                @else
                    <a href="{{ route('pricing') }}" class="block px-6 py-3 bg-[#374151] text-center rounded-xl text-sm font-medium text-white hover:bg-[#4b5563] transition-colors">
                        Downgrade
                    </a>
                @endif
            @else
                <a href="{{ url('/register') }}" class="block px-6 py-3 bg-[#374151] text-center rounded-xl text-sm font-semibold text-white hover:bg-[#4b5563] transition-colors">
                    Get Started
                </a>
            @endauth
        </div>

        {{-- PRO Plan --}}
        <div class="relative bg-[#1e293b] border-2 border-[#d4a853] rounded-2xl p-8 flex flex-col shadow-lg shadow-[#d4a853]/10">
            {{-- Most Popular badge --}}
            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold bg-[#d4a853] text-[#0f1419] uppercase tracking-wider">Most Popular</span>
            </div>
            <div class="mb-6 mt-2">
                <h3 class="text-xl font-semibold text-[#d4a853]">Pro</h3>
                <p class="text-sm text-[#9ca3af] mt-1">Everything you need to scale</p>
            </div>
            <div class="mb-6">
                <span class="text-4xl font-bold text-white">$29.99</span>
                <span class="text-[#9ca3af] text-sm">/month</span>
            </div>
            <ul class="space-y-3 mb-8 flex-1">
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-medium">Unlimited product listings</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Full marketplace access
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Messaging system
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Community feed access
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-medium">Priority search placement</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-medium">Investment applications</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-medium">Advanced analytics</span>
                </li>
                <li class="flex items-start text-sm text-[#9ca3af]">
                    <svg class="w-5 h-5 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-medium">Featured profile badge</span>
                </li>
            </ul>
            @auth
                @if(auth()->user()->isPro())
                    <div class="px-6 py-3 bg-[#d4a853]/20 text-center rounded-xl text-sm font-medium text-[#d4a853] border border-[#d4a853]/30">
                        Current Plan
                    </div>
                @else
                    @php $proPlan = $plans->firstWhere('slug', 'pro'); @endphp
                    @if($proPlan)
                        <a href="{{ route('subscription.subscribe', $proPlan->id) }}" class="block px-6 py-3 bg-[#d4a853] text-center rounded-xl text-sm font-semibold text-[#0f1419] hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                            Upgrade Now
                        </a>
                    @endif
                @endif
            @else
                <a href="{{ url('/register') }}" class="block px-6 py-3 bg-[#d4a853] text-center rounded-xl text-sm font-semibold text-[#0f1419] hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                    Get Started
                </a>
            @endauth
        </div>
    </div>

    {{-- Feature Comparison Table --}}
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-white text-center mb-8">Feature <span class="text-[#d4a853]">Comparison</span></h2>
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#374151]">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-[#9ca3af] uppercase tracking-wider">Feature</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider">Free</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-[#d4a853] uppercase tracking-wider">Pro</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Product Listings</td>
                        <td class="px-6 py-4 text-center text-sm text-white">5 max</td>
                        <td class="px-6 py-4 text-center text-sm text-[#d4a853] font-medium">Unlimited</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Marketplace Access</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#10b981] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Messaging</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#10b981] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Community Feed</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#10b981] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Priority Search Placement</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#374151] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Credit System Access</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#374151] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Investment Applications</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#374151] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Advanced Analytics</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#374151] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">Featured Profile Badge</td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#374151] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <svg class="w-5 h-5 text-[#d4a853] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
