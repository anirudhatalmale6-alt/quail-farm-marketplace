@extends('layouts.app')

@section('title', 'Apply for Credit - QuailConnect')

@section('content')
<div class="min-h-screen" style="background-color: #0f1419;">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Back Link --}}
        <a href="{{ route('buyer.credit.index') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Credit Dashboard
        </a>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Apply for Credit</h1>
            <p class="mt-2 text-gray-400">Request a buy-now-pay-later credit line for your quail orders.</p>
        </div>

        {{-- Info Card --}}
        <div class="rounded-xl p-5 mb-8 border" style="background-color: #1e293b; border-color: #334155;">
            <div class="flex items-start">
                <svg class="w-6 h-6 flex-shrink-0 mr-3 mt-0.5" style="color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-white font-semibold text-sm">How Credit Works</h3>
                    <ul class="mt-2 text-sm text-gray-400 space-y-1">
                        <li>1. Submit your application with desired credit limit and term</li>
                        <li>2. An admin will review and approve your request</li>
                        <li>3. Once approved, select "Credit" as payment method when ordering</li>
                        <li>4. Pay the balance before the due date</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Application Form --}}
        <div class="rounded-xl shadow-lg border p-6" style="background-color: #1e293b; border-color: #334155;">
            <form method="POST" action="{{ route('buyer.credit.store') }}" class="space-y-6">
                @csrf

                {{-- Requested Credit Limit --}}
                <div>
                    <label for="credit_limit" class="block text-sm font-medium text-gray-300 mb-2">Requested Credit Limit ($)</label>
                    <input type="number" name="credit_limit" id="credit_limit" step="0.01" min="100" max="100000"
                           value="{{ old('credit_limit') }}"
                           class="w-full px-4 py-3 rounded-lg border text-white placeholder-gray-500 focus:outline-none focus:ring-2"
                           style="background-color: #0f1419; border-color: #334155; focus-ring-color: #f59e0b;"
                           placeholder="e.g., 5000.00" required>
                    @error('credit_limit')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Term Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Payment Term</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach([30, 60, 90, 120] as $term)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="term_days" value="{{ $term }}"
                                   class="peer sr-only" {{ old('term_days') == $term ? 'checked' : ($term === 30 && !old('term_days') ? 'checked' : '') }}>
                            <div class="flex flex-col items-center justify-center px-4 py-4 rounded-lg border-2 transition-all
                                        peer-checked:border-amber-500 peer-checked:bg-amber-500/10"
                                 style="border-color: #334155; background-color: #0f1419;">
                                <span class="text-2xl font-bold text-white">{{ $term }}</span>
                                <span class="text-xs text-gray-400 mt-1">days</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('term_days')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes / Reason --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Reason / Notes (optional)</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-4 py-3 rounded-lg border text-white placeholder-gray-500 focus:outline-none focus:ring-2"
                              style="background-color: #0f1419; border-color: #334155;"
                              placeholder="Tell us about your business and why you need credit...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full px-6 py-3 text-base font-semibold rounded-lg shadow-sm transition-colors"
                        style="background-color: #f59e0b; color: #0f1419;"
                        onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                    Submit Credit Application
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
