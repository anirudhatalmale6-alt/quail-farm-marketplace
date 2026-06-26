@extends('layouts.app')

@section('title', ($user->farm_name ?? $user->business_name ?? $user->name) . ' - QuailConnect')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Profile Header -->
    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden">
        <!-- Cover Banner -->
        <div class="h-32 sm:h-40 bg-gradient-to-r from-[#d4a853]/20 via-[#0f1419] to-[#d4a853]/10"></div>

        <div class="px-6 pb-6">
            <!-- Avatar + Status -->
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12 sm:-mt-16">
                <div class="relative flex-shrink-0">
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}"
                        class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-[#1e293b] object-cover bg-[#374151]">
                    <!-- Online/Offline Indicator -->
                    <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full border-3 border-[#1e293b] {{ $user->isOnlineNow() ? 'bg-[#10b981]' : 'bg-[#6b7280]' }}"
                        title="{{ $user->isOnlineNow() ? 'Online' : 'Offline' }}"></span>
                </div>

                <div class="flex-1 min-w-0 pb-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->farm_name ?? $user->business_name ?? $user->name }}</h1>
                        @if($user->isPro())
                            <span class="px-2 py-0.5 text-xs font-bold bg-[#d4a853] text-[#0f1419] rounded">PRO</span>
                        @endif
                        <span class="px-2 py-0.5 text-xs font-bold rounded capitalize {{ $user->isOnlineNow() ? 'bg-[#10b981]/20 text-[#10b981]' : 'bg-[#374151] text-[#6b7280]' }}">
                            {{ $user->isOnlineNow() ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    <p class="text-[#d4a853] font-medium">{{ $user->name }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-[#9ca3af]">
                        <span class="capitalize px-2 py-0.5 bg-[#374151] rounded text-xs">{{ $user->role }}</span>
                        @if($user->city || $user->state)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $user->city }}{{ $user->city && $user->state ? ', ' : '' }}{{ $user->state }}
                            </span>
                        @endif
                        @if($user->phone)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $user->phone }}
                            </span>
                        @endif
                        @if($user->website)
                            <a href="{{ $user->website }}" target="_blank" class="flex items-center gap-1 text-[#d4a853] hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Website
                            </a>
                        @endif
                        <span class="text-[#6b7280]">Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pb-1">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg font-semibold text-sm hover:bg-[#f59e0b] transition-colors">
                                Edit Profile
                            </a>
                        @else
                            <a href="{{ route('messages.show', $user->id) }}" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg font-semibold text-sm hover:bg-[#f59e0b] transition-colors">
                                Message
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Bio Section -->
    @if($user->bio)
        <div class="mt-6 bg-[#1e293b] border border-[#374151] rounded-xl p-6">
            <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wide mb-2">About</h3>
            <p class="text-[#9ca3af] leading-relaxed">{{ $user->bio }}</p>
        </div>
    @endif

    <!-- Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-[#d4a853]">{{ $user->products_count ?? 0 }}</p>
            <p class="text-xs text-[#9ca3af]">Products</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-[#10b981]">{{ $user->reviews_count ?? 0 }}</p>
            <p class="text-xs text-[#9ca3af]">Reviews</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white">{{ $user->isOnlineNow() ? 'Now' : ($user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'N/A') }}</p>
            <p class="text-xs text-[#9ca3af]">Last Active</p>
        </div>
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-white capitalize">{{ $user->subscription_plan ?? 'free' }}</p>
            <p class="text-xs text-[#9ca3af]">Plan</p>
        </div>
    </div>

    <!-- Products -->
    @if($products->isNotEmpty())
        <div class="mt-8">
            <h3 class="text-lg font-bold text-white mb-4">Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <a href="{{ route('buyer.marketplace.show', $product->id) }}" class="bg-[#1e293b] border border-[#374151] rounded-xl p-4 hover:border-[#d4a853] transition-all group">
                        <h4 class="text-white font-semibold group-hover:text-[#d4a853] transition-colors">{{ $product->name }}</h4>
                        <p class="text-[#d4a853] font-bold mt-1">${{ number_format($product->price, 2) }} / {{ $product->unit }}</p>
                        <p class="text-sm text-[#6b7280] mt-1">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-[#9ca3af]">{{ $product->quantity_available }} available</span>
                            <span class="text-xs px-2 py-0.5 bg-[#10b981]/10 text-[#10b981] rounded">{{ $product->category->name ?? '' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Reviews -->
    @if($reviews->isNotEmpty())
        <div class="mt-8">
            <h3 class="text-lg font-bold text-white mb-4">Reviews</h3>
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ $review->reviewer->avatar }}" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="text-white text-sm font-medium">{{ $review->reviewer->name }}</p>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-[#d4a853]' : 'text-[#374151]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="ml-auto text-xs text-[#6b7280]">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-[#9ca3af]">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
