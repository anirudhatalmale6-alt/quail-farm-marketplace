@extends('layouts.app')

@section('title', ($farmer->farm_name ?? $farmer->name) . ' - Investor View')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Farmer Profile Card -->
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden">
        <div class="h-28 bg-gradient-to-r from-[#10b981]/20 via-[#0f1419] to-[#d4a853]/10"></div>
        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10">
                <div class="relative">
                    <img src="{{ $farmer->profile_picture_url }}" class="w-24 h-24 rounded-full border-4 border-[#1e293b] object-cover bg-[#374151]">
                    <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-[#1e293b] {{ $farmer->isOnlineNow() ? 'bg-[#10b981]' : 'bg-[#6b7280]' }}"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-white">{{ $farmer->farm_name ?? $farmer->name }}</h1>
                        <span class="px-2 py-0.5 text-xs rounded {{ $farmer->isOnlineNow() ? 'bg-[#10b981]/20 text-[#10b981]' : 'bg-[#374151] text-[#6b7280]' }}">
                            {{ $farmer->isOnlineNow() ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    <p class="text-[#9ca3af]">{{ $farmer->name }} - {{ $farmer->city }}{{ $farmer->state ? ', ' . $farmer->state : '' }}</p>
                </div>
                <a href="{{ route('messages.show', $farmer->id) }}" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg font-semibold text-sm hover:bg-[#f59e0b] transition-colors flex-shrink-0">
                    Contact Farmer
                </a>
            </div>
        </div>
    </div>

    @if($farmer->bio)
        <div class="mt-4 bg-[#1e293b] border border-[#374151] rounded-xl p-5">
            <p class="text-[#9ca3af]">{{ $farmer->bio }}</p>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mt-4">
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-[#d4a853]">{{ $farmer->products_count }}</p>
            <p class="text-xs text-[#9ca3af]">Products</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $farmer->reviews_count }}</p>
            <p class="text-xs text-[#9ca3af]">Reviews</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $farmer->created_at->format('M Y') }}</p>
            <p class="text-xs text-[#9ca3af]">Joined</p>
        </div>
    </div>

    <!-- Products -->
    @if($products->isNotEmpty())
        <div class="mt-6">
            <h2 class="text-lg font-bold text-white mb-4">Products ({{ $products->count() }})</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4">
                        <h3 class="text-white font-semibold">{{ $product->name }}</h3>
                        <p class="text-[#d4a853] font-bold mt-1">${{ number_format($product->price, 2) }} / {{ $product->unit }}</p>
                        <p class="text-xs text-[#6b7280] mt-2">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Investment Applications -->
    <div class="mt-6">
        <h2 class="text-lg font-bold text-white mb-4">Investment Applications</h2>
        @forelse($applications as $app)
            <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-5 mb-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ $app->title }}</h3>
                        <span class="text-xs px-2 py-0.5 rounded capitalize
                            {{ $app->status === 'funded' ? 'bg-[#10b981]/10 text-[#10b981]' : ($app->status === 'submitted' ? 'bg-[#d4a853]/10 text-[#d4a853]' : 'bg-[#374151] text-[#9ca3af]') }}">
                            {{ str_replace('_', ' ', $app->status) }}
                        </span>
                    </div>
                    <p class="text-xl font-bold text-[#d4a853]">${{ number_format($app->amount_requested, 0) }}</p>
                </div>
                <p class="text-sm text-[#9ca3af] mb-3">{{ \Illuminate\Support\Str::limit($app->business_plan, 200) }}</p>
                <div class="grid grid-cols-3 gap-3 text-xs mb-4">
                    @if($app->expected_roi)
                        <div class="bg-[#0f1419] rounded p-2"><span class="text-[#6b7280]">Expected ROI:</span> <span class="text-white">{{ $app->expected_roi }}</span></div>
                    @endif
                    @if($app->timeline)
                        <div class="bg-[#0f1419] rounded p-2"><span class="text-[#6b7280]">Timeline:</span> <span class="text-white">{{ $app->timeline }}</span></div>
                    @endif
                    @if($app->current_farm_size)
                        <div class="bg-[#0f1419] rounded p-2"><span class="text-[#6b7280]">Farm Size:</span> <span class="text-white">{{ $app->current_farm_size }}</span></div>
                    @endif
                </div>

                @if(in_array($app->status, ['submitted', 'under_review', 'approved']))
                    <form action="{{ route('investor.invest', $app->id) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <input type="text" name="notes" placeholder="Add a note (optional)"
                            class="flex-1 px-3 py-2 bg-[#0f1419] border border-[#374151] text-white text-sm rounded-lg focus:ring-1 focus:ring-[#10b981] outline-none">
                        <button type="submit" class="px-4 py-2 bg-[#10b981] text-white rounded-lg font-semibold text-sm hover:bg-[#059669] transition-colors"
                            onclick="return confirm('Confirm investment of ${{ number_format($app->amount_requested, 0) }} in this farming project?')">
                            Invest Now
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-8 text-center">
                <p class="text-[#6b7280]">This farmer has no open investment applications.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
