<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QuailConnect Marketplace')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            400: '#d4a853',
                            500: '#f59e0b',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#111827] min-h-screen flex flex-col text-white">
    <!-- Navigation -->
    <nav class="bg-[#0f1419] border-b border-[#374151] shadow-lg shadow-black/20" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-xl font-bold text-[#d4a853]">QuailConnect</span>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Dashboard</a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Users</a>
                            <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Orders</a>
                            <a href="{{ route('admin.commissions.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.commissions.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Commissions</a>
                            <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Categories</a>
                            <a href="{{ route('admin.credits.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.credits.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Credits</a>
                            <a href="{{ route('admin.investments.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.investments.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Investments</a>
                        @elseif(auth()->user()->isFarmer())
                            <a href="{{ route('farmer.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.dashboard') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Dashboard</a>
                            <a href="{{ route('farmer.products.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.products.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">My Products</a>
                            <a href="{{ route('farmer.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.orders.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Orders</a>
                            @if(auth()->user()->isPro() || auth()->user()->canAccessInvestments())
                                <a href="{{ route('farmer.investments.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.investments.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Investments</a>
                            @endif
                        @elseif(auth()->user()->isBuyer())
                            <a href="{{ route('buyer.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.dashboard') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Dashboard</a>
                            <a href="{{ route('buyer.marketplace.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.marketplace.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Marketplace</a>
                            <a href="{{ route('buyer.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.orders.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">My Orders</a>
                            <a href="{{ route('buyer.credit.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.credit.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Credit</a>
                        @endif
                        <a href="{{ route('messages.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('messages.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Messages</a>
                        <a href="{{ route('feed.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('feed.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Feed</a>
                        <a href="{{ route('streams.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('streams.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Streams</a>
                    @endauth
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Global Live Search -->
                    <div x-data="globalSearch()" @keydown.escape.window="close()" @keydown.ctrl.k.window.prevent="open()" @keydown.meta.k.window.prevent="open()">
                        <button @click="open()" class="p-2 text-[#9ca3af] hover:text-[#d4a853] transition-colors" title="Search (Ctrl+K)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>

                        <!-- Search Overlay -->
                        <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm" @click.self="close()">
                            <div class="max-w-2xl mx-auto mt-20 px-4" @click.stop>
                                <!-- Search Input -->
                                <div class="relative bg-[#1e293b] border border-[#374151] rounded-2xl shadow-2xl shadow-black/50 overflow-hidden">
                                    <div class="flex items-center px-5 border-b border-[#374151]">
                                        <svg class="w-5 h-5 text-[#d4a853] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input x-ref="searchInput" x-model="query" @input.debounce.250ms="doSearch()"
                                            type="text" placeholder="Search products, farmers, feed, streams, supplies..."
                                            class="flex-1 px-4 py-4 bg-transparent text-white text-lg placeholder-[#6b7280] focus:outline-none border-none">
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <span x-show="loading" class="flex items-center">
                                                <svg class="animate-spin w-5 h-5 text-[#d4a853]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            </span>
                                            <kbd class="hidden sm:inline-flex px-2 py-1 text-[10px] font-mono text-[#6b7280] bg-[#0f1419] border border-[#374151] rounded">ESC</kbd>
                                        </div>
                                    </div>

                                    <!-- Results -->
                                    <div class="max-h-[60vh] overflow-y-auto" x-show="query.length >= 2">
                                        <!-- Result Count -->
                                        <div x-show="!loading && results.length > 0" class="px-5 py-2 border-b border-[#374151]">
                                            <span class="text-xs text-[#6b7280]"><span class="text-[#d4a853]" x-text="totalCount"></span> results found</span>
                                        </div>

                                        <!-- Result Items -->
                                        <template x-for="(result, index) in results" :key="result.type + '-' + result.id">
                                            <a :href="result.url" class="flex items-center gap-4 px-5 py-3 hover:bg-[#374151]/50 transition-colors cursor-pointer border-b border-[#374151]/50 last:border-0"
                                                :class="{ 'bg-[#374151]/30': selectedIndex === index }"
                                                @mouseenter="selectedIndex = index">
                                                <!-- Type Icon -->
                                                <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center"
                                                    :class="{
                                                        'bg-[#10b981]/10 text-[#10b981]': result.type === 'product',
                                                        'bg-[#3b82f6]/10 text-[#3b82f6]': result.type === 'farmer',
                                                        'bg-[#8b5cf6]/10 text-[#8b5cf6]': result.type === 'feed',
                                                        'bg-[#ef4444]/10 text-[#ef4444]': result.type === 'stream',
                                                        'bg-[#d4a853]/10 text-[#d4a853]': result.type === 'category',
                                                        'bg-[#f97316]/10 text-[#f97316]': result.type === 'supply',
                                                    }">
                                                    <svg x-show="result.type === 'product'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                    <svg x-show="result.type === 'farmer'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    <svg x-show="result.type === 'feed'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                                    <svg x-show="result.type === 'stream'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    <svg x-show="result.type === 'category'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                                    <svg x-show="result.type === 'supply'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-white text-sm font-medium truncate" x-text="result.title"></span>
                                                        <span x-show="result.category" class="flex-shrink-0 text-[9px] px-1.5 py-0.5 rounded-full bg-[#374151] text-[#9ca3af]" x-text="result.category"></span>
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="text-xs text-[#d4a853]" x-text="result.subtitle"></span>
                                                        <span x-show="result.meta" class="text-xs text-[#4b5563]" x-text="result.meta"></span>
                                                    </div>
                                                </div>

                                                <!-- Type Badge -->
                                                <span class="flex-shrink-0 text-[9px] font-bold uppercase px-2 py-1 rounded"
                                                    :class="{
                                                        'bg-[#10b981]/10 text-[#10b981]': result.type === 'product',
                                                        'bg-[#3b82f6]/10 text-[#3b82f6]': result.type === 'farmer',
                                                        'bg-[#8b5cf6]/10 text-[#8b5cf6]': result.type === 'feed',
                                                        'bg-[#ef4444]/10 text-[#ef4444]': result.type === 'stream',
                                                        'bg-[#d4a853]/10 text-[#d4a853]': result.type === 'category',
                                                        'bg-[#f97316]/10 text-[#f97316]': result.type === 'supply',
                                                    }" x-text="result.type"></span>
                                            </a>
                                        </template>

                                        <!-- No Results -->
                                        <div x-show="!loading && results.length === 0 && query.length >= 2" class="px-5 py-8 text-center">
                                            <svg class="w-10 h-10 mx-auto text-[#374151] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                            <p class="text-[#6b7280] text-sm">No results for "<span class="text-white" x-text="query"></span>"</p>
                                        </div>

                                        <!-- View All Link -->
                                        <a x-show="!loading && results.length > 0" :href="'/search?q=' + encodeURIComponent(query)"
                                            class="block px-5 py-3 text-center text-sm text-[#d4a853] hover:bg-[#374151]/30 transition-colors border-t border-[#374151]">
                                            View all <span x-text="totalCount"></span> results
                                        </a>
                                    </div>

                                    <!-- Hints (when empty) -->
                                    <div x-show="query.length < 2" class="px-5 py-6">
                                        <p class="text-xs text-[#6b7280] mb-3">SEARCH ACROSS</p>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#10b981]"></span> Products
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#3b82f6]"></span> Farmers
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#8b5cf6]"></span> Feed Posts
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#ef4444]"></span> Streams
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#d4a853]"></span> Categories
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-[#9ca3af]">
                                                <span class="w-2 h-2 rounded-full bg-[#f97316]"></span> Supplies
                                            </div>
                                        </div>
                                        <p class="text-xs text-[#4b5563] mt-4">Tip: Press <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-[#0f1419] border border-[#374151] rounded">Ctrl+K</kbd> anytime to search</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @auth
                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ notifCount: 0 }" x-init="fetch('{{ route('notifications.unreadCount') }}').then(r => r.json()).then(d => notifCount = d.count)">
                            <a href="{{ route('notifications.index') }}" class="relative p-2 text-[#9ca3af] hover:text-[#d4a853] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span x-show="notifCount > 0" x-cloak class="absolute -top-0.5 -right-0.5 bg-[#ef4444] text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center" x-text="notifCount > 9 ? '9+' : notifCount"></span>
                            </a>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none">
                                <img src="{{ auth()->user()->avatar }}" alt="" class="w-8 h-8 rounded-full border-2 border-[#d4a853]">
                                <span class="hidden md:block text-[#9ca3af]">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->isPro())
                                    <span class="hidden md:inline-flex px-1.5 py-0.5 text-[10px] font-bold bg-[#d4a853] text-[#0f1419] rounded uppercase">PRO</span>
                                @else
                                    <span class="hidden md:inline-flex px-1.5 py-0.5 text-[10px] font-bold bg-[#374151] text-[#9ca3af] rounded uppercase">FREE</span>
                                @endif
                                <svg class="w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-[#1e293b] rounded-lg shadow-xl shadow-black/30 border border-[#374151] z-50">
                                <div class="px-4 py-3 border-b border-[#374151]">
                                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-[#9ca3af] capitalize">{{ auth()->user()->role }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-[#9ca3af] hover:bg-[#374151] hover:text-white rounded-b-lg transition-colors">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-[#d4a853] text-[#0f1419] font-semibold px-4 py-2 rounded-lg hover:bg-[#f59e0b] transition-colors">Register</a>
                    @endauth

                    <!-- Mobile menu button -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-md text-[#9ca3af] hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-[#374151] bg-[#0f1419]">
            <div class="px-4 py-3 space-y-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Users</a>
                        <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Orders</a>
                        <a href="{{ route('admin.commissions.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Commissions</a>
                        <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Categories</a>
                        <a href="{{ route('admin.credits.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Credits</a>
                        <a href="{{ route('admin.investments.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Investments</a>
                    @elseif(auth()->user()->isFarmer())
                        <a href="{{ route('farmer.dashboard') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Dashboard</a>
                        <a href="{{ route('farmer.products.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">My Products</a>
                        <a href="{{ route('farmer.orders.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Orders</a>
                        @if(auth()->user()->isPro() || auth()->user()->canAccessInvestments())
                            <a href="{{ route('farmer.investments.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Investments</a>
                        @endif
                    @elseif(auth()->user()->isBuyer())
                        <a href="{{ route('buyer.dashboard') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Dashboard</a>
                        <a href="{{ route('buyer.marketplace.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Marketplace</a>
                        <a href="{{ route('buyer.orders.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">My Orders</a>
                        <a href="{{ route('buyer.credit.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Credit</a>
                    @endif
                    <a href="{{ route('messages.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Messages</a>
                    <a href="{{ route('feed.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Feed</a>
                    <a href="{{ route('streams.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Streams</a>
                    <a href="{{ route('notifications.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Notifications</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-[#10b981]/10 border border-[#10b981]/30 text-[#10b981] px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-[#10b981] hover:text-[#34d399]">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-[#ef4444]/10 border border-[#ef4444]/30 text-[#ef4444] px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="text-[#ef4444] hover:text-red-300">&times;</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-[#ef4444]/10 border border-[#ef4444]/30 text-[#ef4444] px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0f1419] border-t border-[#374151] mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-[#9ca3af]">
            &copy; {{ date('Y') }} QuailConnect Marketplace. All rights reserved.
        </div>
    </footer>

    <script>
        function globalSearch() {
            return {
                isOpen: false,
                query: '',
                results: [],
                totalCount: 0,
                loading: false,
                selectedIndex: -1,
                abortController: null,

                open() {
                    this.isOpen = true;
                    this.$nextTick(() => this.$refs.searchInput.focus());
                },

                close() {
                    this.isOpen = false;
                    this.query = '';
                    this.results = [];
                    this.totalCount = 0;
                    this.selectedIndex = -1;
                },

                async doSearch() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.totalCount = 0;
                        return;
                    }

                    if (this.abortController) {
                        this.abortController.abort();
                    }
                    this.abortController = new AbortController();

                    this.loading = true;
                    try {
                        const response = await fetch('/search/live?q=' + encodeURIComponent(this.query), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            signal: this.abortController.signal
                        });
                        const data = await response.json();
                        this.results = data.results;
                        this.totalCount = data.total;
                        this.selectedIndex = -1;
                    } catch (e) {
                        if (e.name !== 'AbortError') {
                            console.error('Search error:', e);
                        }
                    }
                    this.loading = false;
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
