@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('buyer.orders.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Order {{ $order->order_number }}</h1>
                <p class="mt-1 text-[#9ca3af]">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-900/30 text-yellow-400',
                    'confirmed' => 'bg-blue-900/30 text-blue-400',
                    'processing' => 'bg-indigo-900/30 text-indigo-400',
                    'shipped' => 'bg-purple-900/30 text-purple-400',
                    'delivered' => 'bg-green-900/30 text-green-400',
                    'cancelled' => 'bg-red-900/30 text-red-400',
                ];
            @endphp
            <span class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-900/30 text-gray-400' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    {{-- Order Timeline --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-6">Order Progress</h2>
        @php
            $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            if ($currentIndex === false) $currentIndex = -1;
        @endphp
        <div class="flex items-center justify-between relative">
            {{-- Progress Line --}}
            <div class="absolute left-0 right-0 top-5 h-0.5 bg-[#374151]">
                @if($currentIndex >= 0)
                <div class="h-full bg-[#d4a853] transition-all" style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                @endif
            </div>

            @foreach($steps as $index => $step)
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium
                    {{ $index <= $currentIndex ? 'bg-[#d4a853] text-[#0f1419]' : 'bg-[#374151] text-[#9ca3af]' }}">
                    @if($index < $currentIndex)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($index === $currentIndex)
                        <div class="w-3 h-3 bg-[#0f1419] rounded-full"></div>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="mt-2 text-xs font-medium {{ $index <= $currentIndex ? 'text-[#d4a853]' : 'text-[#9ca3af]' }}">
                    {{ ucfirst($step) }}
                </span>
            </div>
            @endforeach
        </div>

        @if($order->status === 'cancelled')
        <div class="mt-6 bg-red-900/30 border border-red-500/30 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium text-red-400">This order has been cancelled.</span>
            </div>
        </div>
        @endif

        @if($order->delivered_at)
        <div class="mt-4 text-sm text-[#9ca3af]">
            Delivered on {{ $order->delivered_at->format('F d, Y \a\t h:i A') }}
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151]">
                <div class="px-6 py-4 border-b border-[#374151]">
                    <h2 class="text-lg font-semibold text-white">Order Items</h2>
                </div>
                <div class="divide-y divide-[#374151]/50">
                    @foreach($order->items as $item)
                    <div class="p-6 flex items-center space-x-4">
                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-[#374151] flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-[#0f1419] flex items-center justify-center flex-shrink-0 border border-[#374151]">
                                <svg class="w-8 h-8 text-[#374151]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white">{{ $item->product->name ?? 'Product' }}</p>
                            <p class="text-sm text-[#9ca3af]">Qty: {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-white">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="px-6 py-4 bg-[#0f1419] rounded-b-xl space-y-2 border-t border-[#374151]">
                    <div class="flex justify-between text-sm">
                        <span class="text-[#9ca3af]">Subtotal</span>
                        <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-[#9ca3af]">Service Fee ({{ number_format($order->commission_rate, 1) }}%)</span>
                        <span class="text-white">${{ number_format($order->commission_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t border-[#374151]">
                        <span class="text-white">Total</span>
                        <span class="text-[#d4a853]">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Farmer Info --}}
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-5">
                <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wider mb-3">Farmer</h3>
                <div class="flex items-center">
                    <img src="{{ $order->farmer->avatar ?? '' }}" alt="" class="w-10 h-10 rounded-full object-cover mr-3">
                    <div>
                        <p class="font-medium text-white">{{ $order->farmer->farm_name ?? $order->farmer->name }}</p>
                        <p class="text-xs text-[#9ca3af]">{{ $order->farmer->name }}</p>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-5">
                <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wider mb-3">Shipping Address</h3>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#9ca3af] mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm text-[#9ca3af]">{{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- Payment --}}
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-5">
                <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wider mb-3">Payment</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Method</span>
                        <span class="text-white font-medium">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $order->payment_status === 'paid' ? 'bg-green-900/30 text-green-400' : 'bg-yellow-900/30 text-yellow-400' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->is_credit_order)
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Type</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900/30 text-blue-400">
                            Credit (Buy Now, Pay Later)
                        </span>
                    </div>
                    @if($order->creditOrder)
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Due Date</span>
                        <span class="text-white">{{ $order->creditOrder->due_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @endif
                    @if($order->payment_reference)
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Reference</span>
                        <span class="text-white font-mono text-xs">{{ Str::limit($order->payment_reference, 20) }}</span>
                    </div>
                    @endif
                </div>

                {{-- Payment Confirmation Status --}}
                <div class="mt-4 pt-3 border-t border-[#374151] space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#9ca3af]">You Confirmed</span>
                        @if($order->buyer_payment_confirmed)
                            <span class="text-green-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Yes
                            </span>
                        @else
                            <span class="text-yellow-400">Pending</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#9ca3af]">Seller Confirmed</span>
                        @if($order->seller_payment_confirmed)
                            <span class="text-green-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Yes
                            </span>
                        @else
                            <span class="text-yellow-400">Pending</span>
                        @endif
                    </div>
                </div>

                {{-- Confirm Payment Form (buyer side) --}}
                @if(!$order->buyer_payment_confirmed && !$order->is_credit_order && $order->payment_status !== 'paid')
                <div class="mt-4 pt-3 border-t border-[#374151]">
                    <form method="POST" action="{{ route('buyer.orders.confirm-payment', $order->id) }}">
                        @csrf
                        @method('PATCH')
                        <label class="block text-xs font-medium text-[#9ca3af] mb-1">
                            {{ $order->payment_method === 'paypal' ? 'PayPal Transaction ID' : 'Bitcoin TX Hash' }}
                        </label>
                        <input type="text" name="payment_reference" required
                               class="w-full px-3 py-2 text-xs rounded-lg border text-white placeholder-gray-500 mb-2"
                               style="background-color: #0f1419; border-color: #374151;"
                               placeholder="{{ $order->payment_method === 'paypal' ? 'e.g., 5TY12345AB678901C' : 'e.g., bc1q...' }}">
                        <button type="submit"
                                class="w-full px-3 py-2 text-xs font-semibold rounded-lg transition-colors"
                                style="background-color: #f59e0b; color: #0f1419;"
                                onclick="return confirm('Confirm that you have sent payment?')">
                            Confirm Payment Sent
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Notes --}}
            @if($order->buyer_notes || $order->farmer_notes)
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-5">
                <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wider mb-3">Notes</h3>
                @if($order->buyer_notes)
                <div class="mb-3">
                    <p class="text-xs font-medium text-[#9ca3af] mb-1">Your Notes</p>
                    <p class="text-sm text-white/80">{{ $order->buyer_notes }}</p>
                </div>
                @endif
                @if($order->farmer_notes)
                <div>
                    <p class="text-xs font-medium text-[#9ca3af] mb-1">Farmer Notes</p>
                    <p class="text-sm text-white/80">{{ $order->farmer_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
