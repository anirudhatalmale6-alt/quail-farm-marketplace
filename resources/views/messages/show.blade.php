@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - QuailConnect')

@section('content')
<div class="h-[calc(100vh-4rem)] flex overflow-hidden">

    {{-- ===== Sidebar - Conversation List (Desktop only) ===== --}}
    <div class="hidden md:flex md:w-96 md:flex-shrink-0 border-r border-[#374151] bg-[#0f1419] flex-col" x-data="{ search: '' }">

        {{-- Sidebar Header --}}
        <div class="p-4 border-b border-[#374151]">
            <h2 class="text-lg font-bold text-white mb-3">Messages</h2>
            <div class="relative">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input x-model="search" type="text" placeholder="Search..."
                    class="w-full pl-10 pr-4 py-2 bg-[#1e293b] border border-[#374151] rounded-xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none">
            </div>
        </div>

        {{-- Sidebar Conversation List --}}
        <div class="flex-1 overflow-y-auto scrollbar-thin">
            @foreach($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation->partner->id) }}"
                   data-name="{{ strtolower($conversation->partner->name) }}"
                   x-show="search === '' || $el.dataset.name.includes(search.toLowerCase())"
                   class="flex items-center gap-3 px-4 py-3.5 hover:bg-[#1e293b] transition-colors border-b border-[#374151]/40 {{ $conversation->partner->id == $otherUser->id ? 'bg-[#1e293b] border-l-2 border-l-[#d4a853]' : '' }}">

                    <div class="relative flex-shrink-0">
                        <img src="{{ $conversation->partner->avatar }}" alt="" class="w-11 h-11 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-[#0f1419] rounded-full"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-sm font-semibold truncate {{ $conversation->partner->id == $otherUser->id ? 'text-[#d4a853]' : 'text-white' }}">
                                {{ $conversation->partner->name }}
                            </h3>
                            <span class="text-[11px] text-gray-500 flex-shrink-0 ml-2">
                                {{ $conversation->latest_message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mt-0.5">
                            <p class="text-xs text-gray-400 truncate pr-2">
                                @if($conversation->latest_message->sender_id === auth()->id())
                                    <svg class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5 {{ $conversation->latest_message->read_at ? 'text-[#d4a853]' : 'text-gray-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 13l4 4L16 7"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l4 4L21 7"/>
                                    </svg>
                                @endif
                                {{ Str::limit($conversation->latest_message->body, 35) }}
                            </p>
                            @if($conversation->unread_count > 0)
                                <span class="flex-shrink-0 ml-1 min-w-[20px] h-5 px-1.5 bg-[#d4a853] text-[#0f1419] text-[11px] rounded-full flex items-center justify-center font-bold">
                                    {{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===== Chat Area ===== --}}
    <div class="flex-1 flex flex-col bg-[#111827] min-w-0" x-data="chatApp()" x-init="init()">

        {{-- Chat Header --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-[#0f1419] border-b border-[#374151] flex-shrink-0">
            <a href="{{ route('messages.index') }}" class="md:hidden text-gray-400 hover:text-[#d4a853] transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="relative flex-shrink-0">
                <img src="{{ $otherUser->avatar }}" alt="" class="w-10 h-10 rounded-full object-cover">
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-[#0f1419] rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-sm font-semibold text-white truncate">{{ $otherUser->name }}</h2>
                <p class="text-xs text-green-400">Online</p>
            </div>
            <div class="flex-shrink-0 text-xs text-gray-500 capitalize hidden sm:block">
                {{ $otherUser->role }}@if($otherUser->farm_name) &middot; {{ $otherUser->farm_name }}@endif@if($otherUser->business_name) &middot; {{ $otherUser->business_name }}@endif
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 scrollbar-thin" x-ref="chatContainer"
             style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23374151&quot; fill-opacity=&quot;0.06&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">

            {{-- Empty state --}}
            <template x-if="messages.length === 0">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">No messages yet. Say hello!</p>
                    </div>
                </div>
            </template>

            {{-- Messages --}}
            <template x-for="(msg, index) in messages" :key="msg.id">
                <div>
                    {{-- Date separator --}}
                    <template x-if="showDateSeparator(index)">
                        <div class="flex items-center justify-center my-4">
                            <span class="bg-[#1e293b] text-gray-400 text-[11px] px-4 py-1 rounded-full shadow-sm border border-[#374151]/50" x-text="formatDate(msg.created_at)"></span>
                        </div>
                    </template>

                    {{-- Message bubble --}}
                    <div class="flex mb-2" :class="msg.sender_id == authUserId ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[75%] sm:max-w-[65%]">

                            {{-- Subject line --}}
                            <template x-if="msg.subject">
                                <p class="text-[10px] font-medium text-gray-500 mb-0.5 px-1" :class="msg.sender_id == authUserId ? 'text-right' : ''">
                                    <span x-text="msg.subject"></span>
                                </p>
                            </template>

                            {{-- Bubble body --}}
                            <div class="relative px-3 py-2 rounded-2xl text-sm leading-relaxed"
                                 :class="msg.sender_id == authUserId
                                    ? 'bg-[#d4a853] text-[#0f1419] rounded-tr-sm'
                                    : 'bg-[#1e293b] text-white rounded-tl-sm border border-[#374151]/60'">

                                {{-- Message text --}}
                                <span class="whitespace-pre-wrap break-words" x-text="msg.body"></span>

                                {{-- Inline timestamp + read receipt --}}
                                <span class="inline-flex items-center gap-1 float-right ml-3 mt-1 -mb-1 select-none"
                                      :class="msg.sender_id == authUserId ? 'text-[#0f1419]/50' : 'text-gray-500'">
                                    <span class="text-[10px] leading-none" x-text="formatTime(msg.created_at)"></span>
                                    <template x-if="msg.sender_id == authUserId">
                                        <svg class="w-[14px] h-[14px]" :class="msg.read_at ? 'text-[#0f1419]/70' : 'text-[#0f1419]/30'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 13l4 4L16 7"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l4 4L21 7"/>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Message Input Bar --}}
        <div class="px-3 py-3 bg-[#0f1419] border-t border-[#374151] flex-shrink-0">
            <div class="flex items-end gap-2 max-w-4xl mx-auto">
                <div class="flex-1">
                    <textarea x-model="newMessage"
                        x-ref="messageInput"
                        @keydown.enter="if(!$event.shiftKey) { $event.preventDefault(); sendMessage(); }"
                        @input="autoResize($event)"
                        placeholder="Type a message..."
                        rows="1"
                        class="w-full px-4 py-3 bg-[#1e293b] border border-[#374151] rounded-2xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none resize-none overflow-y-auto leading-5"
                        style="max-height: 7.5rem;"></textarea>
                </div>
                <button @click="sendMessage()"
                    :disabled="sending || !newMessage.trim()"
                    class="flex-shrink-0 w-11 h-11 bg-[#d4a853] hover:bg-[#f59e0b] text-[#0f1419] rounded-full flex items-center justify-center transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-[#d4a853] shadow-lg">
                    {{-- Send icon --}}
                    <svg x-show="!sending" class="w-5 h-5 translate-x-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{-- Spinner --}}
                    <svg x-show="sending" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
            <p class="text-center text-[10px] text-gray-600 mt-1.5 select-none">Press Enter to send, Shift+Enter for new line</p>
        </div>
    </div>
</div>

<style>
    .scrollbar-thin::-webkit-scrollbar { width: 5px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #374151; border-radius: 9999px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #4b5563; }
</style>

@push('scripts')
<script>
function chatApp() {
    return {
        messages: @json($messages->map(fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'sender_id' => $m->sender_id,
            'receiver_id' => $m->receiver_id,
            'subject' => $m->subject,
            'created_at' => $m->created_at->toISOString(),
            'read_at' => $m->read_at ? $m->read_at->toISOString() : null,
        ])),
        newMessage: '',
        sending: false,
        authUserId: {{ auth()->id() }},
        otherUserId: {{ $otherUser->id }},
        pollInterval: null,

        init() {
            this.$nextTick(() => this.scrollToBottom());
            this.startPolling();
        },

        destroy() {
            if (this.pollInterval) clearInterval(this.pollInterval);
        },

        scrollToBottom() {
            const el = this.$refs.chatContainer;
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        },

        startPolling() {
            this.pollInterval = setInterval(() => this.fetchNewMessages(), 5000);
        },

        get lastMessageId() {
            return this.messages.length > 0
                ? this.messages[this.messages.length - 1].id
                : 0;
        },

        async fetchNewMessages() {
            try {
                const res = await fetch(
                    '/messages/' + this.otherUserId + '/fetch?since=' + this.lastMessageId,
                    { headers: { 'Accept': 'application/json' } }
                );
                if (!res.ok) return;
                const data = await res.json();

                // Append genuinely new messages
                if (data.messages && data.messages.length > 0) {
                    const existing = new Set(this.messages.map(m => m.id));
                    const fresh = data.messages.filter(m => !existing.has(m.id));
                    if (fresh.length > 0) {
                        this.messages.push(...fresh);
                        this.$nextTick(() => this.scrollToBottom());
                    }
                }

                // Update read receipts on sent messages
                if (data.read_up_to) {
                    this.messages.forEach(msg => {
                        if (msg.sender_id == this.authUserId
                            && msg.id <= data.read_up_to
                            && !msg.read_at) {
                            msg.read_at = new Date().toISOString();
                        }
                    });
                }
            } catch (e) {
                // Silently ignore polling errors
            }
        },

        async sendMessage() {
            const body = this.newMessage.trim();
            if (!body || this.sending) return;
            this.sending = true;

            try {
                const res = await fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        receiver_id: this.otherUserId,
                        body: body,
                    }),
                });

                const data = await res.json();
                if (data.status === 'success' && data.message) {
                    this.messages.push(data.message);
                    this.newMessage = '';
                    // Reset textarea height
                    if (this.$refs.messageInput) {
                        this.$refs.messageInput.style.height = 'auto';
                    }
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                console.error('Send failed:', e);
            }

            this.sending = false;
        },

        formatTime(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },

        formatDate(dateStr) {
            const d = new Date(dateStr);
            const now = new Date();
            const yest = new Date();
            yest.setDate(yest.getDate() - 1);

            if (d.toDateString() === now.toDateString()) return 'Today';
            if (d.toDateString() === yest.toDateString()) return 'Yesterday';
            return d.toLocaleDateString([], { month: 'long', day: 'numeric' });
        },

        showDateSeparator(index) {
            if (index === 0) return true;
            const curr = new Date(this.messages[index].created_at).toDateString();
            const prev = new Date(this.messages[index - 1].created_at).toDateString();
            return curr !== prev;
        },

        autoResize(event) {
            const el = event.target;
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        },
    };
}
</script>
@endpush
@endsection
