@extends('layouts.app')

@section('title', 'Streams - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Streams</h1>
            <p class="text-[#9ca3af] text-sm mt-1">Watch short videos from our quail farming community</p>
        </div>
        @if(auth()->user()->isFarmer() || auth()->user()->isAdmin())
            <a href="{{ route('streams.create') }}" class="bg-[#d4a853] text-[#0f1419] font-semibold px-5 py-2.5 rounded-lg hover:bg-[#f59e0b] transition-colors text-sm flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Upload Stream</span>
            </a>
        @endif
    </div>

    <!-- Category Filter Pills -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-4 mb-6 scrollbar-hide">
        @foreach($categories as $key => $label)
            <a
                href="{{ route('streams.index', ['category' => $key]) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors {{ ($category ?? 'all') === $key ? 'bg-[#d4a853] text-[#0f1419]' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#d4a853] hover:text-[#d4a853]' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Streams Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($streams as $stream)
        <a href="{{ route('streams.show', $stream->id) }}" class="bg-[#1e293b] rounded-xl border border-[#374151] overflow-hidden hover:border-[#d4a853]/50 transition-all group">
            <!-- Thumbnail -->
            <div class="relative aspect-[9/16] max-h-72 overflow-hidden bg-[#0f1419]">
                @if($stream->thumbnail)
                    <img src="{{ asset('storage/' . $stream->thumbnail) }}" alt="{{ $stream->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1e293b] to-[#0f1419]">
                        <svg class="w-16 h-16 text-[#374151]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                @endif

                <!-- Play Button Overlay -->
                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="w-14 h-14 bg-[#d4a853] rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-[#0f1419] ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>

                <!-- Duration Badge -->
                @if($stream->duration)
                    <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs font-medium px-2 py-1 rounded">
                        {{ $stream->duration }}
                    </div>
                @endif

                <!-- Category Badge -->
                <div class="absolute top-2 left-2">
                    @php
                        $catColors = [
                            'marketing' => 'bg-[#3b82f6]',
                            'farm_tour' => 'bg-[#10b981]',
                            'product_highlight' => 'bg-[#d4a853]',
                            'announcement' => 'bg-[#ef4444]',
                            'tutorial' => 'bg-[#8b5cf6]',
                        ];
                        $catLabels = [
                            'marketing' => 'Marketing',
                            'farm_tour' => 'Farm Tour',
                            'product_highlight' => 'Product',
                            'announcement' => 'News',
                            'tutorial' => 'Tutorial',
                        ];
                    @endphp
                    <span class="text-xs text-white font-medium px-2 py-1 rounded {{ $catColors[$stream->category] ?? 'bg-[#374151]' }}">
                        {{ $catLabels[$stream->category] ?? ucfirst($stream->category) }}
                    </span>
                </div>
            </div>

            <!-- Stream Info -->
            <div class="p-4">
                <h3 class="text-white font-medium text-sm line-clamp-2 mb-2">{{ $stream->title }}</h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <img src="{{ $stream->user->avatar }}" alt="" class="w-6 h-6 rounded-full border border-[#374151]">
                        <span class="text-[#9ca3af] text-xs">{{ $stream->user->name }}</span>
                    </div>
                    <div class="flex items-center space-x-3 text-[#6b7280] text-xs">
                        <span class="flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ number_format($stream->views_count) }}</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>{{ number_format($stream->likes_count) }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full bg-[#1e293b] rounded-xl border border-[#374151] p-12 text-center">
            <svg class="w-16 h-16 text-[#374151] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <h3 class="text-lg font-semibold text-white mb-2">No streams yet</h3>
            <p class="text-[#9ca3af] text-sm">Be the first to share a video with the community!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($streams->hasPages())
    <div class="mt-8">
        {{ $streams->links() }}
    </div>
    @endif
</div>
@endsection
