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
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        earth: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
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
<body class="bg-white font-sans antialiased" x-data="{ mobileMenu: false }">

    {{-- Navigation --}}
    <nav class="bg-white/95 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <span class="text-2xl">🥚</span>
                        <span class="text-xl font-bold text-primary-600 tracking-tight">Quail<span class="text-earth-700">Connect</span></span>
                    </a>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex md:items-center md:space-x-2">
                    <a href="#features" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-colors">Features</a>
                    <a href="#how-it-works" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-colors">How It Works</a>
                    @auth
                        @php
                            $dashUrl = auth()->user()->isAdmin() ? '/admin/dashboard' : (auth()->user()->isFarmer() ? '/farmer/dashboard' : '/buyer/dashboard');
                        @endphp
                        <a href="{{ url($dashUrl) }}" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ url('/login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Sign In</a>
                        <a href="{{ url('/register') }}" class="ml-1 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">Get Started</a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1">
            <a href="#features" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">Features</a>
            <a href="#how-it-works" @click="mobileMenu = false" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">How It Works</a>
            @auth
                @php
                    $dashUrl = auth()->user()->isAdmin() ? '/admin/dashboard' : (auth()->user()->isFarmer() ? '/farmer/dashboard' : '/buyer/dashboard');
                @endphp
                <a href="{{ url($dashUrl) }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white bg-primary-600 text-center mt-2">Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">Sign In</a>
                <a href="{{ url('/register') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white bg-primary-600 text-center mt-2">Get Started</a>
            @endauth
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-earth-50"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 bg-earth-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-primary-100 text-primary-700 text-sm font-medium mb-6">
                    <span class="mr-1.5">🌿</span> The marketplace for quail farmers
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 tracking-tight leading-tight">
                    Fresh Quail Eggs,<br>
                    <span class="text-primary-600">Direct from Farm</span>
                    <span class="text-earth-700"> to Table</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    QuailConnect bridges the gap between local quail farmers and restaurants, stores, and buyers. Get the freshest eggs at fair prices with reliable transport.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/register?role=farmer') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-600/25 transition-all hover:shadow-xl hover:shadow-primary-600/30 hover:-translate-y-0.5">
                        <span class="mr-2">🌾</span> Join as Farmer
                    </a>
                    <a href="{{ url('/register?role=buyer') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-earth-800 bg-white hover:bg-earth-50 border-2 border-earth-200 rounded-xl transition-all hover:-translate-y-0.5">
                        <span class="mr-2">🏪</span> Browse as Buyer
                    </a>
                </div>

                {{-- Trust indicators --}}
                <div class="mt-14 flex flex-wrap items-center justify-center gap-8 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Verified Farms</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Same-Day Delivery</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Fair Pricing</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 sm:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Why Choose QuailConnect?</h2>
                <p class="mt-4 text-lg text-gray-600">Everything you need to buy and sell quality quail products, all in one platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1: Fresh Eggs --}}
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg hover:border-primary-200 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-primary-200 transition-colors">
                        <span class="text-2xl">🥚</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Farm-Fresh Eggs</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Source eggs directly from local quail farms. Every product is fresh, with full traceability from farm to your kitchen.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Quality-verified listings
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Farm origin tracking
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Freshness guaranteed
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Fair Prices --}}
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg hover:border-earth-200 transition-all duration-300">
                    <div class="w-14 h-14 bg-earth-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-earth-200 transition-colors">
                        <span class="text-2xl">💰</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Fair Prices</h3>
                    <p class="text-gray-600 leading-relaxed">
                        No middlemen means better margins for farmers and lower costs for buyers. Set your own prices with transparent fee structure.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-earth-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Direct farmer-to-buyer
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-earth-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Transparent pricing
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-earth-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bulk order discounts
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Easy Transport --}}
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg hover:border-primary-200 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-primary-200 transition-colors">
                        <span class="text-2xl">🚛</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Easy Transport</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Coordinate pickup and delivery seamlessly. Farmers set their transport options, buyers choose what works best.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Flexible delivery options
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Real-time order tracking
                        </li>
                        <li class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Temperature-safe packaging
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 sm:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">How It Works</h2>
                <p class="mt-4 text-lg text-gray-600">Get started in three simple steps, whether you're a farmer or a buyer.</p>
            </div>

            {{-- Steps --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-primary-600/25">1</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Create Your Account</h3>
                    <p class="mt-2 text-gray-600">Sign up as a farmer or buyer. Set up your profile with your farm details or business information.</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-earth-700 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-earth-700/25">2</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">List or Browse Products</h3>
                    <p class="mt-2 text-gray-600">Farmers list their quail eggs and products. Buyers browse available stock, compare prices, and check reviews.</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="relative inline-flex">
                        <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-primary-600/25">3</div>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-gray-900">Order & Deliver</h3>
                    <p class="mt-2 text-gray-600">Place orders directly with farmers. Choose your preferred delivery option and receive farm-fresh products at your door.</p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="mt-16 text-center">
                <a href="{{ url('/register') }}" class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-600/25 transition-all hover:shadow-xl hover:shadow-primary-600/30 hover:-translate-y-0.5">
                    Get Started Today
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-16 bg-primary-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white">500+</p>
                    <p class="mt-1 text-sm text-primary-200">Registered Farms</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white">1,200+</p>
                    <p class="mt-1 text-sm text-primary-200">Active Buyers</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white">50K+</p>
                    <p class="mt-1 text-sm text-primary-200">Eggs Sold Monthly</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white">4.8</p>
                    <p class="mt-1 text-sm text-primary-200">Average Rating</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-2xl">🥚</span>
                        <span class="text-xl font-bold">Quail<span class="text-earth-400">Connect</span></span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-md leading-relaxed">
                        QuailConnect is the trusted marketplace connecting quail farmers directly with restaurants, stores, and individual buyers. Fresh eggs, fair prices, reliable delivery.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Platform</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/register?role=farmer') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Sell as Farmer</a></li>
                        <li><a href="{{ url('/register?role=buyer') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Buy Products</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-8 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} QuailConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
