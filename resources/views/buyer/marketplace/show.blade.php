@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="mb-6">
        <ol class="flex items-center text-sm text-gray-500 space-x-2">
            <li><a href="{{ route('buyer.marketplace.index') }}" class="hover:text-emerald-600 transition-colors">Marketplace</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            @if($product->category)
            <li><a href="{{ route('buyer.marketplace.index', ['category' => $product->category_id]) }}" class="hover:text-emerald-600 transition-colors">{{ $product->category->name }}</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            @endif
            <li class="text-gray-900 font-medium truncate">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        {{-- Product Image --}}
        <div x-data="{ activeImage: 0 }">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                @if($product->images && count($product->images) > 0)
                    <img :src="'{{ asset('storage') }}/' + {{ json_encode($product->images) }}[activeImage]"
                        alt="{{ $product->name }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 flex items-center justify-center bg-gradient-to-br from-emerald-50 to-green-50">
                        <svg class="w-24 h-24 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Image Thumbnails --}}
            @if($product->images && count($product->images) > 1)
            <div class="grid grid-cols-5 gap-2">
                @foreach($product->images as $index => $image)
                <button @click="activeImage = {{ $index }}"
                    :class="activeImage === {{ $index }} ? 'ring-2 ring-emerald-500' : 'ring-1 ring-gray-200'"
                    class="rounded-lg overflow-hidden focus:outline-none">
                    <img src="{{ asset('storage/' . $image) }}" alt="" class="w-full h-16 object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Details --}}
        <div>
            {{-- Category --}}
            @if($product->category)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mb-3">
                {{ $product->category->name }}
            </span>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="flex items-center mb-4">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($product->reviews_avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="ml-2 text-sm text-gray-500">{{ number_format($product->reviews_avg_rating ?? 0, 1) }} ({{ $product->reviews_count }} {{ Str::plural('review', $product->reviews_count) }})</span>
            </div>

            {{-- Price --}}
            <div class="flex items-baseline mb-6">
                <span class="text-4xl font-bold text-emerald-600">${{ number_format($product->price, 2) }}</span>
                <span class="text-lg text-gray-400 ml-2">per {{ $product->unit }}</span>
            </div>

            {{-- Description --}}
            <div class="prose prose-sm text-gray-600 mb-6 max-w-none">
                <p>{{ $product->description }}</p>
            </div>

            {{-- Availability --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Available Stock</span>
                        <p class="font-semibold text-gray-900">{{ $product->quantity_available }} {{ Str::plural($product->unit, $product->quantity_available) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Minimum Order</span>
                        <p class="font-semibold text-gray-900">{{ $product->min_order }} {{ Str::plural($product->unit, $product->min_order) }}</p>
                    </div>
                </div>
            </div>

            {{-- Order Form --}}
            @auth
            <form action="{{ route('buyer.orders.store') }}" method="POST" class="mb-6" x-data="{ quantity: {{ $product->min_order }}, price: {{ $product->price }} }">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="space-y-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <div class="flex items-center space-x-3">
                            <button type="button" @click="quantity = Math.max({{ $product->min_order }}, quantity - 1)"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <input type="number" name="quantity" id="quantity" x-model.number="quantity"
                                min="{{ $product->min_order }}" max="{{ $product->quantity_available }}"
                                class="w-24 text-center px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <button type="button" @click="quantity = Math.min({{ $product->quantity_available }}, quantity + 1)"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
                            </button>
                            <span class="text-sm text-gray-500">{{ Str::plural($product->unit) }}</span>
                        </div>
                    </div>

                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="2" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Enter your delivery address">{{ Auth::user()->address }}</textarea>
                    </div>

                    <div>
                        <label for="buyer_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                        <textarea name="buyer_notes" id="buyer_notes" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Any special instructions..."></textarea>
                    </div>

                    {{-- Subtotal Preview --}}
                    <div class="bg-emerald-50 rounded-lg p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-lg font-bold text-emerald-700" x-text="'$' + (quantity * price).toFixed(2)"></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm text-lg">
                        Place Order
                    </button>
                </div>

                @if($errors->any())
                <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </form>
            @else
            <div class="bg-gray-50 rounded-lg p-6 text-center mb-6">
                <p class="text-gray-600 mb-3">Sign in to place an order</p>
                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                    Sign In
                </a>
            </div>
            @endauth

            {{-- Farmer Info Card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Sold by</h3>
                <div class="flex items-center mb-3">
                    <img src="{{ $product->user->avatar ?? '' }}" alt="{{ $product->user->name }}" class="w-12 h-12 rounded-full object-cover mr-3 border-2 border-emerald-100">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $product->user->farm_name ?? $product->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->user->name }}</p>
                    </div>
                </div>

                {{-- Farmer Rating --}}
                <div class="flex items-center mb-3">
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($farmerRating ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-sm text-gray-500">{{ number_format($farmerRating ?? 0, 1) }} ({{ $farmerReviewCount }} {{ Str::plural('review', $farmerReviewCount) }})</span>
                </div>

                @if($product->user->city || $product->user->state)
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ implode(', ', array_filter([$product->user->city, $product->user->state])) }}
                </div>
                @endif

                <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Contact Farmer
                </a>
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Customer Reviews ({{ $product->reviews_count }})</h2>

        @if($product->reviews->count() > 0)
        <div class="space-y-6">
            @foreach($product->reviews as $review)
            <div class="flex space-x-4 {{ !$loop->last ? 'pb-6 border-b border-gray-100' : '' }}">
                <img src="{{ $review->reviewer->avatar ?? '' }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-medium text-gray-900">{{ $review->reviewer->name ?? 'Anonymous' }}</p>
                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
        </div>
        @endif
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4">More from this Farmer</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
            <a href="{{ route('buyer.marketplace.show', $related->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group block">
                @if($related->images && count($related->images) > 0)
                    <img src="{{ asset('storage/' . $related->images[0]) }}" alt="{{ $related->name }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 flex items-center justify-center bg-emerald-50">
                        <svg class="w-10 h-10 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <div class="p-3">
                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $related->name }}</h3>
                    <p class="text-sm font-bold text-emerald-600 mt-1">${{ number_format($related->price, 2) }}/{{ $related->unit }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
