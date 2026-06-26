<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome') - QuailConnect</title>
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
<body class="h-full bg-[#111827] font-sans antialiased">

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center space-x-2">
                <svg class="w-10 h-10 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span class="text-2xl font-bold text-[#d4a853] tracking-tight">QuailConnect</span>
            </a>
            <h2 class="mt-4 text-xl font-semibold text-white">@yield('heading', 'Welcome back')</h2>
            <p class="mt-1 text-sm text-[#9ca3af]">@yield('subheading', 'Sign in to your account')</p>
        </div>

        {{-- Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-[#1e293b] py-8 px-6 shadow-xl shadow-black/20 border border-[#374151] sm:rounded-xl sm:px-10">

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-6 bg-[#ef4444]/10 border border-[#ef4444]/30 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-[#ef4444] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-[#ef4444]">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-400 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 bg-[#10b981]/10 border border-[#10b981]/30 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-[#10b981] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-[#10b981]">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-[#ef4444]/10 border border-[#ef4444]/30 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-[#ef4444] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-[#ef4444]">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center">
            <p class="text-xs text-[#9ca3af]/60">&copy; {{ date('Y') }} QuailConnect. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
