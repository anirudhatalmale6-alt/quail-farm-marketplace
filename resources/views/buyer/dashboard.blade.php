@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="mt-1 text-[#9ca3af]">Browse fresh quail products from local farmers.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Orders --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalOrders }}</p>
                </div>
                <div class="bg-emerald-500/10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Deliveries --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Pending Deliveries</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $pendingDeliveries }}</p>
                </div>
                <div class="bg-[#d4a853]/10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Spent --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Spent</p>
                    <p class="text-3xl font-bold text-[#d4a853] mt-1">${{ number_format($totalSpent, 2) }}</p>
                </div>
                <div class="bg-green-500/10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Favorite Farmers --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Favorite Farmers</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $favoriteFarmers }}</p>
                </div>
                <div class="bg-rose-500/10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2">
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151]">
                <div class="px-6 py-4 border-b border-[#374151] flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Recent Orders</h2>
                    <a href="{{ route('buyer.orders.index') }}" class="text-sm font-medium text-[#d4a853] hover:text-[#f59e0b]">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs font-medium text-[#9ca3af] uppercase tracking-wider bg-[#0f1419]">
                                <th class="px-6 py-3">Order #</th>
                                <th class="px-6 py-3">Farmer</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#374151]/50">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-[#374151]/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('buyer.orders.show', $order->id) }}" class="text-sm font-medium text-[#d4a853] hover:text-[#f59e0b]">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $order->farmer->avatar ?? '' }}" alt="" class="w-7 h-7 rounded-full mr-2 object-cover">
                                        <span class="text-sm text-[#9ca3af]">{{ $order->farmer->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-[#d4a853]">${{ number_format($order->total, 2) }}</td>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-900/30 text-gray-400' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#9ca3af]">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#9ca3af]">
                                    <svg class="mx-auto h-12 w-12 text-[#374151] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <p class="text-sm">No orders yet. Start browsing the marketplace!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('buyer.marketplace.index') }}" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#d4a853]/10 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-[#d4a853]/10 rounded-lg p-2 mr-4 group-hover:bg-[#d4a853]/20 transition-colors">
                            <svg class="w-6 h-6 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Browse Products</p>
                            <p class="text-xs text-[#9ca3af]">Find fresh quail products</p>
                        </div>
                    </a>

                    <a href="{{ route('buyer.orders.index') }}" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#d4a853]/10 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-amber-500/10 rounded-lg p-2 mr-4 group-hover:bg-amber-500/20 transition-colors">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">My Orders</p>
                            <p class="text-xs text-[#9ca3af]">Track your order history</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#d4a853]/10 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-purple-500/10 rounded-lg p-2 mr-4 group-hover:bg-purple-500/20 transition-colors">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Messages</p>
                            <p class="text-xs text-[#9ca3af]">Chat with farmers</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
