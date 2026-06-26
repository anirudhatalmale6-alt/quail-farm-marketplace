@extends('layouts.app')

@section('title', 'Subscribe to ' . $plan->name . ' - QuailConnect')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('pricing') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Plans
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Subscribe to <span class="text-[#d4a853]">{{ $plan->name }}</span></h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
        {{-- Plan Summary --}}
        <div class="md:col-span-2">
            <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-white mb-4">Plan Summary</h3>
                <div class="border-b border-[#374151] pb-4 mb-4">
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-[#d4a853]">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-sm text-[#9ca3af]">/ {{ $plan->billing_cycle }}</span>
                    </div>
                </div>
                @if($plan->features)
                    <ul class="space-y-2">
                        @foreach($plan->features as $feature)
                            <li class="flex items-start text-sm text-[#9ca3af]">
                                <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Payment Form --}}
        <div class="md:col-span-3">
            <form method="POST" action="{{ route('subscription.process') }}" class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                <h3 class="text-lg font-semibold text-white mb-6">Payment Details</h3>

                {{-- Payment Method --}}
                <div class="mb-6" x-data="{ method: 'paypal' }">
                    <label class="block text-sm font-medium text-[#9ca3af] mb-3">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                               :class="method === 'paypal' ? 'border-[#d4a853] bg-[#d4a853]/5' : 'border-[#374151] hover:border-[#4b5563]'">
                            <input type="radio" name="payment_method" value="paypal" x-model="method" class="sr-only">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-1" :class="method === 'paypal' ? 'text-[#d4a853]' : 'text-[#9ca3af]'" fill="currentColor" viewBox="0 0 24 24"><path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 00-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 00-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 00.554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.816-5.09a.932.932 0 01.921-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.775-4.471z"/></svg>
                                <span class="text-xs font-medium" :class="method === 'paypal' ? 'text-[#d4a853]' : 'text-[#9ca3af]'">PayPal</span>
                            </div>
                        </label>
                        <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                               :class="method === 'bitcoin' ? 'border-[#d4a853] bg-[#d4a853]/5' : 'border-[#374151] hover:border-[#4b5563]'">
                            <input type="radio" name="payment_method" value="bitcoin" x-model="method" class="sr-only">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-1" :class="method === 'bitcoin' ? 'text-[#d4a853]' : 'text-[#9ca3af]'" fill="currentColor" viewBox="0 0 24 24"><path d="M23.638 14.904c-1.602 6.43-8.113 10.34-14.542 8.736C2.67 22.05-1.244 15.525.362 9.105 1.962 2.67 8.475-1.243 14.9.358c6.43 1.605 10.342 8.115 8.738 14.546zm-6.35-4.613c.24-1.59-.974-2.45-2.64-3.03l.54-2.153-1.315-.33-.525 2.107c-.345-.087-.7-.169-1.053-.25l.53-2.12-1.32-.33-.54 2.16c-.285-.065-.565-.13-.84-.2l-1.815-.45-.35 1.407s.975.225.955.238c.535.136.63.494.615.78l-.617 2.478c.037.009.085.025.138.047l-.14-.036-.865 3.46c-.067.166-.237.415-.62.32.015.02-.96-.24-.96-.24l-.66 1.51 1.71.426.93.24-.55 2.19 1.318.33.54-2.17c.36.1.705.19 1.05.273l-.54 2.15 1.32.33.55-2.18c2.24.427 3.93.254 4.64-1.774.57-1.637-.03-2.58-1.217-3.196.854-.2 1.5-.76 1.68-1.93h.01zm-3.01 4.22c-.404 1.64-3.157.75-4.05.53l.72-2.9c.896.22 3.757.67 3.33 2.37zm.41-4.24c-.37 1.49-2.662.733-3.405.548l.654-2.64c.744.18 3.137.52 2.75 2.084v.01z"/></svg>
                                <span class="text-xs font-medium" :class="method === 'bitcoin' ? 'text-[#d4a853]' : 'text-[#9ca3af]'">Bitcoin</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Reference --}}
                <div class="mb-8">
                    <label for="payment_reference" class="block text-sm font-medium text-[#9ca3af] mb-2">Payment Reference / Transaction ID</label>
                    <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference') }}" required
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853] transition-colors"
                           placeholder="Enter your payment transaction ID">
                    @error('payment_reference')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-[#6b7280] mt-2">Please complete payment first, then enter the transaction reference here.</p>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full px-6 py-3 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                    Complete Subscription - ${{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
