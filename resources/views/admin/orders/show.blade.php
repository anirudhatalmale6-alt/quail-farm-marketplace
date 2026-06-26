@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - QuailConnect Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Link -->
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Orders
    </a>

    <!-- Order Header -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $order->order_number }}</h1>
                <p class="text-sm text-[#9ca3af] mt-1">Placed {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-700/50 text-gray-400' }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $order->payment_status === 'paid' ? 'bg-green-900/30 text-green-400' : ($order->payment_status === 'refunded' ? 'bg-gray-700/50 text-gray-400' : 'bg-amber-900/30 text-amber-400') }}">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Buyer Info -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-sm font-medium text-[#9ca3af] mb-3">Buyer</h3>
            @if($order->buyer)
                <div class="flex items-center space-x-3">
                    <img src="{{ $order->buyer->avatar }}" alt="" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium text-white">{{ $order->buyer->name }}</p>
                        <p class="text-xs text-[#9ca3af]">{{ $order->buyer->email }}</p>
                    </div>
                </div>
            @else
                <p class="text-[#9ca3af] text-sm">Buyer not found</p>
            @endif
        </div>

        <!-- Farmer Info -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-sm font-medium text-[#9ca3af] mb-3">Farmer</h3>
            @if($order->farmer)
                <div class="flex items-center space-x-3">
                    <img src="{{ $order->farmer->avatar }}" alt="" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium text-white">{{ $order->farmer->name }}</p>
                        <p class="text-xs text-[#9ca3af]">{{ $order->farmer->farm_name ?? $order->farmer->email }}</p>
                    </div>
                </div>
            @else
                <p class="text-[#9ca3af] text-sm">Farmer not found</p>
            @endif
        </div>

        <!-- Financial Summary -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-sm font-medium text-[#9ca3af] mb-3">Financial</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#9ca3af]">Subtotal</span>
                    <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#9ca3af]">Commission ({{ number_format($order->commission_rate, 1) }}%)</span>
                    <span class="text-[#d4a853]">${{ number_format($order->commission_amount, 2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-[#374151] font-semibold">
                    <span class="text-white">Total</span>
                    <span class="text-white">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Order Items</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#374151]">
                        <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Product</th>
                        <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Unit Price</th>
                        <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Quantity</th>
                        <th class="text-right py-3 px-3 font-medium text-[#9ca3af]">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 px-3 text-white">{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td class="py-3 px-3 text-[#9ca3af]">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-[#9ca3af]">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-medium text-white">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notes -->
    @if($order->buyer_notes || $order->farmer_notes || $order->shipping_address)
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Notes & Details</h3>
            <div class="space-y-4 text-sm">
                @if($order->shipping_address)
                    <div>
                        <p class="font-medium text-gray-300">Shipping Address</p>
                        <p class="text-[#9ca3af] mt-1">{{ $order->shipping_address }}</p>
                    </div>
                @endif
                @if($order->buyer_notes)
                    <div>
                        <p class="font-medium text-gray-300">Buyer Notes</p>
                        <p class="text-[#9ca3af] mt-1">{{ $order->buyer_notes }}</p>
                    </div>
                @endif
                @if($order->farmer_notes)
                    <div>
                        <p class="font-medium text-gray-300">Farmer / Admin Notes</p>
                        <p class="text-[#9ca3af] mt-1 whitespace-pre-line">{{ $order->farmer_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Dispute Resolution -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6" x-data="{ showResolve: false }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Dispute Resolution</h3>
            <button @click="showResolve = !showResolve" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg text-sm font-medium hover:bg-[#f59e0b] transition">
                <span x-text="showResolve ? 'Cancel' : 'Resolve Order'"></span>
            </button>
        </div>

        <div x-show="showResolve" x-cloak>
            <form method="POST" action="{{ route('admin.orders.resolve', $order->id) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-1">Set Status</label>
                    <select name="status" required class="w-full px-4 py-2 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                        <option value="confirmed">Confirmed</option>
                        <option value="delivered">Mark as Delivered</option>
                        <option value="cancelled">Cancel Order</option>
                        <option value="refunded">Refund Order</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-1">Admin Notes</label>
                    <textarea name="admin_notes" rows="3" placeholder="Explain the resolution..."
                        class="w-full px-4 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm"></textarea>
                </div>

                <button type="submit" class="px-6 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg text-sm font-medium hover:bg-[#f59e0b] transition">
                    Submit Resolution
                </button>
            </form>
        </div>
    </div>

    <!-- Reviews -->
    @if($order->reviews->isNotEmpty())
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Reviews</h3>
            <div class="space-y-4">
                @foreach($order->reviews as $review)
                    <div class="p-4 bg-[#0f1419] rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-white">{{ $review->reviewer->name ?? 'Unknown' }}</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-[#9ca3af]">{{ $review->comment }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
