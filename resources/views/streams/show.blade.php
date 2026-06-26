@extends('layouts.app')

@section('title', $stream->title . ' - QuailConnect Streams')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="lg:flex lg:space-x-8">
        <!-- Main Video Section -->
        <div class="lg:flex-1">
            <!-- Back Link -->
            <a href="{{ route('streams.index') }}" class="inline-flex items-center space-x-2 text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Streams</span>
            </a>

            <!-- Video Player -->
            <div class="bg-black rounded-xl overflow-hidden aspect-video mb-4">
                @php
                    $url = $stream->video_url;
                    $isYouTube = preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $ytMatch);
                    $isVimeo = preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $vmMatch);
                @endphp

                @if($isYouTube)
                    <iframe
                        src="https://www.youtube.com/embed/{{ $ytMatch[1] }}"
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                @elseif($isVimeo)
                    <iframe
                        src="https://player.vimeo.com/video/{{ $vmMatch[1] }}"
                        class="w-full h-full"
                        frameborder="0"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                @else
                    <video
                        src="{{ $url }}"
                        class="w-full h-full"
                        controls
                        @if($stream->thumbnail) poster="{{ asset('storage/' . $stream->thumbnail) }}" @endif
                    >
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>

            <!-- Stream Info -->
            <div class="bg-[#1e293b] rounded-xl border border-[#374151] p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-white mb-2">{{ $stream->title }}</h1>
                        @php
                            $catColors = [
                                'marketing' => 'bg-[#3b82f6]/20 text-[#3b82f6]',
                                'farm_tour' => 'bg-[#10b981]/20 text-[#10b981]',
                                'product_highlight' => 'bg-[#d4a853]/20 text-[#d4a853]',
                                'announcement' => 'bg-[#ef4444]/20 text-[#ef4444]',
                                'tutorial' => 'bg-[#8b5cf6]/20 text-[#8b5cf6]',
                            ];
                            $catLabels = [
                                'marketing' => 'Marketing',
                                'farm_tour' => 'Farm Tour',
                                'product_highlight' => 'Product Highlight',
                                'announcement' => 'Announcement',
                                'tutorial' => 'Tutorial',
                            ];
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $catColors[$stream->category] ?? 'bg-[#374151] text-[#9ca3af]' }}">
                            {{ $catLabels[$stream->category] ?? ucfirst($stream->category) }}
                        </span>
                    </div>

                    @if($stream->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form action="{{ route('streams.destroy', $stream->id) }}" method="POST" onsubmit="return confirm('Delete this stream?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[#6b7280] hover:text-[#ef4444] transition-colors p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Author & Stats -->
                <div class="flex items-center justify-between py-3 border-t border-b border-[#374151]">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $stream->user->avatar }}" alt="" class="w-10 h-10 rounded-full border-2 border-[#374151]">
                        <div>
                            <span class="text-white font-medium text-sm">{{ $stream->user->name }}</span>
                            @if($stream->user->isFarmer())
                                <span class="ml-2 text-xs bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded-full">Farmer</span>
                            @endif
                            <p class="text-[#6b7280] text-xs">{{ $stream->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4" x-data="{ liked: {{ $stream->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}, likesCount: {{ $stream->likes_count }} }">
                        <div class="flex items-center space-x-2 text-[#9ca3af] text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ number_format($stream->views_count) }} views</span>
                        </div>

                        <button
                            @click="
                                fetch('{{ route('streams.like', $stream->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                })
                                .then(r => r.json())
                                .then(data => { liked = data.liked; likesCount = data.likes_count; })
                            "
                            class="flex items-center space-x-2 transition-colors"
                            :class="liked ? 'text-[#ef4444]' : 'text-[#9ca3af] hover:text-[#ef4444]'"
                        >
                            <svg class="w-5 h-5" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="text-sm" x-text="likesCount"></span>
                        </button>
                    </div>
                </div>

                <!-- Description -->
                @if($stream->description)
                    <div class="mt-4">
                        <p class="text-[#d1d5db] text-sm leading-relaxed whitespace-pre-line">{{ $stream->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Streams Sidebar -->
        <div class="lg:w-80 mt-8 lg:mt-0">
            <h2 class="text-lg font-semibold text-white mb-4">Related Streams</h2>
            <div class="space-y-4">
                @forelse($related as $rel)
                <a href="{{ route('streams.show', $rel->id) }}" class="flex space-x-3 group">
                    <div class="relative w-28 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-[#0f1419]">
                        @if($rel->thumbnail)
                            <img src="{{ asset('storage/' . $rel->thumbnail) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#374151]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        @if($rel->duration)
                            <span class="absolute bottom-1 right-1 bg-black/80 text-white text-xs px-1 rounded">{{ $rel->duration }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white text-sm font-medium line-clamp-2 group-hover:text-[#d4a853] transition-colors">{{ $rel->title }}</h3>
                        <p class="text-[#6b7280] text-xs mt-1">{{ $rel->user->name }}</p>
                        <p class="text-[#6b7280] text-xs">{{ number_format($rel->views_count) }} views</p>
                    </div>
                </a>
                @empty
                <p class="text-[#6b7280] text-sm">No related streams found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
