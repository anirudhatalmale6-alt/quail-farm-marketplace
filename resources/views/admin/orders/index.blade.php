@extends('layouts.app')

@section('title', 'All Orders - QuailConnect Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">All Orders</h1>
        <p class="text-[#9ca3af] mt-1">Monitor and manage platform orders</p>
    </div>

    <!-- Filters -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-4 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number, buyer, or farmer..."
                    class="w-full px-4 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
            </div>
            <select name="status" class="px-4 py-2 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="disputed" {{ request('status') === 'disputed' ? 'selected' : '' }}>Disputed</option>
            </select>
            <select name="payment_status" class="px-4 py-2 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Payment Pending</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg hover:bg-[#f59e0b] transition text-sm font-medium">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-[#9ca3af] hover:text-white text-sm font-medium">Clear</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#0f1419] border-b border-[#374151]">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Order #</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Buyer</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Farmer</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Total</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Commission</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Payment</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Date</th>
                        <th class="text-right py-3 px-4 font-medium text-[#9ca3af]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#374151]/50">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d4a853] hover:text-[#f59e0b] font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-white">{{ $order->buyer->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-white">{{ $order->farmer->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 font-medium text-white">${{ number_format($order->total, 2) }}</td>
                            <td class="py-3 px-4 text-[#d4a853]">${{ number_format($order->commission_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-900/30 text-amber-400',
                                        'confirmed' => 'bg-blue-900/30 text-blue-400',
                                        'processing' => 'bg-indigo-900/30 text-indigo-400',
                                        'shipped' => 'bg-purple-900/30 text-purple-400',
                                        'delivered' => 'bg-green-900/30 text-green-400',
                                        'cancelled' => 'bg-red-900/30 text-red-400',
                                        'disputed' => 'bg-red-900/30 text-red-400',
                                        'refunded' => 'bg-gray-700/50 text-gray-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-700/50 text-gray-400' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $order->payment_status === 'paid' ? 'bg-green-900/30 text-green-400' : ($order->payment_status === 'refunded' ? 'bg-gray-700/50 text-gray-400' : 'bg-amber-900/30 text-amber-400') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#9ca3af]">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d4a853] hover:text-[#f59e0b] text-xs font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-[#9ca3af]">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-[#374151]">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
