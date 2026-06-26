@extends('layouts.app')

@section('title', 'Feed - QuailConnect')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Feed</h1>
            <p class="text-[#9ca3af] text-sm mt-1">Stay updated with the latest from our quail farming community</p>
        </div>
    </div>

    <!-- Create Post Card -->
    @if(auth()->user()->isFarmer() || auth()->user()->isAdmin())
    <div class="bg-[#1e293b] rounded-xl border border-[#374151] p-5 mb-6" x-data="{ expanded: false, imagePreview: null }">
        <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-start space-x-3">
                <img src="{{ auth()->user()->avatar }}" alt="" class="w-10 h-10 rounded-full border-2 border-[#374151]">
                <div class="flex-1">
                    <textarea
                        name="content"
                        @focus="expanded = true"
                        rows="2"
                        :rows="expanded ? 4 : 2"
                        class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-3 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853] resize-none transition-all"
                        placeholder="Share an update with the community..."
                        required
                    ></textarea>

                    <div x-show="expanded" x-cloak class="mt-3 space-y-3">
                        <!-- Title (optional) -->
                        <input
                            type="text"
                            name="title"
                            class="w-full bg-[#0f1419] border border-[#374151] rounded-lg px-4 py-2 text-white placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853]"
                            placeholder="Post title (optional)"
                        >

                        <!-- Post Type & Image -->
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center space-x-3">
                                <select name="type" class="bg-[#0f1419] border border-[#374151] rounded-lg px-3 py-2 text-sm text-[#9ca3af] focus:outline-none focus:border-[#d4a853]">
                                    <option value="update">Update</option>
                                    <option value="announcement">Announcement</option>
                                    <option value="promotion">Promotion</option>
                                    <option value="story">Story</option>
                                </select>

                                <label class="flex items-center space-x-2 cursor-pointer text-[#9ca3af] hover:text-[#d4a853] transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">Photo</span>
                                    <input type="file" name="image" accept="image/*" class="hidden" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                                </label>
                            </div>

                            <button type="submit" class="bg-[#d4a853] text-[#0f1419] font-semibold px-6 py-2 rounded-lg hover:bg-[#f59e0b] transition-colors text-sm">
                                Post
                            </button>
                        </div>

                        <!-- Image Preview -->
                        <div x-show="imagePreview" x-cloak class="relative">
                            <img :src="imagePreview" class="w-full max-h-64 object-cover rounded-lg border border-[#374151]">
                            <button type="button" @click="imagePreview = null; $el.closest('form').querySelector('input[type=file]').value = ''" class="absolute top-2 right-2 bg-[#0f1419]/80 text-white rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-600 transition-colors">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Feed Posts -->
    <div class="space-y-6">
        @forelse($posts as $post)
        <div class="bg-[#1e293b] rounded-xl border border-[#374151] overflow-hidden" x-data="{ showComments: false, liked: {{ $post->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}, likesCount: {{ $post->likes_count }}, commentsCount: {{ $post->comments_count }} }">
            <!-- Post Header -->
            <div class="flex items-center justify-between p-5 pb-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ $post->user->avatar }}" alt="" class="w-10 h-10 rounded-full border-2 border-[#374151]">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-white font-medium text-sm">{{ $post->user->name }}</span>
                            @if($post->user->isFarmer())
                                <span class="text-xs bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded-full">Farmer</span>
                            @elseif($post->user->isAdmin())
                                <span class="text-xs bg-[#d4a853]/20 text-[#d4a853] px-2 py-0.5 rounded-full">Admin</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-[#6b7280] text-xs">{{ $post->created_at->diffForHumans() }}</span>
                            @php
                                $typeBadges = [
                                    'update' => ['bg-[#3b82f6]/20', 'text-[#3b82f6]'],
                                    'announcement' => ['bg-[#ef4444]/20', 'text-[#ef4444]'],
                                    'promotion' => ['bg-[#d4a853]/20', 'text-[#d4a853]'],
                                    'story' => ['bg-[#8b5cf6]/20', 'text-[#8b5cf6]'],
                                ];
                                $badge = $typeBadges[$post->type] ?? $typeBadges['update'];
                            @endphp
                            <span class="text-xs {{ $badge[0] }} {{ $badge[1] }} px-2 py-0.5 rounded-full capitalize">{{ $post->type }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    @if($post->is_pinned)
                        <span class="text-xs bg-[#d4a853]/20 text-[#d4a853] px-2 py-1 rounded-full flex items-center space-x-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 5a2 2 0 012-2h6a2 2 0 012 2v2H5V5zm0 4h10l-1.5 7.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/></svg>
                            <span>Pinned</span>
                        </span>
                    @endif

                    @if($post->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form action="{{ route('feed.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[#6b7280] hover:text-[#ef4444] transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Post Content -->
            <div class="px-5 pb-3">
                @if($post->title)
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $post->title }}</h3>
                @endif
                <p class="text-[#d1d5db] text-sm leading-relaxed whitespace-pre-line">{{ $post->content }}</p>
            </div>

            <!-- Post Image -->
            @if($post->image)
                <div class="px-5 pb-3">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="" class="w-full rounded-lg border border-[#374151] max-h-96 object-cover">
                </div>
            @endif

            <!-- Like & Comment Actions -->
            <div class="px-5 py-3 border-t border-[#374151] flex items-center space-x-6">
                <button
                    @click="
                        fetch('{{ route('feed.like', $post->id) }}', {
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

                <button
                    @click="showComments = !showComments"
                    class="flex items-center space-x-2 text-[#9ca3af] hover:text-[#3b82f6] transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-sm" x-text="commentsCount"></span>
                </button>
            </div>

            <!-- Comments Section -->
            <div x-show="showComments" x-cloak class="border-t border-[#374151]">
                <!-- Existing Comments -->
                <div class="px-5 py-3 space-y-3 max-h-64 overflow-y-auto" id="comments-{{ $post->id }}">
                    @foreach($post->comments->sortBy('created_at') as $comment)
                        <div class="flex items-start space-x-2">
                            <img src="{{ $comment->user->avatar }}" alt="" class="w-7 h-7 rounded-full border border-[#374151]">
                            <div class="bg-[#0f1419] rounded-lg px-3 py-2 flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-white text-xs font-medium">{{ $comment->user->name }}</span>
                                    <span class="text-[#6b7280] text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[#d1d5db] text-sm mt-1">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Comment -->
                <div class="px-5 py-3 border-t border-[#374151]/50">
                    <div class="flex items-center space-x-2">
                        <img src="{{ auth()->user()->avatar }}" alt="" class="w-7 h-7 rounded-full border border-[#374151]">
                        <div class="flex-1 flex items-center space-x-2" x-data="{ commentText: '' }">
                            <input
                                type="text"
                                x-model="commentText"
                                @keydown.enter="
                                    if (commentText.trim()) {
                                        fetch('{{ route('feed.comment', $post->id) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify({ content: commentText })
                                        })
                                        .then(r => r.json())
                                        .then(data => {
                                            const container = document.getElementById('comments-{{ $post->id }}');
                                            container.innerHTML += `<div class=&quot;flex items-start space-x-2&quot;><img src=&quot;${data.comment.user_avatar}&quot; alt=&quot;&quot; class=&quot;w-7 h-7 rounded-full border border-[#374151]&quot;><div class=&quot;bg-[#0f1419] rounded-lg px-3 py-2 flex-1&quot;><div class=&quot;flex items-center space-x-2&quot;><span class=&quot;text-white text-xs font-medium&quot;>${data.comment.user_name}</span><span class=&quot;text-[#6b7280] text-xs&quot;>${data.comment.created_at}</span></div><p class=&quot;text-[#d1d5db] text-sm mt-1&quot;>${data.comment.content}</p></div></div>`;
                                            container.scrollTop = container.scrollHeight;
                                            commentsCount = data.comments_count;
                                            commentText = '';
                                        });
                                    }
                                "
                                class="flex-1 bg-[#0f1419] border border-[#374151] rounded-lg px-3 py-2 text-white text-sm placeholder-[#6b7280] focus:outline-none focus:border-[#d4a853]"
                                placeholder="Write a comment..."
                            >
                            <button
                                @click="
                                    if (commentText.trim()) {
                                        fetch('{{ route('feed.comment', $post->id) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify({ content: commentText })
                                        })
                                        .then(r => r.json())
                                        .then(data => {
                                            const container = document.getElementById('comments-{{ $post->id }}');
                                            container.innerHTML += `<div class=&quot;flex items-start space-x-2&quot;><img src=&quot;${data.comment.user_avatar}&quot; alt=&quot;&quot; class=&quot;w-7 h-7 rounded-full border border-[#374151]&quot;><div class=&quot;bg-[#0f1419] rounded-lg px-3 py-2 flex-1&quot;><div class=&quot;flex items-center space-x-2&quot;><span class=&quot;text-white text-xs font-medium&quot;>${data.comment.user_name}</span><span class=&quot;text-[#6b7280] text-xs&quot;>${data.comment.created_at}</span></div><p class=&quot;text-[#d1d5db] text-sm mt-1&quot;>${data.comment.content}</p></div></div>`;
                                            container.scrollTop = container.scrollHeight;
                                            commentsCount = data.comments_count;
                                            commentText = '';
                                        });
                                    }
                                "
                                class="text-[#d4a853] hover:text-[#f59e0b] transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-[#1e293b] rounded-xl border border-[#374151] p-12 text-center">
            <svg class="w-16 h-16 text-[#374151] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <h3 class="text-lg font-semibold text-white mb-2">No posts yet</h3>
            <p class="text-[#9ca3af] text-sm">Be the first to share an update with the community!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
