@extends('layouts.app')

@section('title', 'Manage Users - QuailConnect Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Manage Users</h1>
            <p class="text-[#9ca3af] mt-1">View and manage all platform users</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, farm or business..."
                    class="w-full px-4 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
            </div>
            <select name="role" class="px-4 py-2 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                <option value="">All Roles</option>
                <option value="farmer" {{ request('role') === 'farmer' ? 'selected' : '' }}>Farmers</option>
                <option value="buyer" {{ request('role') === 'buyer' ? 'selected' : '' }}>Buyers</option>
                <option value="investor" {{ request('role') === 'investor' ? 'selected' : '' }}>Investors</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
            </select>
            <select name="status" class="px-4 py-2 bg-[#1e293b] border border-[#374151] text-white rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg hover:bg-[#f59e0b] transition text-sm font-medium">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-[#9ca3af] hover:text-white text-sm font-medium">Clear</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#0f1419] border-b border-[#374151]">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">User</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Role</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Farm / Business</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Balance</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Joined</th>
                        <th class="text-right py-3 px-4 font-medium text-[#9ca3af]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#374151]/50">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $user->avatar }}" alt="" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-medium text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-[#9ca3af]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'farmer' ? 'bg-green-900/30 text-green-400' : ($user->role === 'admin' ? 'bg-purple-900/30 text-purple-400' : 'bg-blue-900/30 text-blue-400') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#9ca3af]">
                                {{ $user->farm_name ?? $user->business_name ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-[#10b981]">${{ number_format($user->balance, 2) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->status === 'active' ? 'bg-green-900/30 text-green-400' : ($user->status === 'pending' ? 'bg-amber-900/30 text-amber-400' : 'bg-red-900/30 text-red-400') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#9ca3af]">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-[#d4a853] hover:text-[#f59e0b] text-xs font-medium">View</a>

                                    @if($user->status === 'pending')
                                        <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-400 hover:text-green-300 text-xs font-medium">Approve</button>
                                        </form>
                                    @elseif($user->status === 'active' && !$user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Suspend</button>
                                        </form>
                                    @elseif($user->status === 'suspended')
                                        <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-400 hover:text-green-300 text-xs font-medium">Reactivate</button>
                                        </form>
                                    @endif

                                    @if(!$user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#9ca3af]">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-[#374151]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
