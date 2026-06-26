@extends('layouts.app')

@section('title', 'Browse Farmers - QuailConnect')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Browse Farmers</h1>
            <p class="text-[#9ca3af] mt-1">Discover quail farmers seeking investment</p>
        </div>
        <form action="{{ route('investor.farmers.index') }}" method="GET" class="relative w-full sm:w-80">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search farmers..."
                class="w-full pl-10 pr-4 py-2.5 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($farmers as $farmer)
            <a href="{{ route('investor.farmers.show', $farmer->id) }}" class="bg-[#1e293b] border border-[#374151] rounded-xl p-5 hover:border-[#d4a853] transition-all group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="relative">
                        <img src="{{ $farmer->avatar }}" class="w-14 h-14 rounded-full object-cover">
                        <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full border-2 border-[#1e293b] {{ $farmer->isOnlineNow() ? 'bg-[#10b981]' : 'bg-[#6b7280]' }}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white font-bold group-hover:text-[#d4a853] transition-colors truncate">{{ $farmer->farm_name ?? $farmer->name }}</h3>
                        <p class="text-sm text-[#9ca3af]">{{ $farmer->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs {{ $farmer->isOnlineNow() ? 'text-[#10b981]' : 'text-[#6b7280]' }}">
                                {{ $farmer->isOnlineNow() ? 'Online' : 'Offline' }}
                            </span>
                            @if($farmer->isPro())
                                <span class="text-[9px] px-1.5 py-0.5 bg-[#d4a853] text-[#0f1419] rounded font-bold">PRO</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($farmer->bio)
                    <p class="text-sm text-[#6b7280] mb-3 line-clamp-2">{{ $farmer->bio }}</p>
                @endif
                <div class="flex items-center justify-between text-xs text-[#9ca3af]">
                    <span>{{ $farmer->products_count }} products</span>
                    @if($farmer->city)
                        <span>{{ $farmer->city }}{{ $farmer->state ? ', ' . $farmer->state : '' }}</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-16">
                <p class="text-[#6b7280]">No farmers found matching your search.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $farmers->links() }}</div>
</div>
@endsection
