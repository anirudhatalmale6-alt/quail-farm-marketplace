@extends('layouts.app')

@section('title', isset($application) ? 'Edit Application - QuailConnect' : 'New Investment Application - QuailConnect')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('farmer.investments.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Applications
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-white">
            {{ isset($application) ? 'Edit' : 'New' }} Investment <span class="text-[#d4a853]">Application</span>
        </h1>
        <p class="mt-1 text-sm text-[#9ca3af]">Provide details about your farm expansion or investment needs.</p>
    </div>

    <form method="POST" action="{{ isset($application) ? route('farmer.investments.update', $application->id) : route('farmer.investments.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($application))
            @method('PUT')
        @endif

        {{-- Title --}}
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Application Details</h3>

            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-[#9ca3af] mb-2">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $application->title ?? '') }}" required
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]"
                           placeholder="e.g., Expand Quail Farm to 5000 birds">
                    @error('title')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="business_plan" class="block text-sm font-medium text-[#9ca3af] mb-2">Business Plan</label>
                    <textarea name="business_plan" id="business_plan" rows="8" required
                              class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853] resize-y"
                              placeholder="Describe your business plan, goals, and how the investment will be used...">{{ old('business_plan', $application->business_plan ?? '') }}</textarea>
                    @error('business_plan')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="financial_projections" class="block text-sm font-medium text-[#9ca3af] mb-2">Financial Projections <span class="text-[#6b7280]">(optional)</span></label>
                    <textarea name="financial_projections" id="financial_projections" rows="5"
                              class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853] resize-y"
                              placeholder="Revenue projections, cost breakdown, expected profits...">{{ old('financial_projections', $application->financial_projections ?? '') }}</textarea>
                    @error('financial_projections')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Financial Details --}}
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Financial Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="amount_requested" class="block text-sm font-medium text-[#9ca3af] mb-2">Amount Requested ($)</label>
                    <input type="number" name="amount_requested" id="amount_requested" step="0.01" min="100" required
                           value="{{ old('amount_requested', $application->amount_requested ?? '') }}"
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]"
                           placeholder="10000.00">
                    @error('amount_requested')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expected_roi" class="block text-sm font-medium text-[#9ca3af] mb-2">Expected ROI <span class="text-[#6b7280]">(optional)</span></label>
                    <input type="text" name="expected_roi" id="expected_roi"
                           value="{{ old('expected_roi', $application->expected_roi ?? '') }}"
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]"
                           placeholder="e.g., 25% annually">
                    @error('expected_roi')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_farm_size" class="block text-sm font-medium text-[#9ca3af] mb-2">Current Farm Size <span class="text-[#6b7280]">(optional)</span></label>
                    <input type="text" name="current_farm_size" id="current_farm_size"
                           value="{{ old('current_farm_size', $application->current_farm_size ?? '') }}"
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]"
                           placeholder="e.g., 2000 birds, 1 acre">
                    @error('current_farm_size')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timeline" class="block text-sm font-medium text-[#9ca3af] mb-2">Timeline <span class="text-[#6b7280]">(optional)</span></label>
                    <input type="text" name="timeline" id="timeline"
                           value="{{ old('timeline', $application->timeline ?? '') }}"
                           class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]"
                           placeholder="e.g., 12 months">
                    @error('timeline')
                        <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-[#1e293b] border border-[#374151] rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Supporting Documents <span class="text-sm font-normal text-[#6b7280]">(optional)</span></h3>

            @if(isset($application) && $application->documents)
                <div class="mb-4 space-y-2">
                    <p class="text-sm text-[#9ca3af] mb-2">Existing documents:</p>
                    @foreach($application->documents as $doc)
                        <div class="flex items-center text-sm text-[#9ca3af] bg-[#0f1419] px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ basename($doc) }}
                        </div>
                    @endforeach
                </div>
            @endif

            <input type="file" name="documents[]" multiple
                   class="w-full px-4 py-3 bg-[#0f1419] border border-[#374151] rounded-xl text-[#9ca3af] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#d4a853] file:text-[#0f1419] file:font-medium file:cursor-pointer hover:file:bg-[#f59e0b]">
            <p class="text-xs text-[#6b7280] mt-2">Upload business plans, financial statements, or other supporting documents. Max 10MB per file.</p>
            @error('documents.*')
                <p class="text-sm text-[#ef4444] mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" name="status" value="submitted" class="flex-1 px-6 py-3 bg-[#d4a853] text-[#0f1419] font-semibold rounded-xl hover:bg-[#f59e0b] transition-colors shadow-lg shadow-[#d4a853]/20">
                Submit Application
            </button>
            <button type="submit" name="status" value="draft" class="flex-1 px-6 py-3 bg-[#374151] text-white font-medium rounded-xl hover:bg-[#4b5563] transition-colors">
                Save as Draft
            </button>
        </div>
    </form>
</div>
@endsection
