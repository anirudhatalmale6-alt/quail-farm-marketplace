@extends('layouts.app')

@section('title', 'Review: ' . $application->title . ' - Admin - QuailConnect')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.investments.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Applications
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $application->title }}</h1>
                <p class="mt-1 text-sm text-[#9ca3af]">Submitted by {{ $application->user->name }} on {{ $application->created_at->format('M d, Y') }}</p>
            </div>
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
            <span class="mt-4 sm:mt-0 px-4 py-1.5 text-sm font-medium rounded-full {{ $statusColors[$application->status] ?? 'bg-[#374151] text-[#9ca3af]' }}">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>
    </div>

    {{-- Farmer Info --}}
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#9ca3af] uppercase tracking-wider mb-4">Farmer Information</h3>
        <div class="flex items-center space-x-4">
            <img src="{{ $application->user->avatar }}" alt="" class="w-12 h-12 rounded-full border-2 border-[#d4a853]">
            <div>
                <p class="text-white font-medium">{{ $application->user->name }}</p>
                <p class="text-sm text-[#9ca3af]">{{ $application->user->farm_name ?? 'No farm name' }} &middot; {{ $application->user->city ?? '' }}{{ $application->user->state ? ', ' . $application->user->state : '' }}</p>
                <p class="text-xs text-[#6b7280]">{{ $application->user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
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
        <div class="text-[#9ca3af] whitespace-pre-line">{{ $application->business_plan }}</div>
    </div>

    {{-- Financial Projections --}}
    @if($application->financial_projections)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Financial Projections</h3>
            <div class="text-[#9ca3af] whitespace-pre-line">{{ $application->financial_projections }}</div>
        </div>
    @endif

    {{-- Farm Size --}}
    @if($application->current_farm_size)
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6 mb-6">
            <p class="text-sm text-[#9ca3af]"><span class="text-white font-medium">Current Farm Size:</span> {{ $application->current_farm_size }}</p>
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

    {{-- Admin Review Form --}}
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Review Decision</h3>

        @if($application->reviewed_at)
            <div class="mb-4 bg-[#0f1419] rounded-xl p-4">
                <p class="text-sm text-[#9ca3af]">
                    <span class="text-white font-medium">Last reviewed by:</span> {{ $application->reviewer->name ?? 'Admin' }}
                    on {{ $application->reviewed_at->format('M d, Y \a\t h:i A') }}
                </p>
                @if($application->admin_notes)
                    <p class="text-sm text-[#9ca3af] mt-2"><span class="text-white font-medium">Notes:</span> {{ $application->admin_notes }}</p>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('admin.investments.review', $application->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-5">
                <label for="admin_notes" class="block text-sm font-medium text-[#9ca3af] mb-2">Admin Notes</label>
                <textarea name="admin_notes" id="admin_notes" rows="4"
                          class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853] resize-y"
                          placeholder="Add notes about this application...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                @error('admin_notes')
                    <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" name="status" value="under_review" class="px-5 py-2.5 bg-[#f59e0b] text-[#0f1419] font-semibold rounded-xl hover:bg-[#fbbf24] transition-colors">
                    Mark Under Review
                </button>
                <button type="submit" name="status" value="approved" class="px-5 py-2.5 bg-[#10b981] text-white font-semibold rounded-xl hover:bg-[#34d399] transition-colors">
                    Approve
                </button>
                <button type="submit" name="status" value="rejected" class="px-5 py-2.5 bg-[#ef4444] text-white font-semibold rounded-xl hover:bg-[#f87171] transition-colors">
                    Reject
                </button>
                <button type="submit" name="status" value="funded" class="px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors">
                    Mark as Funded
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
