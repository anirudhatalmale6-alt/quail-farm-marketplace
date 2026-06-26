@extends('layouts.app')

@section('title', 'Credit Management - QuailConnect Admin')

@section('content')
<div class="min-h-screen" style="background-color: #0f1419;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Credit Applications</h1>
            <p class="mt-1 text-gray-400">Review and manage buyer credit requests</p>
        </div>

        {{-- Status Filters --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php
                $statuses = ['all' => 'All', 'pending' => 'Pending', 'active' => 'Active', 'approved' => 'Approved', 'rejected' => 'Rejected', 'expired' => 'Expired'];
                $currentStatus = request('status', 'all');
            @endphp
            @foreach($statuses as $value => $label)
                <a href="{{ route('admin.credits.index', $value === 'all' ? [] : ['status' => $value]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                   style="{{ $currentStatus === $value ? 'background-color: #f59e0b; color: #0f1419;' : 'background-color: #1e293b; color: #9ca3af; border: 1px solid #334155;' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Applications Table --}}
        <div class="rounded-xl shadow-sm border overflow-hidden" style="background-color: #1e293b; border-color: #334155;">
            @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #334155;">
                            <th class="text-left py-4 px-6 font-medium text-gray-400">Buyer</th>
                            <th class="text-left py-4 px-6 font-medium text-gray-400">Requested Limit</th>
                            <th class="text-left py-4 px-6 font-medium text-gray-400">Term</th>
                            <th class="text-left py-4 px-6 font-medium text-gray-400">Status</th>
                            <th class="text-left py-4 px-6 font-medium text-gray-400">Applied</th>
                            <th class="text-right py-4 px-6 font-medium text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr style="border-bottom: 1px solid #1e293b44;">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <img src="{{ $app->buyer->avatar ?? '' }}" alt="" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-white font-medium">{{ $app->buyer->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $app->buyer->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-white font-medium">${{ number_format($app->credit_limit, 2) }}</td>
                            <td class="py-4 px-6 text-gray-300">{{ $app->term_days }} days</td>
                            <td class="py-4 px-6">
                                @php
                                    $statusStyles = [
                                        'pending' => 'background-color: #f59e0b22; color: #f59e0b;',
                                        'active' => 'background-color: #10b98122; color: #10b981;',
                                        'approved' => 'background-color: #10b98122; color: #10b981;',
                                        'rejected' => 'background-color: #ef444422; color: #ef4444;',
                                        'expired' => 'background-color: #6b728022; color: #6b7280;',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="{{ $statusStyles[$app->status] ?? 'color: #9ca3af;' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400">{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.credits.show', $app->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                   style="background-color: #f59e0b22; color: #f59e0b;"
                                   onmouseover="this.style.backgroundColor='#f59e0b33'" onmouseout="this.style.backgroundColor='#f59e0b22'">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
            <div class="px-6 py-4 border-t" style="border-color: #334155;">
                {{ $applications->links() }}
            </div>
            @endif
            @else
            <div class="px-6 py-16 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 text-lg">No credit applications found.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
