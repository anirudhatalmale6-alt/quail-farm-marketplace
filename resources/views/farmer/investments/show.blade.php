@extends('layouts.app')

@section('title', $application->title . ' - QuailConnect')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('farmer.investments.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Applications
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $application->title }}</h1>
                <p class="mt-1 text-sm text-[#9ca3af]">Submitted {{ $application->created_at->format('M d, Y') }}</p>
            </div>
            @if($application->status === 'draft')
                <a href="{{ route('farmer.investments.edit', $application->id) }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Draft
                </a>
            @endif
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#9ca3af] uppercase tracking-wider mb-4">Application Status</h3>
        @php
            $statuses = ['draft', 'submitted', 'under_review', 'approved', 'funded'];
            $currentIndex = array_search($application->status, $statuses);
            if ($application->status === 'rejected') $currentIndex = 3;
        @endphp
        <div class="flex items-center justify-between">
            @foreach($statuses as $i => $status)
                <div class="flex flex-col items-center flex-1 {{ $i < count($statuses) - 1 ? 'relative' : '' }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $application->status === 'rejected' && $status === 'approved' ? 'bg-[#ef4444] text-white' : '' }}
                        {{ $i <= $currentIndex && $application->status !== 'rejected' ? 'bg-[#d4a853] text-[#0f1419]' : '' }}
                        {{ $i > $currentIndex || ($application->status === 'rejected' && $status !== 'approved') ? 'bg-[#374151] text-[#6b7280]' : '' }}">
                        @if($application->status === 'rejected' && $status === 'approved')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @elseif($i < $currentIndex)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-[#9ca3af] text-center hidden sm:block">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    @if($i < count($statuses) - 1)
                        <div class="absolute top-4 left-1/2 w-full h-0.5 {{ $i < $currentIndex ? 'bg-[#d4a853]' : 'bg-[#374151]' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
        @if($application->status === 'rejected')
            <div class="mt-4 bg-[#ef4444]/10 border border-[#ef4444]/30 rounded-xl p-4">
                <p class="text-sm text-[#ef4444] font-medium">Application Rejected</p>
                @if($application->admin_notes)
                    <p class="text-sm text-[#9ca3af] mt-1">{{ $application->admin_notes }}</p>
                @endif
            </div>
        @endif
    </div>

    {{-- Application Details --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">Amount Requested</p>
            <p class="text-2xl font-bold text-[#d4a853]">${{ number_format($application->amount_requested, 2) }}</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">Expected ROI</p>
            <p class="text-2xl font-bold text-white">{{ $application->expected_roi ?? 'N/A' }}</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <p class="text-xs text-[#9ca3af] uppercase tracking-wider mb-1">Timeline</p>
            <p class="text-2xl font-bold text-white">{{ $application->timeline ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Business Plan --}}
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Business Plan</h3>
        <div class="prose prose-invert max-w-none text-[#9ca3af] whitespace-pre-line">{{ $application->business_plan }}</div>
    </div>

    {{-- Financial Projections --}}
    @if($application->financial_projections)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Financial Projections</h3>
            <div class="prose prose-invert max-w-none text-[#9ca3af] whitespace-pre-line">{{ $application->financial_projections }}</div>
        </div>
    @endif

    {{-- Additional Info --}}
    @if($application->current_farm_size)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Farm Information</h3>
            <p class="text-[#9ca3af]"><span class="text-white font-medium">Current Farm Size:</span> {{ $application->current_farm_size }}</p>
        </div>
    @endif

    {{-- Documents --}}
    @if($application->documents && count($application->documents) > 0)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Supporting Documents</h3>
            <div class="space-y-2">
                @foreach($application->documents as $doc)
                    <div class="flex items-center text-sm text-[#9ca3af] bg-[#0f1419] px-4 py-3 rounded-xl">
                        <svg class="w-5 h-5 mr-3 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ basename($doc) }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Admin Review --}}
    @if($application->reviewed_at)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Admin Review</h3>
            <div class="space-y-3">
                <p class="text-sm text-[#9ca3af]">
                    <span class="text-white font-medium">Reviewed by:</span> {{ $application->reviewer->name ?? 'Admin' }}
                </p>
                <p class="text-sm text-[#9ca3af]">
                    <span class="text-white font-medium">Reviewed on:</span> {{ $application->reviewed_at->format('M d, Y \a\t h:i A') }}
                </p>
                @if($application->admin_notes)
                    <div class="mt-3 bg-[#0f1419] rounded-xl p-4">
                        <p class="text-sm text-[#9ca3af]">{{ $application->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
