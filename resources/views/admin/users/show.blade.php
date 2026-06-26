@extends('layouts.app')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Link -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
                <div class="text-center mb-6">
                    <img src="{{ $user->avatar }}" alt="" class="w-24 h-24 rounded-full mx-auto border-4 border-[#d4a853]/30">
                    <h2 class="text-xl font-bold text-white mt-4">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                        {{ $user->role === 'farmer' ? 'bg-green-900/30 text-green-400' : ($user->role === 'admin' ? 'bg-purple-900/30 text-purple-400' : 'bg-blue-900/30 text-blue-400') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Email</span>
                        <span class="text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Phone</span>
                        <span class="text-white">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->status === 'active' ? 'bg-green-900/30 text-green-400' : ($user->status === 'pending' ? 'bg-amber-900/30 text-amber-400' : 'bg-red-900/30 text-red-400') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    @if($user->farm_name)
                        <div class="flex justify-between">
                            <span class="text-[#9ca3af]">Farm</span>
                            <span class="text-white">{{ $user->farm_name }}</span>
                        </div>
                    @endif
                    @if($user->business_name)
                        <div class="flex justify-between">
                            <span class="text-[#9ca3af]">Business</span>
                            <span class="text-white">{{ $user->business_name }}</span>
                        </div>
                    @endif
                    @if($user->city || $user->state)
                        <div class="flex justify-between">
                            <span class="text-[#9ca3af]">Location</span>
                            <span class="text-white">{{ implode(', ', array_filter([$user->city, $user->state, $user->country])) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-[#9ca3af]">Joined</span>
                        <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($user->bio)
                    <div class="mt-4 pt-4 border-t border-[#374151]">
                        <p class="text-sm text-[#9ca3af] mb-1">Bio</p>
                        <p class="text-sm text-gray-300">{{ $user->bio }}</p>
                    </div>
                @endif

                <!-- Status Actions -->
                @if(!$user->isAdmin())
                    <div class="mt-6 pt-4 border-t border-[#374151] space-y-2">
                        @if($user->status !== 'active')
                            <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="w-full py-2 bg-[#d4a853] text-[#0f1419] rounded-lg text-sm font-medium hover:bg-[#f59e0b] transition">
                                    Approve / Activate
                                </button>
                            </form>
                        @endif
                        @if($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="suspended">
                                <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                                    Suspend User
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Stats & Orders -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 text-center">
                    <p class="text-sm text-[#9ca3af]">Total Orders</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 text-center">
                    <p class="text-sm text-[#9ca3af]">{{ $user->isFarmer() ? 'Total Revenue' : 'Total Spent' }}</p>
                    <p class="text-2xl font-bold text-[#d4a853] mt-1">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-[#10b981]/20 to-[#059669]/20 rounded-xl shadow-sm border border-[#10b981]/30 p-6 text-center">
                    <p class="text-sm text-[#10b981]">Wallet Balance</p>
                    <p class="text-2xl font-bold text-[#10b981] mt-1">${{ number_format($user->balance, 2) }}</p>
                </div>
            </div>

            <!-- Balance Management -->
            @if(!$user->isAdmin())
                <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6" x-data="{ action: 'add' }">
                    <h3 class="text-lg font-semibold text-white mb-4">Manage Balance</h3>
                    <form method="POST" action="{{ route('admin.users.adjust-balance', $user->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="block text-sm text-[#9ca3af] mb-1">Action</label>
                                <select name="action" x-model="action" class="w-full bg-[#0f1419] border border-[#374151] text-white rounded-lg px-3 py-2 focus:border-[#d4a853] focus:outline-none">
                                    <option value="add">Add Funds</option>
                                    <option value="deduct">Deduct Funds</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9ca3af] mb-1">Amount ($)</label>
                                <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00"
                                    class="w-full bg-[#0f1419] border border-[#374151] text-white rounded-lg px-3 py-2 focus:border-[#d4a853] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm text-[#9ca3af] mb-1">Reason</label>
                                <input type="text" name="reason" required placeholder="Reason for adjustment"
                                    class="w-full bg-[#0f1419] border border-[#374151] text-white rounded-lg px-3 py-2 focus:border-[#d4a853] focus:outline-none">
                            </div>
                            <div>
                                <button type="submit" class="w-full py-2 rounded-lg text-sm font-semibold transition"
                                    :class="action === 'add' ? 'bg-[#10b981] text-white hover:bg-[#059669]' : 'bg-[#ef4444] text-white hover:bg-red-700'">
                                    <span x-text="action === 'add' ? 'Add Funds' : 'Deduct Funds'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Recent Orders -->
            <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Orders</h3>
                @if($recentOrders->isEmpty())
                    <p class="text-[#9ca3af] text-sm text-center py-8">No orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#374151]">
                                    <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Order #</th>
                                    <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">{{ $user->isFarmer() ? 'Buyer' : 'Farmer' }}</th>
                                    <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Total</th>
                                    <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Status</th>
                                    <th class="text-left py-3 px-3 font-medium text-[#9ca3af]">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#374151]">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-[#374151]/50">
                                        <td class="py-3 px-3">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d4a853] hover:text-[#f59e0b] font-medium">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-3 text-[#9ca3af]">{{ $user->isFarmer() ? $order->buyer->name ?? 'N/A' : $order->farmer->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-3 font-medium text-white">${{ number_format($order->total, 2) }}</td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ in_array($order->status, ['delivered']) ? 'bg-green-900/30 text-green-400' : (in_array($order->status, ['cancelled', 'refunded']) ? 'bg-red-900/30 text-red-400' : 'bg-amber-900/30 text-amber-400') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-[#9ca3af]">{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
