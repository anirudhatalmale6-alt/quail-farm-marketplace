<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuailConnect - Fresh Quail Eggs, Direct from Farm to Table</title>
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
<body class="bg-[#111827] font-sans antialiased text-white" x-data="{ mobileMenu: false }">

    {{-- Navigation --}}
    <nav class="bg-[#0f1419]/95 backdrop-blur-sm border-b border-[#374151] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-xl font-bold text-[#d4a853] tracking-tight">QuailConnect</span>
                    </a>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex md:items-center md:space-x-2">
                    <a href="#features" class="px-3 py-2 text-sm font-medium text-[#9ca3af] hover:text-[#d4a853] rounded-lg transition-colors">Features</a>
                    <a href="#how-it-works" class="px-3 py-2 text-sm font-medium text-[#9ca3af] hover:text-[#d4a853] rounded-lg transition-colors">How It Works</a>
                    <a href="#listings" class="px-3 py-2 text-sm font-medium text-[#9ca3af] hover:text-[#d4a853] rounded-lg transition-colors">Listings</a>
                    @auth
                        @php
                            $dashUrl = auth()->user()->isAdmin() ? '/admin/dashboard' : (auth()->user()->isFarmer() ? '/farmer/dashboard' : '/buyer/dashboard');
                        @endphp
                        <a href="{{ url($dashUrl) }}" class="ml-2 px-5 py-2 text-sm font-semibold text-[#0f1419] bg-[#d4a853] hover:bg-[#f59e0b] rounded-lg transition-colors shadow-lg shadow-[#d4a853]/20">Dashboard</a>
                    @else
                        <a href="{{ url('/login') }}" class="px-4 py-2 text-sm font-medium text-[#9ca3af] hover:text-[#d4a853] transition-colors">Sign In</a>
                        <a href="{{ url('/register') }}" class="ml-1 px-5 py-2 text-sm font-semibold text-[#0f1419] bg-[#d4a853] hover:bg-[#f59e0b] rounded-lg transition-colors shadow-lg shadow-[#d4a853]/20">Get Started</a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg text-[#9ca3af] hover:text-white transition-colors">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden border-t border-[#374151] bg-[#0f1419] px-4 py-3 space-y-1">
            <a href="#features" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Features</a>
            <a href="#how-it-works" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">How It Works</a>
            <a href="#listings" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Listings</a>
            @auth
                @php
                    $dashUrl = auth()->user()->isAdmin() ? '/admin/dashboard' : (auth()->user()->isFarmer() ? '/farmer/dashboard' : '/buyer/dashboard');
                @endphp
                <a href="{{ url($dashUrl) }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-[#0f1419] bg-[#d4a853] text-center mt-2">Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Sign In</a>
                <a href="{{ url('/register') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-[#0f1419] bg-[#d4a853] text-center mt-2">Get Started</a>
            @endauth
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        {{-- Dark gradient background with subtle glow --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f1419] via-[#111827] to-[#1e293b]"></div>
        <div class="absolute top-20 left-10 w-96 h-96 bg-[#d4a853]/5 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-[#d4a853]/3 rounded-full filter blur-3xl"></div>
        {{-- Subtle grid pattern overlay --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
                {{-- Left: Hero text --}}
                <div class="lg:col-span-3">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#d4a853]/10 border border-[#d4a853]/20 text-[#d4a853] text-sm font-medium mb-6">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        The marketplace for quail farmers
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight">
                        Fresh Quail Eggs,<br>
                        <span class="text-[#d4a853]">Direct from Farm</span><br>
                        <span class="text-[#9ca3af]">to Table</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-[#9ca3af] max-w-2xl leading-relaxed">
                        QuailConnect bridges the gap between local quail farmers and restaurants, stores, and buyers. Get the freshest eggs at fair prices with reliable transport.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row items-start gap-4">
                        <a href="{{ url('/register?role=farmer') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-[#0f1419] bg-[#d4a853] hover:bg-[#f59e0b] rounded-xl shadow-lg shadow-[#d4a853]/20 transition-all hover:shadow-xl hover:shadow-[#d4a853]/30 hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Join as Farmer
                        </a>
                        <a href="{{ url('/register?role=buyer') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-[#d4a853] bg-transparent hover:bg-[#d4a853]/10 border-2 border-[#d4a853]/40 hover:border-[#d4a853] rounded-xl transition-all hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Browse as Buyer
                        </a>
                    </div>

                    {{-- Trust indicators --}}
                    <div class="mt-14 flex flex-wrap items-center gap-8 text-sm text-[#9ca3af]">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Verified Farms</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Same-Day Delivery</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Fair Pricing</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Market Snapshot Widget (Mikrobid-style sidebar) --}}
                <div class="lg:col-span-2">
                    <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden shadow-xl shadow-black/30">
                        {{-- Widget header --}}
                        <div class="px-6 py-4 border-b border-[#374151] flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-white flex items-center">
                                <svg class="w-4 h-4 text-[#d4a853] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                                Market Snapshot
                            </h3>
                            <span class="text-xs text-[#10b981] flex items-center">
                                <span class="w-1.5 h-1.5 bg-[#10b981] rounded-full mr-1 animate-pulse"></span>
                                Live
                            </span>
                        </div>
                        {{-- Stats rows --}}
                        <div class="divide-y divide-[#374151]">
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-[#9ca3af]">Active Listings</span>
                                <span class="text-sm font-semibold text-white">347</span>
                            </div>
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-[#9ca3af]">Eggs Sold Today</span>
                                <span class="text-sm font-semibold text-[#10b981]">2,480</span>
                            </div>
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-[#9ca3af]">Avg. Price / Dozen</span>
                                <span class="text-sm font-semibold text-[#d4a853]">$4.50</span>
                            </div>
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-[#9ca3af]">New Farms This Week</span>
                                <span class="text-sm font-semibold text-white">+12</span>
                            </div>
                            <div class="px-6 py-3 flex items-center justify-between">
                                <span class="text-sm text-[#9ca3af]">Pending Orders</span>
                                <span class="text-sm font-semibold text-[#f59e0b]">89</span>
                            </div>
                        </div>
                        {{-- Widget footer / trending --}}
                        <div class="px-6 py-4 bg-[#0f1419]/50 border-t border-[#374151]">
                            <p class="text-xs text-[#9ca3af] mb-3 uppercase tracking-wider font-semibold">Trending Categories</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 text-xs font-medium bg-[#d4a853]/10 text-[#d4a853] border border-[#d4a853]/20 rounded-full">Fertilized Eggs</span>
                                <span class="px-3 py-1 text-xs font-medium bg-[#10b981]/10 text-[#10b981] border border-[#10b981]/20 rounded-full">Organic</span>
                                <span class="px-3 py-1 text-xs font-medium bg-[#60a5fa]/10 text-[#60a5fa] border border-[#60a5fa]/20 rounded-full">Bulk Orders</span>
                                <span class="px-3 py-1 text-xs font-medium bg-[#a78bfa]/10 text-[#a78bfa] border border-[#a78bfa]/20 rounded-full">Live Birds</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 sm:py-28 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white">Why Choose <span class="text-[#d4a853]">QuailConnect</span>?</h2>
                <p class="mt-4 text-lg text-[#9ca3af]">Everything you need to buy and sell quality quail products, all in one platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1: Fresh Eggs --}}
                <div class="group relative bg-[#1e293b] border border-[#374151] rounded-2xl p-8 hover:border-[#d4a853]/40 transition-all duration-300 hover:shadow-lg hover:shadow-[#d4a853]/5">
                    <div class="w-14 h-14 bg-[#d4a853]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#d4a853]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Farm-Fresh Eggs</h3>
                    <p class="text-[#9ca3af] leading-relaxed">
                        Source eggs directly from local quail farms. Every product is fresh, with full traceability from farm to your kitchen.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Quality-verified listings
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Farm origin tracking
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Freshness guaranteed
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Fair Prices --}}
                <div class="group relative bg-[#1e293b] border border-[#374151] rounded-2xl p-8 hover:border-[#d4a853]/40 transition-all duration-300 hover:shadow-lg hover:shadow-[#d4a853]/5">
                    <div class="w-14 h-14 bg-[#d4a853]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#d4a853]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Fair Prices</h3>
                    <p class="text-[#9ca3af] leading-relaxed">
                        No middlemen means better margins for farmers and lower costs for buyers. Set your own prices with transparent fee structure.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Direct farmer-to-buyer
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Transparent pricing
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bulk order discounts
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Easy Transport --}}
                <div class="group relative bg-[#1e293b] border border-[#374151] rounded-2xl p-8 hover:border-[#d4a853]/40 transition-all duration-300 hover:shadow-lg hover:shadow-[#d4a853]/5">
                    <div class="w-14 h-14 bg-[#d4a853]/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#d4a853]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12l2 5-2 1H8m0-6v6m0-6H5l-3 6h6m4 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Easy Transport</h3>
                    <p class="text-[#9ca3af] leading-relaxed">
                        Coordinate pickup and delivery seamlessly. Farmers set their transport options, buyers choose what works best.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Flexible delivery options
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Real-time order tracking
                        </li>
                        <li class="flex items-center text-sm text-[#9ca3af]">
                            <svg class="w-4 h-4 text-[#d4a853] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Temperature-safe packaging
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 sm:py-28 bg-[#0f1419]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white">How It <span class="text-[#d4a853]">Works</span></h2>
                <p class="mt-4 text-lg text-[#9ca3af]">Get started in three simple steps, whether you're a farmer or a buyer.</p>
            </div>

            {{-- Steps --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-[#d4a853] rounded-2xl flex items-center justify-center text-[#0f1419] text-xl font-bold shadow-lg shadow-[#d4a853]/25">1</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-white">Create Your Account</h3>
                    <p class="mt-2 text-[#9ca3af]">Sign up as a farmer or buyer. Set up your profile with your farm details or business information.</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-[#d4a853] rounded-2xl flex items-center justify-center text-[#0f1419] text-xl font-bold shadow-lg shadow-[#d4a853]/25">2</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-white">List or Browse Products</h3>
                    <p class="mt-2 text-[#9ca3af]">Farmers list their quail eggs and products. Buyers browse available stock, compare prices, and check reviews.</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-[#d4a853] rounded-2xl flex items-center justify-center text-[#0f1419] text-xl font-bold shadow-lg shadow-[#d4a853]/25">3</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-white">Order & Deliver</h3>
                    <p class="mt-2 text-[#9ca3af]">Place orders directly with farmers. Choose your preferred delivery option and receive farm-fresh products at your door.</p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="mt-16 text-center">
                <a href="{{ url('/register') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-[#0f1419] bg-[#d4a853] hover:bg-[#f59e0b] rounded-xl shadow-lg shadow-[#d4a853]/20 transition-all hover:shadow-xl hover:shadow-[#d4a853]/30 hover:-translate-y-0.5">
                    Get Started Today
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Category Filter Section --}}
    <section class="py-12 bg-[#111827] border-y border-[#374151]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Browse by Category</h3>
                <a href="#" class="text-sm text-[#d4a853] hover:text-[#f59e0b] transition-colors">View All</a>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#d4a853] text-[#0f1419] rounded-full transition-colors hover:bg-[#f59e0b]">All Products</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Fresh Eggs</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Fertilized Eggs</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Live Birds</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Quail Meat</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Organic</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Bulk Orders</a>
                <a href="#" class="px-5 py-2.5 text-sm font-medium bg-[#1e293b] text-[#9ca3af] border border-[#374151] rounded-full hover:border-[#d4a853] hover:text-[#d4a853] transition-colors">Equipment</a>
            </div>
        </div>
    </section>

    {{-- Featured Listings Section --}}
    <section id="listings" class="py-20 sm:py-28 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white">Featured <span class="text-[#d4a853]">Listings</span></h2>
                    <p class="mt-3 text-lg text-[#9ca3af]">Fresh picks from verified farms across the marketplace.</p>
                </div>
                @auth
                    <a href="{{ url('/buyer/marketplace') }}" class="hidden sm:inline-flex items-center text-sm font-medium text-[#d4a853] hover:text-[#f59e0b] transition-colors">
                        View All Listings
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Sample Product Card 1 --}}
                <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden hover:border-[#d4a853]/40 transition-all duration-300 group">
                    <div class="aspect-[4/3] bg-[#0f1419] flex items-center justify-center">
                        <svg class="w-16 h-16 text-[#374151] group-hover:text-[#d4a853]/30 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-[#10b981] bg-[#10b981]/10 px-2 py-0.5 rounded-full">In Stock</span>
                            <span class="text-xs text-[#9ca3af]">Farm Fresh</span>
                        </div>
                        <h4 class="font-semibold text-white mb-1">Organic Quail Eggs (30pk)</h4>
                        <p class="text-sm text-[#9ca3af] mb-3">Free-range, no antibiotics</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-[#d4a853]">$12.99</span>
                            <span class="text-xs text-[#9ca3af]">per pack</span>
                        </div>
                    </div>
                </div>

                {{-- Sample Product Card 2 --}}
                <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden hover:border-[#d4a853]/40 transition-all duration-300 group">
                    <div class="aspect-[4/3] bg-[#0f1419] flex items-center justify-center">
                        <svg class="w-16 h-16 text-[#374151] group-hover:text-[#d4a853]/30 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-[#10b981] bg-[#10b981]/10 px-2 py-0.5 rounded-full">In Stock</span>
                            <span class="text-xs text-[#9ca3af]">Bulk Available</span>
                        </div>
                        <h4 class="font-semibold text-white mb-1">Fertilized Hatching Eggs (12)</h4>
                        <p class="text-sm text-[#9ca3af] mb-3">Coturnix japonica breed</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-[#d4a853]">$18.50</span>
                            <span class="text-xs text-[#9ca3af]">per dozen</span>
                        </div>
                    </div>
                </div>

                {{-- Sample Product Card 3 --}}
                <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden hover:border-[#d4a853]/40 transition-all duration-300 group">
                    <div class="aspect-[4/3] bg-[#0f1419] flex items-center justify-center">
                        <svg class="w-16 h-16 text-[#374151] group-hover:text-[#d4a853]/30 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-[#f59e0b] bg-[#f59e0b]/10 px-2 py-0.5 rounded-full">Limited</span>
                            <span class="text-xs text-[#9ca3af]">Premium</span>
                        </div>
                        <h4 class="font-semibold text-white mb-1">Live Quail Chicks (10)</h4>
                        <p class="text-sm text-[#9ca3af] mb-3">1-week old, vaccinated</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-[#d4a853]">$35.00</span>
                            <span class="text-xs text-[#9ca3af]">per batch</span>
                        </div>
                    </div>
                </div>

                {{-- Sample Product Card 4 --}}
                <div class="bg-[#1e293b] border border-[#374151] rounded-2xl overflow-hidden hover:border-[#d4a853]/40 transition-all duration-300 group">
                    <div class="aspect-[4/3] bg-[#0f1419] flex items-center justify-center">
                        <svg class="w-16 h-16 text-[#374151] group-hover:text-[#d4a853]/30 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-[#10b981] bg-[#10b981]/10 px-2 py-0.5 rounded-full">In Stock</span>
                            <span class="text-xs text-[#9ca3af]">Best Seller</span>
                        </div>
                        <h4 class="font-semibold text-white mb-1">Quail Egg Tray (100pk)</h4>
                        <p class="text-sm text-[#9ca3af] mb-3">Restaurant bulk supply</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-[#d4a853]">$38.99</span>
                            <span class="text-xs text-[#9ca3af]">per tray</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-16 bg-[#0f1419] border-y border-[#374151]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-[#d4a853]">500+</p>
                    <p class="mt-1 text-sm text-[#9ca3af]">Registered Farms</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-[#d4a853]">1,200+</p>
                    <p class="mt-1 text-sm text-[#9ca3af]">Active Buyers</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-[#d4a853]">50K+</p>
                    <p class="mt-1 text-sm text-[#9ca3af]">Eggs Sold Monthly</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-[#d4a853]">4.8</p>
                    <p class="mt-1 text-sm text-[#9ca3af]">Average Rating</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#0f1419]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-7 h-7 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-xl font-bold text-[#d4a853]">QuailConnect</span>
                    </div>
                    <p class="text-[#9ca3af] text-sm max-w-md leading-relaxed">
                        QuailConnect is the trusted marketplace connecting quail farmers directly with restaurants, stores, and individual buyers. Fresh eggs, fair prices, reliable delivery.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-[#d4a853] mb-4">Platform</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/register?role=farmer') }}" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Sell as Farmer</a></li>
                        <li><a href="{{ url('/register?role=buyer') }}" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Buy Products</a></li>
                        <li><a href="#" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-[#d4a853] mb-4">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">About Us</a></li>
                        <li><a href="#" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#374151] mt-10 pt-8 text-center">
                <p class="text-sm text-[#9ca3af]/60">&copy; {{ date('Y') }} QuailConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
