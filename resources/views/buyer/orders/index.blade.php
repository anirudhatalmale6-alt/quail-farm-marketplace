@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">My Orders</h1>
            <p class="mt-1 text-[#9ca3af]">Track and manage your purchase history</p>
        </div>
        <a href="{{ route('buyer.marketplace.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Products
        </a>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex space-x-1 mb-6 bg-[#0f1419] rounded-lg p-1 overflow-x-auto border border-[#374151]">
        @php
            $statuses = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
        @endphp
        @foreach($statuses as $key => $label)
        <a href="{{ route('buyer.orders.index', ['status' => $key]) }}"
            class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap transition-colors {{ request('status', '') === $key ? 'bg-[#d4a853] text-[#0f1419] shadow-sm' : 'text-[#9ca3af] hover:text-white' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-900/30 border border-green-500/30 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-green-400 text-sm">{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-green-400 hover:text-green-300">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
    @endif

    {{-- Orders List --}}
    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <a href="{{ route('buyer.orders.show', $order->id) }}" class="text-sm font-semibold text-[#d4a853] hover:text-[#f59e0b]">
                                {{ $order->order_number }}
                            </a>
                            <p class="text-xs text-[#9ca3af] mt-0.5">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-0 flex items-center space-x-3">
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-900/30 text-gray-400' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="text-lg font-bold text-[#d4a853]">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="flex items-center space-x-4 mb-3">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex items-center space-x-2 text-sm text-[#9ca3af]">
                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-[#374151]">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-[#0f1419] flex items-center justify-center border border-[#374151]">
                                <svg class="w-5 h-5 text-[#374151]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-medium text-white">{{ $item->product->name ?? 'Product' }}</p>
                            <p class="text-xs text-[#9ca3af]">Qty: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <span class="text-xs text-[#9ca3af]">+{{ $order->items->count() - 3 }} more</span>
                    @endif
                </div>

                {{-- Farmer & Actions --}}
                <div class="flex items-center justify-between pt-3 border-t border-[#374151]/50">
                    <div class="flex items-center text-sm text-[#9ca3af]">
                        <img src="{{ $order->farmer->avatar ?? '' }}" alt="" class="w-6 h-6 rounded-full mr-2 object-cover">
                        <span>{{ $order->farmer->farm_name ?? $order->farmer->name ?? 'Farmer' }}</span>
                    </div>
                    <a href="{{ route('buyer.orders.show', $order->id) }}" class="text-sm font-medium text-[#d4a853] hover:text-[#f59e0b] inline-flex items-center">
                        View Details
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-[#374151] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <h3 class="text-lg font-semibold text-white mb-2">No orders yet</h3>
        <p class="text-[#9ca3af] mb-6">Browse the marketplace to find fresh quail products.</p>
        <a href="{{ route('buyer.marketplace.index') }}" class="inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
