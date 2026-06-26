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
                        emerald: {
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                        },
                        earth: {
                            50: '#fdf8f1',
                            100: '#f5ead6',
                            200: '#e8d5b0',
                            300: '#d4b896',
                            400: '#c49a6c',
                            500: '#a67c52',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-xl font-bold text-emerald-600">QuailConnect</span>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Dashboard</a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Users</a>
                            <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Orders</a>
                            <a href="{{ route('admin.commissions.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.commissions.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Commissions</a>
                            <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Categories</a>
                        @elseif(auth()->user()->isFarmer())
                            <a href="{{ route('farmer.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.dashboard') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Dashboard</a>
                            <a href="{{ route('farmer.products.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.products.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">My Products</a>
                            <a href="{{ route('farmer.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.orders.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Orders</a>
                        @elseif(auth()->user()->isBuyer())
                            <a href="{{ route('buyer.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.dashboard') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Dashboard</a>
                            <a href="{{ route('buyer.marketplace.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.marketplace.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Marketplace</a>
                            <a href="{{ route('buyer.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('buyer.orders.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">My Orders</a>
                        @endif
                        <a href="{{ route('messages.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('messages.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-600 hover:text-emerald-600' }}">Messages</a>
                    @endauth
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none">
                                <img src="{{ auth()->user()->avatar }}" alt="" class="w-8 h-8 rounded-full border-2 border-emerald-600">
                                <span class="hidden md:block text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                                <div class="px-4 py-3 border-b">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-emerald-600">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">Register</a>
                    @endauth

                    <!-- Mobile menu button -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" x-cloak class="md:hidden border-t bg-white">
            <div class="px-4 py-3 space-y-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Users</a>
                        <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Orders</a>
                        <a href="{{ route('admin.commissions.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Commissions</a>
                        <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Categories</a>
                    @elseif(auth()->user()->isFarmer())
                        <a href="{{ route('farmer.dashboard') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Dashboard</a>
                        <a href="{{ route('farmer.products.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">My Products</a>
                        <a href="{{ route('farmer.orders.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Orders</a>
                    @elseif(auth()->user()->isBuyer())
                        <a href="{{ route('buyer.dashboard') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Dashboard</a>
                        <a href="{{ route('buyer.marketplace.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Marketplace</a>
                        <a href="{{ route('buyer.orders.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">My Orders</a>
                    @endif
                    <a href="{{ route('messages.index') }}" class="block px-3 py-2 rounded text-sm text-gray-700 hover:bg-emerald-50">Messages</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
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
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} QuailConnect Marketplace. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
