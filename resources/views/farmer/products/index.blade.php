@extends('layouts.app')

@section('title', 'My Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">My Products</h1>
            <p class="mt-1 text-[#9ca3af]">Manage your product listings</p>
        </div>
        <a href="{{ route('farmer.products.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Product
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-900/30 border border-green-700 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-green-300 text-sm">{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-green-400 hover:text-green-300">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
    @endif

    {{-- Products Grid --}}
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] overflow-hidden hover:shadow-lg hover:shadow-[#d4a853]/5 transition-shadow group">
            {{-- Product Image --}}
            <div class="aspect-w-4 aspect-h-3 bg-[#0f1419] relative overflow-hidden">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-48 flex items-center justify-center bg-[#0f1419]">
                        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                {{-- Status Badge --}}
                <div class="absolute top-3 right-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-900/30 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="p-4">
                <h3 class="text-sm font-semibold text-white truncate">{{ $product->name }}</h3>
                <p class="text-xs text-[#9ca3af] mt-1">{{ $product->category->name ?? 'Uncategorized' }}</p>

                <div class="mt-3 flex items-center justify-between">
                    <div>
                        <span class="text-lg font-bold text-[#d4a853]">${{ number_format($product->price, 2) }}</span>
                        <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                    </div>
                    <span class="text-xs text-[#9ca3af]">Stock: {{ $product->quantity_available }}</span>
                </div>

                {{-- Actions --}}
                <div class="mt-4 flex items-center space-x-2">
                    <a href="{{ route('farmer.products.edit', $product->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-[#d4a853] bg-[#d4a853]/10 rounded-lg hover:bg-[#d4a853]/20 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('farmer.products.destroy', $product->id) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Are you sure you want to delete this product?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-400 bg-red-900/20 rounded-lg hover:bg-red-900/40 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @else
    {{-- Empty State --}}
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-lg font-semibold text-white mb-2">No products yet</h3>
        <p class="text-[#9ca3af] mb-6">Start by adding your first product to the marketplace.</p>
        <a href="{{ route('farmer.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#d4a853] text-[#0f1419] font-medium rounded-lg hover:bg-[#f59e0b] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Your First Product
        </a>
    </div>
    @endif
</div>
@endsection
