@extends('layouts.app')

@section('title', 'Investor Dashboard - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Investor Dashboard</h1>
            <p class="text-[#9ca3af] mt-1">Find and invest in promising quail farms</p>
        </div>
        <a href="{{ route('investor.farmers.index') }}" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg font-semibold text-sm hover:bg-[#f59e0b] transition-colors">
            Browse Farmers
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#9ca3af]">Active Farmers</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $stats['total_farmers'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#3b82f6]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#9ca3af]">Open Applications</p>
                    <p class="text-2xl font-bold text-[#d4a853] mt-1">{{ $stats['open_applications'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#d4a853]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#9ca3af]">My Investments</p>
                    <p class="text-2xl font-bold text-[#10b981] mt-1">{{ $stats['my_investments'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#10b981]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#9ca3af]">Total Invested</p>
                    <p class="text-2xl font-bold text-white mt-1">${{ number_format($stats['total_invested'], 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-[#8b5cf6]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Investment Opportunities -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl">
            <div class="px-6 py-4 border-b border-[#374151] flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Investment Opportunities</h2>
                <span class="text-xs px-2 py-1 bg-[#d4a853]/10 text-[#d4a853] rounded-full">{{ $recentApplications->count() }} open</span>
            </div>
            <div class="divide-y divide-[#374151]">
                @forelse($recentApplications as $app)
                    <a href="{{ route('investor.farmers.show', $app->user_id) }}" class="block px-6 py-4 hover:bg-[#374151]/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="{{ $app->user->avatar }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium truncate">{{ $app->title }}</p>
                                <p class="text-sm text-[#9ca3af]">{{ $app->user->farm_name ?? $app->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[#d4a853] font-bold">${{ number_format($app->amount_requested, 0) }}</p>
                                <p class="text-xs text-[#9ca3af] capitalize">{{ str_replace('_', ' ', $app->status) }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-8 text-center text-[#6b7280]">No open investment applications</div>
                @endforelse
            </div>
        </div>

        <!-- Top Farmers -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl">
            <div class="px-6 py-4 border-b border-[#374151]">
                <h2 class="text-lg font-bold text-white">Top Farmers</h2>
            </div>
            <div class="divide-y divide-[#374151]">
                @foreach($topFarmers as $farmer)
                    <a href="{{ route('investor.farmers.show', $farmer->id) }}" class="flex items-center gap-3 px-6 py-4 hover:bg-[#374151]/30 transition-colors">
                        <div class="relative">
                            <img src="{{ $farmer->avatar }}" class="w-10 h-10 rounded-full">
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-[#1e293b] {{ $farmer->isOnlineNow() ? 'bg-[#10b981]' : 'bg-[#6b7280]' }}"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium">{{ $farmer->farm_name ?? $farmer->name }}</p>
                            <p class="text-sm text-[#9ca3af]">{{ $farmer->city }}{{ $farmer->city && $farmer->state ? ', ' : '' }}{{ $farmer->state }}</p>
                        </div>
                        <span class="text-xs text-[#9ca3af]">{{ $farmer->products_count }} products</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- My Investments -->
    @if($myInvestments->isNotEmpty())
        <div class="mt-6 bg-[#1e293b] border border-[#374151] rounded-xl">
            <div class="px-6 py-4 border-b border-[#374151]">
                <h2 class="text-lg font-bold text-white">My Funded Investments</h2>
            </div>
            <div class="divide-y divide-[#374151]">
                @foreach($myInvestments as $inv)
                    <div class="flex items-center gap-3 px-6 py-4">
                        <img src="{{ $inv->user->avatar }}" class="w-10 h-10 rounded-full">
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium">{{ $inv->title }}</p>
                            <p class="text-sm text-[#9ca3af]">{{ $inv->user->farm_name ?? $inv->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[#10b981] font-bold">${{ number_format($inv->amount_requested, 0) }}</p>
                            <p class="text-xs text-[#10b981]">Funded {{ $inv->reviewed_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
