@extends('layouts.app')

@section('title', 'Edit Profile - QuailConnect')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-white mb-6">Edit Profile</h1>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Profile Picture -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-6" x-data="{ preview: null }">
            <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wide mb-4">Profile Picture</h3>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img :src="preview || '{{ $user->profile_picture_url }}'" class="w-24 h-24 rounded-full object-cover border-2 border-[#374151]">
                </div>
                <div>
                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-[#374151] text-white rounded-lg hover:bg-[#4b5563] transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Upload Photo
                        <input type="file" name="profile_picture" accept="image/*" class="hidden"
                            @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                    <p class="text-xs text-[#6b7280] mt-2">JPG, PNG. Max 2MB.</p>
                </div>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wide mb-2">Basic Information</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
            </div>

            @if($user->isFarmer())
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Farm Name</label>
                    <input type="text" name="farm_name" value="{{ old('farm_name', $user->farm_name) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
            @endif

            @if($user->isBuyer())
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $user->business_name) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
            @endif

            @if($user->isInvestor())
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Investment Budget ($)</label>
                    <input type="number" name="investment_budget" value="{{ old('investment_budget', $user->investment_budget) }}" step="0.01"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Investment Interests</label>
                    <textarea name="investment_interests" rows="3"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none resize-none">{{ old('investment_interests', $user->investment_interests) }}</textarea>
                </div>
            @endif

            <div>
                <label class="block text-sm text-[#9ca3af] mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $user->website) }}" placeholder="https://"
                    class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
            </div>

            <div>
                <label class="block text-sm text-[#9ca3af] mb-1">Bio</label>
                <textarea name="bio" rows="4"
                    class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none resize-none">{{ old('bio', $user->bio) }}</textarea>
            </div>
        </div>

        <!-- Location -->
        <div class="bg-[#1e293b] border border-[#374151] rounded-xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-[#d4a853] uppercase tracking-wide mb-2">Location</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">State</label>
                    <input type="text" name="state" value="{{ old('state', $user->state) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
                <div>
                    <label class="block text-sm text-[#9ca3af] mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}"
                        class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm text-[#9ca3af] mb-1">Address</label>
                <textarea name="address" rows="2"
                    class="w-full px-4 py-2.5 bg-[#0f1419] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none resize-none">{{ old('address', $user->address) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-[#d4a853] text-[#0f1419] font-bold rounded-lg hover:bg-[#f59e0b] transition-colors">
                Save Changes
            </button>
            <a href="{{ route('profile.show', $user->id) }}" class="px-6 py-3 bg-[#374151] text-white rounded-lg hover:bg-[#4b5563] transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
