@extends('layouts.app')

@section('title', 'Upload Stream - QuailConnect')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Back Link -->
    <a href="{{ route('streams.index') }}" class="inline-flex items-center space-x-2 text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-6 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Back to Streams</span>
    </a>

    <div class="bg-[#1e293b] rounded-xl border border-[#374151] p-6">
        <h1 class="text-xl font-bold text-white mb-6 flex items-center space-x-2">
            <svg class="w-6 h-6 text-[#d4a853]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <span>Upload Stream</span>
        </h1>

        <form action="{{ route('streams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ thumbPreview: null }">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Title <span class="text-[#ef4444]">*</span></label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853]"
                    placeholder="Give your stream a title"
                    required
                >
                @error('title')
                    <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Description</label>
                <textarea
                    name="description"
                    rows="3"
                    class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] resize-none"
                    placeholder="Describe what this stream is about..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video URL -->
            <div>
                <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Video URL <span class="text-[#ef4444]">*</span></label>
                <input
                    type="text"
                    name="video_url"
                    value="{{ old('video_url') }}"
                    class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853]"
                    placeholder="YouTube, Vimeo, or direct video URL"
                    required
                >
                <p class="text-[#6b7280] text-xs mt-1">Paste a YouTube link, Vimeo link, or direct video URL</p>
                @error('video_url')
                    <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thumbnail -->
            <div>
                <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Thumbnail</label>
                <div class="flex items-center space-x-4">
                    <label class="flex-1 border-2 border-dashed border-[#374151] rounded-lg p-6 text-center cursor-pointer hover:border-[#d4a853] transition-colors">
                        <svg class="w-8 h-8 text-[#6b7280] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-[#9ca3af] text-sm">Click to upload thumbnail</span>
                        <input type="file" name="thumbnail" accept="image/*" class="hidden" @change="thumbPreview = URL.createObjectURL($event.target.files[0])">
                    </label>
                    <div x-show="thumbPreview" x-cloak class="w-24 h-24 rounded-lg overflow-hidden border border-[#374151]">
                        <img :src="thumbPreview" class="w-full h-full object-cover">
                    </div>
                </div>
                @error('thumbnail')
                    <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & Duration -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Category <span class="text-[#ef4444]">*</span></label>
                    <select name="category" class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#d4a853]" required>
                        <option value="marketing" {{ old('category') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="farm_tour" {{ old('category') === 'farm_tour' ? 'selected' : '' }}>Farm Tour</option>
                        <option value="product_highlight" {{ old('category') === 'product_highlight' ? 'selected' : '' }}>Product Highlight</option>
                        <option value="announcement" {{ old('category') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                        <option value="tutorial" {{ old('category') === 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                    </select>
                    @error('category')
                        <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#d1d5db] mb-1.5">Duration</label>
                    <input
                        type="text"
                        name="duration"
                        value="{{ old('duration') }}"
                        class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853]"
                        placeholder="e.g., 0:45 or 2:30"
                    >
                    @error('duration')
                        <p class="text-[#ef4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-2">
                <a href="{{ route('streams.index') }}" class="px-5 py-2.5 text-sm text-[#9ca3af] hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-[#d4a853] text-[#0f1419] font-semibold px-6 py-2.5 rounded-lg hover:bg-[#f59e0b] transition-colors text-sm">
                    Upload Stream
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
