@extends('layouts.app')

@section('title', 'Investment Applications - Admin - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Investment <span class="text-[#d4a853]">Applications</span></h1>
        <p class="mt-1 text-sm text-[#9ca3af]">Review and manage farmer investment proposals.</p>
    </div>

    {{-- Status Filters --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('admin.investments.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ !request('status') ? 'bg-[#d4a853] text-[#0f1419]' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#d4a853] hover:text-[#d4a853]' }}">
            All ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.investments.index', ['status' => 'submitted']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'submitted' ? 'bg-[#3b82f6] text-white' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#3b82f6] hover:text-[#3b82f6]' }}">
            Submitted ({{ $statusCounts['submitted'] }})
        </a>
        <a href="{{ route('admin.investments.index', ['status' => 'under_review']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'under_review' ? 'bg-[#f59e0b] text-[#0f1419]' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#f59e0b] hover:text-[#f59e0b]' }}">
            Under Review ({{ $statusCounts['under_review'] }})
        </a>
        <a href="{{ route('admin.investments.index', ['status' => 'approved']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'approved' ? 'bg-[#10b981] text-white' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#10b981] hover:text-[#10b981]' }}">
            Approved ({{ $statusCounts['approved'] }})
        </a>
        <a href="{{ route('admin.investments.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'rejected' ? 'bg-[#ef4444] text-white' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#ef4444] hover:text-[#ef4444]' }}">
            Rejected ({{ $statusCounts['rejected'] ?? 0 }})
        </a>
        <a href="{{ route('admin.investments.index', ['status' => 'funded']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'funded' ? 'bg-[#d4a853] text-[#0f1419]' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#d4a853] hover:text-[#d4a853]' }}">
            Funded ({{ $statusCounts['funded'] }})
        </a>
    </div>

    @if($applications->isEmpty())
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 text-[#374151] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-semibold text-white mb-2">No Applications Found</h3>
            <p class="text-[#9ca3af]">No investment applications match the selected filter.</p>
        </div>
    @else
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#374151]">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Farmer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-[#9ca3af] uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#374151]">
                        @foreach($applications as $application)
                            <tr class="hover:bg-[#374151]/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $application->user->avatar }}" alt="" class="w-8 h-8 rounded-full mr-3">
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $application->user->name }}</p>
                                            <p class="text-xs text-[#9ca3af]">{{ $application->user->farm_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-white">{{ Str::limit($application->title, 40) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-[#d4a853]">${{ number_format($application->amount_requested, 2) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-[#374151] text-[#9ca3af]',
                                            'submitted' => 'bg-[#3b82f6]/10 text-[#3b82f6]',
                                            'under_review' => 'bg-[#f59e0b]/10 text-[#f59e0b]',
                                            'approved' => 'bg-[#10b981]/10 text-[#10b981]',
                                            'rejected' => 'bg-[#ef4444]/10 text-[#ef4444]',
                                            'funded' => 'bg-[#d4a853]/10 text-[#d4a853]',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColors[$application->status] ?? 'bg-[#374151] text-[#9ca3af]' }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-[#9ca3af]">{{ $application->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.investments.show', $application->id) }}" class="text-sm text-[#d4a853] hover:text-[#f59e0b] font-medium transition-colors">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $applications->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
