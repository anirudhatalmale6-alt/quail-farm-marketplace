@extends('layouts.app')

@section('title', 'Login - QuailConnect')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
            <p class="mt-2 text-[#9ca3af]">Sign in to your QuailConnect account</p>
        </div>

        <div class="bg-[#1e293b] rounded-xl shadow-lg border border-[#374151] p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-[#9ca3af] mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('email')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#9ca3af] mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('password')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-[#374151] bg-[#111827] text-[#d4a853] focus:ring-[#d4a853]">
                        <span class="ml-2 text-sm text-[#9ca3af]">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-[#d4a853] text-[#0f1419] py-2.5 px-4 rounded-lg font-semibold hover:bg-[#c49a48] focus:ring-4 focus:ring-[#d4a853]/25 transition">
                    Sign In
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#9ca3af]">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#d4a853] hover:text-[#e0b96a] font-medium">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection
