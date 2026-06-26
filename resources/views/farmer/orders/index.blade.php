@extends('layouts.app')

@section('title', 'Received Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Received Orders</h1>
        <p class="mt-1 text-[#9ca3af]">Manage and fulfill orders from your buyers</p>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex space-x-1 mb-6 bg-[#0f1419] rounded-lg p-1 overflow-x-auto">
        @php
            $statuses = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
        @endphp
        @foreach($statuses as $key => $label)
        <a href="{{ route('farmer.orders.index', ['status' => $key]) }}"
            class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap transition-colors {{ request('status', '') === $key ? 'bg-[#1e293b] text-[#d4a853] shadow-sm' : 'text-[#9ca3af] hover:text-white' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-900/30 border border-green-700 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-green-300 text-sm">{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-green-400 hover:text-green-300">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
    @endif

    {{-- Orders Table --}}
    @if($orders->count() > 0)
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[#0f1419]">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Buyer</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @foreach($orders as $order)
                    <tr class="hover:bg-[#374151]/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('farmer.orders.show', $order->id) }}" class="text-sm font-semibold text-[#d4a853] hover:text-[#f59e0b]">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $order->buyer->avatar ?? '' }}" alt="" class="w-8 h-8 rounded-full mr-2 object-cover">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $order->buyer->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->buyer->business_name ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-1">
                                @foreach($order->items->take(2) as $item)
                                    @if($item->product && $item->product->images && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="" class="w-8 h-8 rounded object-cover border border-[#374151]">
                                    @else
                                        <div class="w-8 h-8 rounded bg-[#0f1419] flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                @endforeach
                                @if($order->items->count() > 2)
                                    <span class="text-xs text-gray-500 ml-1">+{{ $order->items->count() - 2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-white">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-700 text-gray-300' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#9ca3af]">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('farmer.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#d4a853] bg-[#d4a853]/10 rounded-lg hover:bg-[#d4a853]/20 transition-colors">
                                Manage
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-lg font-semibold text-white mb-2">No orders yet</h3>
        <p class="text-[#9ca3af]">Orders from buyers will appear here when they purchase your products.</p>
    </div>
    @endif
</div>
@endsection
