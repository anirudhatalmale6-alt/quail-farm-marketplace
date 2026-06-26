@extends('layouts.app')

@section('title', 'Register - QuailConnect')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white">Create Account</h2>
            <p class="mt-2 text-[#9ca3af]">Join the QuailConnect marketplace</p>
        </div>

        <div class="bg-[#1e293b] rounded-xl shadow-lg border border-[#374151] p-8" x-data="{ role: '{{ old('role', 'buyer') }}' }">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-[#9ca3af] mb-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('name')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#9ca3af] mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('email')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-2">I want to</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="buyer" x-model="role" class="sr-only peer">
                            <div class="p-4 border-2 border-[#374151] rounded-lg text-center peer-checked:border-[#d4a853] peer-checked:bg-[#d4a853]/10 hover:bg-[#111827] transition">
                                <svg class="w-8 h-8 mx-auto mb-2 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                </svg>
                                <span class="text-xs font-medium text-white">Buy Products</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="farmer" x-model="role" class="sr-only peer">
                            <div class="p-4 border-2 border-[#374151] rounded-lg text-center peer-checked:border-[#d4a853] peer-checked:bg-[#d4a853]/10 hover:bg-[#111827] transition">
                                <svg class="w-8 h-8 mx-auto mb-2 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-xs font-medium text-white">Sell as Farmer</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="investor" x-model="role" class="sr-only peer">
                            <div class="p-4 border-2 border-[#374151] rounded-lg text-center peer-checked:border-[#d4a853] peer-checked:bg-[#d4a853]/10 hover:bg-[#111827] transition">
                                <svg class="w-8 h-8 mx-auto mb-2 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-xs font-medium text-white">Invest in Farms</span>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="role === 'farmer'" x-cloak>
                    <label for="farm_name" class="block text-sm font-medium text-[#9ca3af] mb-1">Farm Name</label>
                    <input type="text" name="farm_name" id="farm_name" value="{{ old('farm_name') }}"
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('farm_name')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="role === 'buyer'" x-cloak>
                    <label for="business_name" class="block text-sm font-medium text-[#9ca3af] mb-1">Business Name</label>
                    <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}"
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('business_name')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="role === 'investor'" x-cloak>
                    <label for="company_name" class="block text-sm font-medium text-[#9ca3af] mb-1">Company / Fund Name</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('company_name')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-[#9ca3af] mb-1">Phone Number <span class="text-gray-500">(optional)</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#9ca3af] mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                    @error('password')
                        <p class="mt-1 text-sm text-[#ef4444]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#9ca3af] mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none transition placeholder-gray-500">
                </div>

                <button type="submit"
                    class="w-full bg-[#d4a853] text-[#0f1419] py-2.5 px-4 rounded-lg font-semibold hover:bg-[#c49a48] focus:ring-4 focus:ring-[#d4a853]/25 transition">
                    Create Account
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#9ca3af]">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#d4a853] hover:text-[#e0b96a] font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
