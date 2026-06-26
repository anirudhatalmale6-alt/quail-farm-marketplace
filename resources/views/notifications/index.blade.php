@extends('layouts.app')

@section('title', 'Notifications - QuailConnect')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Notifications</h1>
            <p class="text-[#9ca3af] text-sm mt-1">Stay updated with your latest activity</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-sm text-[#d4a853] hover:text-[#f59e0b] transition-colors font-medium">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="space-y-2">
        @forelse($notifications as $notification)
        @php
            $typeIcons = [
                'feed_post' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>',
                'stream' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
                'order' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                'message' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',
                'credit' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'system' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            ];
            $typeColors = [
                'feed_post' => 'text-[#3b82f6]',
                'stream' => 'text-[#8b5cf6]',
                'order' => 'text-[#10b981]',
                'message' => 'text-[#06b6d4]',
                'credit' => 'text-[#d4a853]',
                'system' => 'text-[#9ca3af]',
            ];
            $icon = $typeIcons[$notification->type] ?? $typeIcons['system'];
            $color = $typeColors[$notification->type] ?? $typeColors['system'];
        @endphp

        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="w-full text-left bg-[#1e293b] rounded-xl border {{ $notification->is_read ? 'border-[#374151]' : 'border-l-4 border-l-[#d4a853] border-t-[#374151] border-r-[#374151] border-b-[#374151]' }} p-4 hover:bg-[#1e293b]/80 transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium {{ $notification->is_read ? 'text-[#9ca3af]' : 'text-white' }}">{{ $notification->title }}</h3>
                            <span class="text-[#6b7280] text-xs flex-shrink-0 ml-4">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if($notification->message)
                            <p class="text-[#6b7280] text-sm mt-1 truncate">{{ $notification->message }}</p>
                        @endif
                    </div>
                    @if(!$notification->is_read)
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-[#d4a853] rounded-full"></div>
                        </div>
                    @endif
                </div>
            </button>
        </form>
        @empty
        <div class="bg-[#1e293b] rounded-xl border border-[#374151] p-12 text-center">
            <svg class="w-16 h-16 text-[#374151] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <h3 class="text-lg font-semibold text-white mb-2">No notifications</h3>
            <p class="text-[#9ca3af] text-sm">You're all caught up!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
