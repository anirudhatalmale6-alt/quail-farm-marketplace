@extends('layouts.app')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Link -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-emerald-600 mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <img src="{{ $user->avatar }}" alt="" class="w-24 h-24 rounded-full mx-auto border-4 border-emerald-100">
                    <h2 class="text-xl font-bold text-gray-900 mt-4">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                        {{ $user->role === 'farmer' ? 'bg-emerald-100 text-emerald-800' : ($user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-900">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : ($user->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    @if($user->farm_name)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Farm</span>
                            <span class="text-gray-900">{{ $user->farm_name }}</span>
                        </div>
                    @endif
                    @if($user->business_name)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Business</span>
                            <span class="text-gray-900">{{ $user->business_name }}</span>
                        </div>
                    @endif
                    @if($user->city || $user->state)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Location</span>
                            <span class="text-gray-900">{{ implode(', ', array_filter([$user->city, $user->state, $user->country])) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined</span>
                        <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($user->bio)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Bio</p>
                        <p class="text-sm text-gray-700">{{ $user->bio }}</p>
                    </div>
                @endif

                <!-- Status Actions -->
                @if(!$user->isAdmin())
                    <div class="mt-6 pt-4 border-t border-gray-200 space-y-2">
                        @if($user->status !== 'active')
                            <form method="POST" action="{{ route('admin.users.update-status', $user->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="w-full py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
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
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <p class="text-sm text-gray-500">{{ $user->isFarmer() ? 'Total Revenue' : 'Total Spent' }}</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">${{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                @if($recentOrders->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-8">No orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-3 font-medium text-gray-500">Order #</th>
                                    <th class="text-left py-3 px-3 font-medium text-gray-500">{{ $user->isFarmer() ? 'Buyer' : 'Farmer' }}</th>
                                    <th class="text-left py-3 px-3 font-medium text-gray-500">Total</th>
                                    <th class="text-left py-3 px-3 font-medium text-gray-500">Status</th>
                                    <th class="text-left py-3 px-3 font-medium text-gray-500">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-3">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-3 text-gray-600">{{ $user->isFarmer() ? $order->buyer->name ?? 'N/A' : $order->farmer->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-3 font-medium text-gray-900">${{ number_format($order->total, 2) }}</td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ in_array($order->status, ['delivered']) ? 'bg-green-100 text-green-800' : (in_array($order->status, ['cancelled', 'refunded']) ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
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
