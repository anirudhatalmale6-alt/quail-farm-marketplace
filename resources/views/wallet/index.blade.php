@extends('layouts.app')

@section('title', 'My Wallet - QuailConnect')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Current Balance -->
        <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-2xl p-6 shadow-lg shadow-[#10b981]/20">
            <div class="flex items-center justify-between mb-2">
                <p class="text-white/80 text-sm font-medium">Current Balance</p>
                <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <p class="text-3xl font-bold text-white">${{ number_format($user->balance, 2) }}</p>
        </div>

        <!-- Total In -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[#9ca3af] text-sm font-medium">Total Received</p>
                <svg class="w-6 h-6 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <p class="text-2xl font-bold text-[#10b981]">${{ number_format($totalIn, 2) }}</p>
        </div>

        <!-- Total Out -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[#9ca3af] text-sm font-medium">Total Spent</p>
                <svg class="w-6 h-6 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
            <p class="text-2xl font-bold text-[#ef4444]">${{ number_format($totalOut, 2) }}</p>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[#374151] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-lg font-bold text-white">Transaction History</h2>

            <!-- Filter -->
            <form method="GET" action="{{ route('wallet.index') }}" class="flex items-center gap-2">
                <select name="type" onchange="this.form.submit()" class="bg-[#0f1419] border border-[#374151] text-white text-sm rounded-lg px-3 py-1.5 focus:border-[#d4a853] focus:outline-none">
                    <option value="">All Types</option>
                    <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposits</option>
                    <option value="withdrawal" {{ request('type') === 'withdrawal' ? 'selected' : '' }}>Withdrawals</option>
                    <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchases</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>Sales</option>
                    <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refunds</option>
                    <option value="admin_credit" {{ request('type') === 'admin_credit' ? 'selected' : '' }}>Admin Credits</option>
                    <option value="admin_debit" {{ request('type') === 'admin_debit' ? 'selected' : '' }}>Admin Debits</option>
                    <option value="investment" {{ request('type') === 'investment' ? 'selected' : '' }}>Investments</option>
                </select>
            </form>
        </div>

        @if($transactions->count() > 0)
            <div class="divide-y divide-[#374151]/50">
                @foreach($transactions as $txn)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-[#374151]/20 transition-colors">
                        <div class="flex items-center gap-4">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $txn->isCredit() ? 'bg-[#10b981]/10' : 'bg-[#ef4444]/10' }}">
                                @if($txn->isCredit())
                                    <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-medium text-white">{{ $txn->description }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs px-1.5 py-0.5 rounded-full {{ $txn->isCredit() ? 'bg-[#10b981]/10 text-[#10b981]' : 'bg-[#ef4444]/10 text-[#ef4444]' }}">
                                        {{ str_replace('_', ' ', ucfirst($txn->type)) }}
                                    </span>
                                    <span class="text-xs text-[#6b7280]">{{ $txn->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-bold {{ $txn->isCredit() ? 'text-[#10b981]' : 'text-[#ef4444]' }}">
                                {{ $txn->isCredit() ? '+' : '' }}${{ number_format(abs($txn->amount), 2) }}
                            </p>
                            <p class="text-xs text-[#6b7280]">Bal: ${{ number_format($txn->balance_after, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#374151]">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-[#374151] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <p class="text-[#6b7280] text-lg">No transactions yet</p>
                <p class="text-[#4b5563] text-sm mt-1">Your balance activity will appear here</p>
            </div>
        @endif
    </div>
</div>
@endsection
