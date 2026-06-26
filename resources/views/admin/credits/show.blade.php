@extends('layouts.app')

@section('title', 'Credit Application #' . $application->id . ' - QuailConnect Admin')

@section('content')
<div class="min-h-screen" style="background-color: #0f1419;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Back Link --}}
        <a href="{{ route('admin.credits.index') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Credit Applications
        </a>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Credit Application #{{ $application->id }}</h1>
                <p class="mt-1 text-gray-400">Submitted {{ $application->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            @php
                $statusStyles = [
                    'pending' => 'background-color: #f59e0b22; color: #f59e0b;',
                    'active' => 'background-color: #10b98122; color: #10b981;',
                    'approved' => 'background-color: #10b98122; color: #10b981;',
                    'rejected' => 'background-color: #ef444422; color: #ef4444;',
                    'expired' => 'background-color: #6b728022; color: #6b7280;',
                ];
            @endphp
            <span class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold"
                  style="{{ $statusStyles[$application->status] ?? 'color: #9ca3af;' }}">
                {{ ucfirst($application->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Buyer Info --}}
            <div class="rounded-xl border p-6" style="background-color: #1e293b; border-color: #334155;">
                <h3 class="text-sm font-medium text-gray-400 mb-3">Buyer Information</h3>
                @if($application->buyer)
                <div class="flex items-center mb-3">
                    <img src="{{ $application->buyer->avatar ?? '' }}" alt="" class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <p class="text-white font-medium">{{ $application->buyer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $application->buyer->email }}</p>
                    </div>
                </div>
                @if($application->buyer->business_name)
                <p class="text-sm text-gray-400">Business: <span class="text-white">{{ $application->buyer->business_name }}</span></p>
                @endif
                @if($application->buyer->phone)
                <p class="text-sm text-gray-400 mt-1">Phone: <span class="text-white">{{ $application->buyer->phone }}</span></p>
                @endif
                @endif
            </div>

            {{-- Application Details --}}
            <div class="rounded-xl border p-6" style="background-color: #1e293b; border-color: #334155;">
                <h3 class="text-sm font-medium text-gray-400 mb-3">Application Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Requested Limit</span>
                        <span class="text-white font-medium">${{ number_format($application->credit_limit, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Term</span>
                        <span class="text-white font-medium">{{ $application->term_days }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Interest Rate</span>
                        <span class="text-white font-medium">{{ number_format($application->interest_rate, 1) }}%</span>
                    </div>
                    @if($application->status === 'active')
                    <div class="flex justify-between">
                        <span class="text-gray-400">Available Credit</span>
                        <span class="text-green-400 font-medium">${{ number_format($application->available_credit, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Approval Info --}}
            <div class="rounded-xl border p-6" style="background-color: #1e293b; border-color: #334155;">
                <h3 class="text-sm font-medium text-gray-400 mb-3">Approval Info</h3>
                @if($application->approver)
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Approved By</span>
                        <span class="text-white">{{ $application->approver->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Approved At</span>
                        <span class="text-white">{{ $application->approved_at?->format('M d, Y') }}</span>
                    </div>
                    @if($application->expires_at)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Expires</span>
                        <span class="text-white">{{ $application->expires_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-gray-500 text-sm">Not yet reviewed</p>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if($application->notes)
        <div class="rounded-xl border p-6 mb-8" style="background-color: #1e293b; border-color: #334155;">
            <h3 class="text-sm font-medium text-gray-400 mb-3">Notes</h3>
            <p class="text-gray-300 text-sm whitespace-pre-line">{{ $application->notes }}</p>
        </div>
        @endif

        {{-- Approve / Reject Forms (only for pending) --}}
        @if($application->status === 'pending')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Approve Form --}}
            <div class="rounded-xl border p-6" style="background-color: #1e293b; border-color: #10b98144;">
                <h3 class="text-lg font-semibold text-green-400 mb-4">Approve Application</h3>
                <form method="POST" action="{{ route('admin.credits.approve', $application->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="credit_limit" class="block text-sm font-medium text-gray-300 mb-1">Set Credit Limit ($)</label>
                        <input type="number" name="credit_limit" id="credit_limit" step="0.01" min="1"
                               value="{{ old('credit_limit', $application->credit_limit) }}"
                               class="w-full px-4 py-2.5 rounded-lg border text-white placeholder-gray-500 focus:outline-none"
                               style="background-color: #0f1419; border-color: #334155;" required>
                    </div>

                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-300 mb-1">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" id="interest_rate" step="0.01" min="0" max="100"
                               value="{{ old('interest_rate', '0') }}"
                               class="w-full px-4 py-2.5 rounded-lg border text-white placeholder-gray-500 focus:outline-none"
                               style="background-color: #0f1419; border-color: #334155;" required>
                    </div>

                    <div>
                        <label for="approve_notes" class="block text-sm font-medium text-gray-300 mb-1">Notes (optional)</label>
                        <textarea name="notes" id="approve_notes" rows="2"
                                  class="w-full px-4 py-2.5 rounded-lg border text-white placeholder-gray-500 focus:outline-none"
                                  style="background-color: #0f1419; border-color: #334155;"
                                  placeholder="Any notes..."></textarea>
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                            style="background-color: #10b981; color: white;"
                            onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'"
                            onclick="return confirm('Approve this credit application?')">
                        Approve Credit
                    </button>
                </form>
            </div>

            {{-- Reject Form --}}
            <div class="rounded-xl border p-6" style="background-color: #1e293b; border-color: #ef444444;">
                <h3 class="text-lg font-semibold text-red-400 mb-4">Reject Application</h3>
                <form method="POST" action="{{ route('admin.credits.reject', $application->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="reject_notes" class="block text-sm font-medium text-gray-300 mb-1">Reason for Rejection</label>
                        <textarea name="notes" id="reject_notes" rows="4"
                                  class="w-full px-4 py-2.5 rounded-lg border text-white placeholder-gray-500 focus:outline-none"
                                  style="background-color: #0f1419; border-color: #334155;"
                                  placeholder="Explain why this application was rejected..."></textarea>
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                            style="background-color: #ef4444; color: white;"
                            onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'"
                            onclick="return confirm('Reject this credit application?')">
                        Reject Application
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Credit Order History --}}
        <div class="rounded-xl shadow-sm border" style="background-color: #1e293b; border-color: #334155;">
            <div class="px-6 py-4 border-b" style="border-color: #334155;">
                <h2 class="text-lg font-semibold text-white">Credit Order History</h2>
            </div>
            @if($application->creditOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #334155;">
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Order</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Amount</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Due Date</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Status</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-400">Paid At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($application->creditOrders as $co)
                        <tr style="border-bottom: 1px solid #1e293b44;">
                            <td class="py-3 px-6">
                                <a href="{{ route('admin.orders.show', $co->order_id) }}" class="font-medium" style="color: #f59e0b;">
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
                            <td class="py-3 px-6 text-gray-400">{{ $co->paid_at?->format('M d, Y') ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-500">No credit orders yet for this buyer.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
