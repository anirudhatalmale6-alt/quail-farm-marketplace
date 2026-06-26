@extends('layouts.app')

@section('title', 'Farmer Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="mt-1 text-[#9ca3af]">Here's an overview of your farm marketplace activity.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Products --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Products</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="bg-emerald-900/30 rounded-lg p-3">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Orders --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Active Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $activeOrders }}</p>
                </div>
                <div class="bg-amber-900/30 rounded-lg p-3">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Revenue</p>
                    <p class="text-3xl font-bold text-white mt-1">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-green-900/30 rounded-lg p-3">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Average Rating</p>
                    <div class="flex items-center mt-1">
                        <p class="text-3xl font-bold text-white">{{ $avgRating ? number_format($avgRating, 1) : 'N/A' }}</p>
                        @if($avgRating)
                        <div class="flex ml-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-[#d4a853]' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-yellow-900/30 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#d4a853]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
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
                    <a href="{{ route('farmer.orders.index') }}" class="text-sm font-medium text-[#d4a853] hover:text-[#f59e0b]">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[#0f1419]">
                                <th class="px-6 py-3">Order #</th>
                                <th class="px-6 py-3">Buyer</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#374151]">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-[#374151]/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('farmer.orders.show', $order->id) }}" class="text-sm font-medium text-[#d4a853] hover:text-[#f59e0b]">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $order->buyer->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-white">${{ number_format($order->total, 2) }}</td>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm">No orders yet. Your orders will appear here.</p>
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
                    <a href="{{ route('farmer.products.create') }}" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#374151]/50 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-emerald-900/30 rounded-lg p-2 mr-4 group-hover:bg-emerald-900/50 transition-colors">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Add New Product</p>
                            <p class="text-xs text-[#9ca3af]">List a new product for sale</p>
                        </div>
                    </a>

                    <a href="{{ route('farmer.orders.index') }}" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#374151]/50 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-amber-900/30 rounded-lg p-2 mr-4 group-hover:bg-amber-900/50 transition-colors">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">View Orders</p>
                            <p class="text-xs text-[#9ca3af]">Manage your received orders</p>
                        </div>
                    </a>

                    <a href="{{ route('farmer.products.index') }}" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#374151]/50 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-blue-900/30 rounded-lg p-2 mr-4 group-hover:bg-blue-900/50 transition-colors">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">My Products</p>
                            <p class="text-xs text-[#9ca3af]">View and manage your listings</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-4 rounded-lg border border-[#374151] hover:bg-[#374151]/50 hover:border-[#d4a853]/30 transition-all group">
                        <div class="bg-purple-900/30 rounded-lg p-2 mr-4 group-hover:bg-purple-900/50 transition-colors">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Messages</p>
                            <p class="text-xs text-[#9ca3af]">Chat with your buyers</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
