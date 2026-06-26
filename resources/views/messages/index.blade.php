@extends('layouts.app')

@section('title', 'Messages - QuailConnect')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-600 mt-1">Your conversations</p>
    </div>

    <!-- Conversations List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @forelse($conversations as $conversation)
            <a href="{{ route('messages.show', $conversation->partner->id) }}"
               class="block p-4 hover:bg-gray-50 transition {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex items-center space-x-4">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $conversation->partner->avatar }}" alt="" class="w-12 h-12 rounded-full">
                        @if($conversation->unread_count > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-600 text-white text-xs rounded-full flex items-center justify-center font-medium">
                                {{ $conversation->unread_count }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                {{ $conversation->partner->name }}
                                <span class="text-xs font-normal text-gray-500 capitalize">({{ $conversation->partner->role }})</span>
                            </h3>
                            <span class="text-xs text-gray-400 flex-shrink-0 ml-2">
                                {{ $conversation->latest_message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 truncate mt-1">
                            @if($conversation->latest_message->sender_id === auth()->id())
                                <span class="text-gray-400">You: </span>
                            @endif
                            {{ Str::limit($conversation->latest_message->body, 80) }}
                        </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No messages yet</h3>
                <p class="text-gray-500 text-sm">Start a conversation by messaging a buyer or farmer.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
