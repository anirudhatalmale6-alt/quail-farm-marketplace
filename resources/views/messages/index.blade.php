@extends('layouts.app')

@section('title', 'Messages - QuailConnect')

@section('content')
<div class="h-[calc(100vh-4rem)] flex overflow-hidden" x-data="{ search: '' }">

    {{-- Conversation List (full width on mobile, sidebar on desktop) --}}
    <div class="w-full md:w-96 md:flex-shrink-0 md:border-r border-[#374151] bg-[#0f1419] flex flex-col">

        {{-- Header + Search --}}
        <div class="p-4 border-b border-[#374151]">
            <h1 class="text-xl font-bold text-white mb-3">Messages</h1>
            <div class="relative">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input x-model="search" type="text" placeholder="Search conversations..."
                    class="w-full pl-10 pr-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
            </div>
        </div>

        {{-- Conversations --}}
        <div class="flex-1 overflow-y-auto scrollbar-thin">
            @forelse($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation->partner->id) }}"
                   data-name="{{ strtolower($conversation->partner->name) }}"
                   x-show="search === '' || $el.dataset.name.includes(search.toLowerCase())"
                   class="flex items-center gap-3 px-4 py-3.5 hover:bg-[#1e293b] transition-colors border-b border-[#374151]/40 cursor-pointer group">

                    {{-- Avatar with online dot --}}
                    <div class="relative flex-shrink-0">
                        <img src="{{ $conversation->partner->avatar }}" alt="" class="w-12 h-12 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0f1419] rounded-full"></span>
                    </div>

                    {{-- Name / Preview / Time --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-sm font-semibold text-white truncate group-hover:text-[#d4a853] transition-colors">
                                {{ $conversation->partner->name }}
                            </h3>
                            <span class="text-[11px] text-gray-500 flex-shrink-0 ml-2 {{ $conversation->unread_count > 0 ? 'text-[#d4a853] font-medium' : '' }}">
                                {{ $conversation->latest_message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mt-0.5">
                            <p class="text-xs text-gray-400 truncate pr-2">
                                @if($conversation->latest_message->sender_id === auth()->id())
                                    {{-- Double check for sent --}}
                                    <svg class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5 {{ $conversation->latest_message->read_at ? 'text-[#d4a853]' : 'text-gray-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 13l4 4L16 7"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l4 4L21 7"/>
                                    </svg>
                                @endif
                                {{ Str::limit($conversation->latest_message->body, 45) }}
                            </p>
                            @if($conversation->unread_count > 0)
                                <span class="flex-shrink-0 ml-1 min-w-[20px] h-5 px-1.5 bg-[#d4a853] text-[#0f1419] text-[11px] rounded-full flex items-center justify-center font-bold">
                                    {{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-[#1e293b] flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-1">No messages yet</h3>
                    <p class="text-gray-500 text-sm">Start a conversation by messaging a buyer or farmer.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right Panel - "Select a conversation" placeholder (Desktop only) --}}
    <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-[#111827]">
        <div class="text-center px-8">
            <div class="w-28 h-28 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-6">
                <svg class="w-14 h-14 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-300 mb-2">QuailConnect Messenger</h2>
            <p class="text-gray-500 text-sm max-w-xs mx-auto">Select a conversation from the sidebar to start chatting with buyers and farmers.</p>
        </div>
    </div>
</div>

<style>
    .scrollbar-thin::-webkit-scrollbar { width: 5px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #374151; border-radius: 9999px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #4b5563; }
</style>
@endsection
