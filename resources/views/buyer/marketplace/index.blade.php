@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Marketplace</h1>
        <p class="mt-1 text-[#9ca3af]">Browse fresh quail products from verified local farmers</p>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-4 mb-8">
        <form action="{{ route('buyer.marketplace.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853]"
                    placeholder="Search products...">
            </div>

            {{-- Category Filter --}}
            <div class="sm:w-48">
                <select name="category"
                    class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div class="sm:w-44">
                <select name="sort"
                    class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853]">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                </select>
            </div>

            {{-- Search Button --}}
            <button type="submit" class="px-6 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors shadow-sm">
                Search
            </button>
        </form>
    </div>

    {{-- Products Grid --}}
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <a href="{{ route('buyer.marketplace.show', $product->id) }}" class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] overflow-hidden hover:shadow-lg hover:shadow-[#d4a853]/10 hover:brightness-110 transition-all group block">
            {{-- Product Image --}}
            <div class="relative overflow-hidden">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-52 flex items-center justify-center bg-gradient-to-br from-[#1e293b] to-[#0f1419]">
                        <svg class="w-16 h-16 text-[#374151]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                {{-- Category Badge --}}
                @if($product->category)
                <div class="absolute top-3 left-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#0f1419]/80 text-[#d4a853] backdrop-blur-sm border border-[#d4a853]/20">
                        {{ $product->category->name }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-4">
                <h3 class="text-sm font-semibold text-white truncate group-hover:text-[#d4a853] transition-colors">{{ $product->name }}</h3>

                {{-- Farmer Info --}}
                <div class="flex items-center mt-2">
                    <img src="{{ $product->user->avatar ?? '' }}" alt="" class="w-5 h-5 rounded-full mr-1.5 object-cover">
                    <span class="text-xs text-[#9ca3af]">{{ $product->user->farm_name ?? $product->user->name }}</span>
                </div>

                {{-- Rating --}}
                <div class="flex items-center mt-2">
                    @php
                        $avgRating = $product->reviews->count() > 0 ? $product->reviews->avg('rating') : 0;
                    @endphp
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-[#374151]' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-[#9ca3af] ml-1">({{ $product->reviews->count() }})</span>
                </div>

                {{-- Price & Order --}}
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-[#374151]/50">
                    <div>
                        <span class="text-lg font-bold text-[#d4a853]">${{ number_format($product->price, 2) }}</span>
                        <span class="text-xs text-[#9ca3af]">/ {{ $product->unit }}</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#9ca3af] bg-[#374151]/50 rounded-lg group-hover:bg-[#d4a853] group-hover:text-[#0f1419] transition-colors">
                        Order
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-[#374151] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h3 class="text-lg font-semibold text-white mb-2">No products found</h3>
        <p class="text-[#9ca3af] mb-4">Try adjusting your search or filter criteria.</p>
        <a href="{{ route('buyer.marketplace.index') }}" class="inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors">
            Clear Filters
        </a>
    </div>
    @endif
</div>
@endsection
