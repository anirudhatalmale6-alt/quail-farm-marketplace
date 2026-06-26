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
                        @elseif(auth()->user()->isFarmer())
                            <a href="{{ route('farmer.dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.dashboard') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Dashboard</a>
                            <a href="{{ route('farmer.products.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.products.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">My Products</a>
                            <a href="{{ route('farmer.orders.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('farmer.orders.*') ? 'text-[#d4a853] border-b-2 border-[#d4a853]' : 'text-[#9ca3af] hover:text-[#d4a853]' }}">Orders</a>
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
                    @elseif(auth()->user()->isFarmer())
                        <a href="{{ route('farmer.dashboard') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Dashboard</a>
                        <a href="{{ route('farmer.products.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">My Products</a>
                        <a href="{{ route('farmer.orders.index') }}" class="block px-3 py-2 rounded text-sm text-[#9ca3af] hover:bg-[#1e293b] hover:text-[#d4a853]">Orders</a>
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

    @stack('scripts')
</body>
</html>
