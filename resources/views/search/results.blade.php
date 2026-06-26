@extends('layouts.app')

@section('title', 'Search Results - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="relative max-w-2xl">
            <div class="flex">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search products, farmers, feed, streams..."
                        class="w-full pl-12 pr-4 py-4 bg-[#1e293b] border border-[#374151] rounded-l-xl text-white text-lg placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] focus:ring-1 focus:ring-[#d4a853]" autofocus>
                </div>
                <button type="submit" class="px-8 py-4 bg-[#d4a853] text-[#0f1419] font-bold rounded-r-xl hover:bg-[#f59e0b] transition-colors text-lg">
                    Search
                </button>
            </div>
        </form>
        @if($query)
            <p class="mt-3 text-[#9ca3af]">
                Found <span class="text-[#d4a853] font-semibold">{{ $total }}</span> results for "<span class="text-white">{{ $query }}</span>"
            </p>
        @endif
    </div>

    @if($query)
    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $filters = [
                'all' => ['label' => 'All Results', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                'products' => ['label' => 'Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                'farmers' => ['label' => 'Farmers', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                'feed' => ['label' => 'Feed Posts', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                'streams' => ['label' => 'Streams', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                'categories' => ['label' => 'Categories', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                'supplies' => ['label' => 'Supplies', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ];
        @endphp
        @foreach($filters as $key => $filter)
            <a href="{{ route('search', ['q' => $query, 'type' => $key]) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all {{ $type === $key ? 'bg-[#d4a853] text-[#0f1419]' : 'bg-[#1e293b] text-[#9ca3af] border border-[#374151] hover:border-[#d4a853] hover:text-[#d4a853]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $filter['icon'] }}"/></svg>
                {{ $filter['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Results Grid -->
    @if($results->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 mx-auto text-[#374151] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-[#9ca3af] mb-2">No results found</h3>
            <p class="text-[#6b7280]">Try different keywords or browse our categories</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($results as $result)
                <a href="{{ $result['url'] }}" class="block bg-[#1e293b] border border-[#374151] rounded-xl p-5 hover:border-[#d4a853] hover:shadow-lg hover:shadow-[#d4a853]/5 transition-all group">
                    <div class="flex items-start gap-4">
                        <!-- Type Icon -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                            @if($result['type'] === 'product') bg-[#10b981]/10 text-[#10b981]
                            @elseif($result['type'] === 'farmer') bg-[#3b82f6]/10 text-[#3b82f6]
                            @elseif($result['type'] === 'feed') bg-[#8b5cf6]/10 text-[#8b5cf6]
                            @elseif($result['type'] === 'stream') bg-[#ef4444]/10 text-[#ef4444]
                            @elseif($result['type'] === 'category') bg-[#d4a853]/10 text-[#d4a853]
                            @elseif($result['type'] === 'supply') bg-[#f97316]/10 text-[#f97316]
                            @endif">
                            @if($result['type'] === 'product')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @elseif($result['type'] === 'farmer')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @elseif($result['type'] === 'feed')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @elseif($result['type'] === 'stream')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @elseif($result['type'] === 'category')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            @elseif($result['type'] === 'supply')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-white font-semibold truncate group-hover:text-[#d4a853] transition-colors">{{ $result['title'] }}</h3>
                                @if($result['category'])
                                    <span class="flex-shrink-0 text-[10px] px-2 py-0.5 rounded-full bg-[#374151] text-[#9ca3af]">{{ $result['category'] }}</span>
                                @endif
                            </div>
                            @if($result['subtitle'])
                                <p class="text-sm text-[#d4a853] mb-1">{{ $result['subtitle'] }}</p>
                            @endif
                            @if($result['description'])
                                <p class="text-sm text-[#6b7280] line-clamp-2">{{ $result['description'] }}</p>
                            @endif
                            @if($result['meta'])
                                <p class="text-xs text-[#4b5563] mt-1">{{ $result['meta'] }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    @else
        <!-- Empty State - Show Trending -->
        <div class="text-center py-16">
            <svg class="w-20 h-20 mx-auto text-[#374151] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-white mb-2">Search QuailConnect</h3>
            <p class="text-[#9ca3af] mb-8 max-w-md mx-auto">Search across products, farmers, feed posts, streams, categories, and supplies. Start typing to see instant results.</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('search', ['q' => 'eggs']) }}" class="px-4 py-2 bg-[#1e293b] border border-[#374151] rounded-full text-sm text-[#9ca3af] hover:border-[#d4a853] hover:text-[#d4a853] transition-all">Eggs</a>
                <a href="{{ route('search', ['q' => 'quail']) }}" class="px-4 py-2 bg-[#1e293b] border border-[#374151] rounded-full text-sm text-[#9ca3af] hover:border-[#d4a853] hover:text-[#d4a853] transition-all">Quail</a>
                <a href="{{ route('search', ['q' => 'organic']) }}" class="px-4 py-2 bg-[#1e293b] border border-[#374151] rounded-full text-sm text-[#9ca3af] hover:border-[#d4a853] hover:text-[#d4a853] transition-all">Organic</a>
                <a href="{{ route('search', ['q' => 'feed']) }}" class="px-4 py-2 bg-[#1e293b] border border-[#374151] rounded-full text-sm text-[#9ca3af] hover:border-[#d4a853] hover:text-[#d4a853] transition-all">Feed</a>
                <a href="{{ route('search', ['q' => 'farm']) }}" class="px-4 py-2 bg-[#1e293b] border border-[#374151] rounded-full text-sm text-[#9ca3af] hover:border-[#d4a853] hover:text-[#d4a853] transition-all">Farm</a>
            </div>
        </div>
    @endif
</div>
@endsection
