@extends('layouts.app')

@section('title', 'My Investments - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Investment <span class="text-[#d4a853]">Applications</span></h1>
            <p class="mt-1 text-sm text-[#9ca3af]">Submit proposals to grow your quail farming operation.</p>
        </div>
        @if(auth()->user()->isPro())
            <a href="{{ route('farmer.investments.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Application
            </a>
        @else
            <a href="{{ route('pricing') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-[#374151] text-[#9ca3af] font-medium rounded-xl hover:bg-[#4b5563] transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Upgrade to Pro to Apply
            </a>
        @endif
    </div>

    @if($applications->isEmpty())
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 text-[#374151] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <h3 class="text-lg font-semibold text-white mb-2">No Applications Yet</h3>
            <p class="text-[#9ca3af] mb-6">Submit your first investment application to expand your farming business.</p>
            @if(auth()->user()->isPro())
                <a href="{{ route('farmer.investments.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors">
                    Create Application
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <a href="{{ route('farmer.investments.show', $application->id) }}" class="block bg-[#1e293b] border border-[#374151] rounded-xl p-6 hover:border-[#d4a853]/40 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-white">{{ $application->title }}</h3>
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
                            </div>
                            <p class="text-sm text-[#9ca3af] line-clamp-1">{{ Str::limit($application->business_plan, 120) }}</p>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-6 sm:text-right flex-shrink-0">
                            <p class="text-lg font-bold text-[#d4a853]">${{ number_format($application->amount_requested, 2) }}</p>
                            <p class="text-xs text-[#9ca3af]">{{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
