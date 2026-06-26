@extends('layouts.app')

@section('title', 'Commission Settings - QuailConnect Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Commission Settings</h1>
        <p class="text-[#9ca3af] mt-1">Manage platform commission brackets for orders</p>
    </div>

    <!-- Add New Bracket -->
    <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6 mb-6" x-data="{ showForm: false }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Commission Brackets</h3>
            <button @click="showForm = !showForm" class="px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg text-sm font-medium hover:bg-[#f59e0b] transition">
                <span x-text="showForm ? 'Cancel' : 'Add Bracket'"></span>
            </button>
        </div>

        <!-- Add Form -->
        <div x-show="showForm" x-cloak class="mb-6 p-4 bg-[#0f1419] rounded-lg border border-[#374151]">
            <form method="POST" action="{{ route('admin.commissions.store') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-1">Min Order ($)</label>
                    <input type="number" name="min_order_amount" step="0.01" min="0" required
                        class="w-full px-3 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-1">Max Order ($)</label>
                    <input type="number" name="max_order_amount" step="0.01" min="0"
                        class="w-full px-3 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm"
                        placeholder="Leave blank for unlimited">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#9ca3af] mb-1">Rate (%)</label>
                    <input type="number" name="rate" step="0.01" min="0" max="100" required
                        class="w-full px-3 py-2 bg-[#1e293b] border border-[#374151] text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] outline-none text-sm">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#d4a853] text-[#0f1419] rounded-lg text-sm font-medium hover:bg-[#f59e0b] transition">
                        Save Bracket
                    </button>
                </div>
            </form>
        </div>

        <!-- Brackets Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#0f1419] border-b border-[#374151]">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Min Order Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Max Order Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Commission Rate</th>
                        <th class="text-left py-3 px-4 font-medium text-[#9ca3af]">Status</th>
                        <th class="text-right py-3 px-4 font-medium text-[#9ca3af]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#374151]">
                    @forelse($commissions as $commission)
                        <tr class="hover:bg-[#374151]/50" x-data="{ editing: false }">
                            <!-- Display Mode -->
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-white">${{ number_format($commission->min_order_amount, 2) }}</td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-white">{{ $commission->max_order_amount ? '$' . number_format($commission->max_order_amount, 2) : 'Unlimited' }}</td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4">
                                    <span class="text-lg font-semibold text-[#d4a853]">{{ number_format($commission->rate, 2) }}%</span>
                                </td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $commission->is_active ? 'bg-green-900/30 text-green-400' : 'bg-gray-700/50 text-gray-400' }}">
                                        {{ $commission->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button @click="editing = true" class="text-[#d4a853] hover:text-[#f59e0b] text-xs font-medium">Edit</button>
                                    <form method="POST" action="{{ route('admin.commissions.destroy', $commission->id) }}" class="inline"
                                          onsubmit="return confirm('Delete this commission bracket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium">Delete</button>
                                    </form>
                                </td>
                            </template>

                            <!-- Edit Mode -->
                            <template x-if="editing">
                                <td colspan="5" class="py-3 px-4">
                                    <form method="POST" action="{{ route('admin.commissions.update', $commission->id) }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-xs text-[#9ca3af] mb-1">Min ($)</label>
                                            <input type="number" name="min_order_amount" value="{{ $commission->min_order_amount }}" step="0.01" min="0" required
                                                class="w-full px-3 py-1.5 bg-[#1e293b] border border-[#374151] text-white rounded text-sm focus:ring-1 focus:ring-[#d4a853] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-[#9ca3af] mb-1">Max ($)</label>
                                            <input type="number" name="max_order_amount" value="{{ $commission->max_order_amount }}" step="0.01" min="0"
                                                class="w-full px-3 py-1.5 bg-[#1e293b] border border-[#374151] text-white rounded text-sm focus:ring-1 focus:ring-[#d4a853] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-[#9ca3af] mb-1">Rate (%)</label>
                                            <input type="number" name="rate" value="{{ $commission->rate }}" step="0.01" min="0" max="100" required
                                                class="w-full px-3 py-1.5 bg-[#1e293b] border border-[#374151] text-white rounded text-sm focus:ring-1 focus:ring-[#d4a853] outline-none">
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" name="is_active" value="1" {{ $commission->is_active ? 'checked' : '' }}
                                                class="rounded border-[#374151] bg-[#1e293b] text-[#d4a853] focus:ring-[#d4a853]">
                                            <label class="text-xs text-[#9ca3af]">Active</label>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="submit" class="px-3 py-1.5 bg-[#d4a853] text-[#0f1419] rounded text-xs font-medium hover:bg-[#f59e0b]">Save</button>
                                            <button type="button" @click="editing = false" class="px-3 py-1.5 bg-[#374151] text-white rounded text-xs font-medium hover:bg-[#4b5563]">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                            </template>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[#9ca3af]">No commission brackets defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
