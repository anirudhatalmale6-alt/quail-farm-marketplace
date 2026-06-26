@extends('layouts.app')

@section('title', 'My Credit - QuailConnect')

@section('content')
<div class="min-h-screen" style="background-color: #0f1419;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Credit Dashboard</h1>
                <p class="mt-1 text-gray-400">Manage your buy-now-pay-later credit line</p>
            </div>
            @if(!$activeCredit && !$pendingApplication)
                <a href="{{ route('buyer.credit.apply') }}"
                   class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 text-sm font-semibold rounded-lg shadow-sm transition-colors"
                   style="background-color: #f59e0b; color: #0f1419;"
                   onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Apply for Credit
                </a>
            @endif
        </div>

        {{-- Active Credit Card --}}
        @if($activeCredit)
        <div class="rounded-xl shadow-lg p-6 mb-8 border" style="background-color: #1e293b; border-color: #f59e0b33;">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Active Credit Line</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: #f59e0b22; color: #f59e0b;">
                    {{ ucfirst($activeCredit->status) }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Credit Limit</p>
                    <p class="text-2xl font-bold" style="color: #f59e0b;">${{ number_format($activeCredit->credit_limit, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400 mb-1">Available Balance</p>
                    <p class="text-2xl font-bold text-green-400">${{ number_format($activeCredit->available_credit, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400 mb-1">Term</p>
                    <p class="text-2xl font-bold text-white">{{ $activeCredit->term_days }} days</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400 mb-1">Interest Rate</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($activeCredit->interest_rate, 1) }}%</p>
                </div>
            </div>
            @if($activeCredit->expires_at)
            <div class="mt-4 pt-4 border-t" style="border-color: #334155;">
                <p class="text-sm text-gray-400">
                    Expires: <span class="text-white font-medium">{{ $activeCredit->expires_at->format('F d, Y') }}</span>
                    @if($activeCredit->expires_at->isPast())
                        <span class="ml-2 text-red-400 font-medium">(Expired)</span>
                    @else
                        <span class="ml-2 text-gray-500">({{ $activeCredit->expires_at->diffForHumans() }})</span>
                    @endif
                </p>
            </div>
            @endif
            {{-- Usage bar --}}
            @php
                $used = $activeCredit->credit_limit - $activeCredit->available_credit;
                $usagePercent = $activeCredit->credit_limit > 0 ? ($used / $activeCredit->credit_limit) * 100 : 0;
            @endphp
            <div class="mt-4">
                <div class="flex justify-between text-xs text-gray-400 mb-1">
                    <span>Used: ${{ number_format($used, 2) }}</span>
                    <span>{{ number_format($usagePercent, 0) }}%</span>
                </div>
                <div class="w-full h-2 rounded-full" style="background-color: #334155;">
                    <div class="h-2 rounded-full transition-all" style="width: {{ min($usagePercent, 100) }}%; background-color: #f59e0b;"></div>
                </div>
            </div>
        </div>
        @endif

        {{-- Pending Application Notice --}}
        @if($pendingApplication)
        <div class="rounded-xl p-6 mb-8 border" style="background-color: #1e293b; border-color: #f59e0b44;">
            <div class="flex items-start">
                <svg class="w-6 h-6 flex-shrink-0 mr-3" style="color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-white font-semibold">Application Under Review</h3>
                    <p class="text-gray-400 text-sm mt-1">
                        Your credit application for ${{ number_format($pendingApplication->credit_limit, 2) }}
                        ({{ $pendingApplication->term_days }}-day term) is being reviewed.
                        Submitted {{ $pendingApplication->created_at->diffForHumans() }}.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Credit Orders (What's Owed) --}}
        <div class="rounded-xl shadow-sm border mb-8" style="background-color: #1e293b; border-color: #334155;">
            <div class="px-6 py-4 border-b" style="border-color: #334155;">
                <h2 class="text-lg font-semibold text-white">Credit Orders</h2>
            </div>
            @if($creditOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #334155;">
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Order</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Amount</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Due Date</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($creditOrders as $co)
                        <tr style="border-bottom: 1px solid #1e293b;">
                            <td class="py-3 px-6">
                                <a href="{{ route('buyer.orders.show', $co->order_id) }}" class="font-medium" style="color: #f59e0b;">
                                    {{ $co->order->order_number ?? '#' . $co->order_id }}
                                </a>
                            </td>
                            <td class="py-3 px-6 text-white font-medium">${{ number_format($co->amount, 2) }}</td>
                            <td class="py-3 px-6 text-gray-300">
                                {{ $co->due_date->format('M d, Y') }}
                                @if($co->due_date->isPast() && $co->status === 'pending')
                                    <span class="text-red-400 text-xs ml-1">(Overdue)</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">
                                @php
                                    $coStatusColors = [
                                        'pending' => 'background-color: #f59e0b22; color: #f59e0b;',
                                        'paid' => 'background-color: #10b98122; color: #10b981;',
                                        'overdue' => 'background-color: #ef444422; color: #ef4444;',
                                        'defaulted' => 'background-color: #ef444444; color: #ef4444;',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="{{ $coStatusColors[$co->status] ?? 'color: #9ca3af;' }}">
                                    {{ ucfirst($co->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">No credit orders yet.</p>
            </div>
            @endif
        </div>

        {{-- Application History --}}
        <div class="rounded-xl shadow-sm border" style="background-color: #1e293b; border-color: #334155;">
            <div class="px-6 py-4 border-b" style="border-color: #334155;">
                <h2 class="text-lg font-semibold text-white">Application History</h2>
            </div>
            @if($allApplications->count() > 0)
            <div class="divide-y" style="border-color: #334155;">
                @foreach($allApplications as $app)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-white font-medium">${{ number_format($app->credit_limit, 2) }} - {{ $app->term_days }} day term</p>
                        <p class="text-sm text-gray-400">Applied {{ $app->created_at->format('M d, Y') }}</p>
                    </div>
                    @php
                        $appStatusStyles = [
                            'pending' => 'background-color: #f59e0b22; color: #f59e0b;',
                            'active' => 'background-color: #10b98122; color: #10b981;',
                            'approved' => 'background-color: #10b98122; color: #10b981;',
                            'rejected' => 'background-color: #ef444422; color: #ef4444;',
                            'expired' => 'background-color: #6b728022; color: #6b7280;',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                          style="{{ $appStatusStyles[$app->status] ?? 'color: #9ca3af;' }}">
                        {{ ucfirst($app->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-8 text-center">
                <p class="text-gray-500">No applications yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
