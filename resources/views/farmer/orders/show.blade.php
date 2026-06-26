@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('farmer.orders.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                <p class="mt-1 text-gray-500">Received on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-blue-100 text-blue-800',
                    'processing' => 'bg-indigo-100 text-indigo-800',
                    'shipped' => 'bg-purple-100 text-purple-800',
                    'delivered' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 text-emerald-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-emerald-800 text-sm">{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
    @endif

    {{-- Status Update Actions --}}
    @if(!in_array($order->status, ['delivered', 'cancelled']))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Order Status</h2>

        <form action="{{ route('farmer.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex flex-wrap gap-3">
                @php
                    $nextStatuses = [
                        'pending' => ['confirmed' => 'Confirm Order', 'cancelled' => 'Cancel Order'],
                        'confirmed' => ['processing' => 'Start Processing', 'cancelled' => 'Cancel Order'],
                        'processing' => ['shipped' => 'Mark as Shipped', 'cancelled' => 'Cancel Order'],
                        'shipped' => ['delivered' => 'Mark as Delivered'],
                    ];
                    $available = $nextStatuses[$order->status] ?? [];
                @endphp

                @foreach($available as $status => $label)
                <button type="submit" name="status" value="{{ $status }}"
                    onclick="return confirm('Are you sure you want to {{ strtolower($label) }}?')"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg transition-colors shadow-sm
                    {{ $status === 'cancelled' ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                    @if($status === 'confirmed')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @elseif($status === 'processing')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    @elseif($status === 'shipped')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    @elseif($status === 'delivered')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($status === 'cancelled')
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    @endif
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Farmer Notes --}}
            <div>
                <label for="farmer_notes" class="block text-sm font-medium text-gray-700 mb-1">Add a Note (optional)</label>
                <textarea name="farmer_notes" id="farmer_notes" rows="2"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="e.g., Expected delivery time, special packing instructions...">{{ $order->farmer_notes }}</textarea>
            </div>
        </form>
    </div>
    @endif

    {{-- Order Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Progress</h2>
        @php
            $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            if ($currentIndex === false) $currentIndex = -1;
        @endphp
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 top-5 h-0.5 bg-gray-200">
                @if($currentIndex >= 0)
                <div class="h-full bg-emerald-500 transition-all" style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                @endif
            </div>

            @foreach($steps as $index => $step)
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium
                    {{ $index <= $currentIndex ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                    @if($index < $currentIndex)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($index === $currentIndex)
                        <div class="w-3 h-3 bg-white rounded-full"></div>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="mt-2 text-xs font-medium {{ $index <= $currentIndex ? 'text-emerald-700' : 'text-gray-400' }}">
                    {{ ucfirst($step) }}
                </span>
            </div>
            @endforeach
        </div>

        @if($order->status === 'cancelled')
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium text-red-800">This order has been cancelled.</span>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <div class="p-6 flex items-center space-x-4">
                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">{{ $item->product->name ?? 'Product' }}</p>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Platform Commission ({{ number_format($order->commission_rate, 1) }}%)</span>
                        <span class="text-red-600">-${{ number_format($order->commission_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                        <span class="text-gray-900">Your Earnings</span>
                        <span class="text-emerald-600">${{ number_format($order->subtotal - $order->commission_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Buyer Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Buyer</h3>
                <div class="flex items-center mb-3">
                    <img src="{{ $order->buyer->avatar ?? '' }}" alt="" class="w-10 h-10 rounded-full object-cover mr-3">
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->buyer->name ?? 'N/A' }}</p>
                        @if($order->buyer->business_name)
                        <p class="text-xs text-gray-500">{{ $order->buyer->business_name }}</p>
                        @endif
                    </div>
                </div>
                @if($order->buyer->phone)
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $order->buyer->phone }}
                </div>
                @endif
                @if($order->buyer->email)
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $order->buyer->email }}
                </div>
                @endif
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Delivery Address</h3>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm text-gray-700">{{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Payment</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Method</span>
                        <span class="text-gray-900 font-medium">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Order Total</span>
                        <span class="text-gray-900 font-bold">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->buyer_notes || $order->farmer_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Notes</h3>
                @if($order->buyer_notes)
                <div class="mb-3">
                    <p class="text-xs font-medium text-gray-400 mb-1">Buyer Notes</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $order->buyer_notes }}</p>
                </div>
                @endif
                @if($order->farmer_notes)
                <div>
                    <p class="text-xs font-medium text-gray-400 mb-1">Your Notes</p>
                    <p class="text-sm text-gray-700 bg-emerald-50 rounded-lg p-3">{{ $order->farmer_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
