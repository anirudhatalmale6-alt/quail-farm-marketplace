@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name . ' - QuailConnect')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-[#d4a853] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <img src="{{ $otherUser->avatar }}" alt="" class="w-10 h-10 rounded-full">
        <div>
            <h1 class="text-lg font-bold text-white">{{ $otherUser->name }}</h1>
            <p class="text-xs text-gray-500 capitalize">{{ $otherUser->role }}{{ $otherUser->farm_name ? ' - ' . $otherUser->farm_name : '' }}{{ $otherUser->business_name ? ' - ' . $otherUser->business_name : '' }}</p>
        </div>
    </div>

    <!-- Messages -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] mb-4">
        <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto" id="messages-container">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%]">
                        @if($message->subject)
                            <p class="text-xs font-medium text-gray-500 mb-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                                {{ $message->subject }}
                            </p>
                        @endif
                        <div class="px-4 py-3 rounded-2xl text-sm {{ $message->sender_id === auth()->id() ? 'bg-[#d4a853] text-[#0f1419] rounded-br-sm' : 'bg-[#374151] text-white rounded-bl-sm' }}">
                            {{ $message->body }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                            {{ $message->created_at->format('M d, g:i A') }}
                            @if($message->sender_id === auth()->id() && $message->read_at)
                                <span class="text-[#d4a853] ml-1">Read</span>
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 text-sm">No messages yet. Start the conversation below.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Send Message Form -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-4">
        <form method="POST" action="{{ route('messages.store') }}" class="flex items-end space-x-3">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
            <div class="flex-1">
                <textarea name="body" rows="2" required placeholder="Type your message..."
                    class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm text-white placeholder-gray-500 resize-none"></textarea>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-[#d4a853] text-[#0f1419] rounded-lg font-medium hover:bg-[#f59e0b] transition flex items-center space-x-2">
                <span>Send</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom of messages
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endpush
@endsection
