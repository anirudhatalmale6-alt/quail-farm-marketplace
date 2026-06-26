@extends('layouts.app')

@section('title', 'Admin Dashboard - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
        <p class="text-[#9ca3af] mt-1">Platform overview and management</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Farmers -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Farmers</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalFarmers) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Buyers -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Buyers</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalBuyers) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Total Revenue</p>
                    <p class="text-3xl font-bold text-white mt-1">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Commission Earned -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Commission Earned</p>
                    <p class="text-3xl font-bold text-[#d4a853] mt-1">${{ number_format($totalCommission, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Users -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#9ca3af]">Pending Users</p>
                    <p class="text-3xl font-bold {{ $pendingUsers > 0 ? 'text-amber-400' : 'text-white' }} mt-1">{{ number_format($pendingUsers) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Revenue Overview</h3>
            <div id="chart-revenue" class="h-64 flex items-center justify-center text-[#9ca3af] border-2 border-dashed border-[#374151] rounded-lg">
                <p class="text-sm">Chart.js integration placeholder</p>
            </div>
        </div>
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-lg font-semibold text-white mb-4">User Growth</h3>
            <div id="chart-users" class="h-64 flex items-center justify-center text-[#9ca3af] border-2 border-dashed border-[#374151] rounded-lg">
                <p class="text-sm">Chart.js integration placeholder</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Links -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.users.index') }}?status=pending" class="flex items-center p-3 bg-amber-900/20 rounded-lg hover:bg-amber-900/40 transition">
                    <svg class="w-5 h-5 text-amber-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="text-sm font-medium text-amber-400">Pending Approvals</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center p-3 bg-blue-900/20 rounded-lg hover:bg-blue-900/40 transition">
                    <svg class="w-5 h-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-400">View Orders</span>
                </a>
                <a href="{{ route('admin.commissions.index') }}" class="flex items-center p-3 bg-green-900/20 rounded-lg hover:bg-green-900/40 transition">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="text-sm font-medium text-green-400">Commission Rates</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center p-3 bg-purple-900/20 rounded-lg hover:bg-purple-900/40 transition">
                    <svg class="w-5 h-5 text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="text-sm font-medium text-purple-400">Categories</span>
                </a>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Pending Approvals</h3>
            @if($pendingApprovals->isEmpty())
                <p class="text-[#9ca3af] text-sm">No pending approvals.</p>
            @else
                <div class="space-y-3">
                    @foreach($pendingApprovals as $user)
                        <div class="flex items-center justify-between p-3 bg-[#0f1419] rounded-lg">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $user->avatar }}" alt="" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-[#9ca3af] capitalize">{{ $user->role }} &middot; {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="text-xs bg-[#d4a853] text-[#0f1419] px-3 py-1.5 rounded-lg hover:bg-[#f59e0b] transition font-medium">Approve</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#374151]">
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">User</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Role</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Email</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @foreach($recentSignups as $signup)
                        <tr class="hover:bg-[#374151]/50">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $signup->avatar }}" alt="" class="w-8 h-8 rounded-full">
                                    <span class="font-medium text-white">{{ $signup->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $signup->role === 'farmer' ? 'bg-green-900/30 text-green-400' : ($signup->role === 'admin' ? 'bg-purple-900/30 text-purple-400' : 'bg-blue-900/30 text-blue-400') }}">
                                    {{ ucfirst($signup->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#9ca3af]">{{ $signup->email }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $signup->status === 'active' ? 'bg-green-900/30 text-green-400' : ($signup->status === 'pending' ? 'bg-amber-900/30 text-amber-400' : 'bg-red-900/30 text-red-400') }}">
                                    {{ ucfirst($signup->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#9ca3af]">{{ $signup->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
