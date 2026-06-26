@extends('layouts.app')

@section('title', 'Commission Settings - QuailConnect Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Commission Settings</h1>
        <p class="text-gray-600 mt-1">Manage platform commission brackets for orders</p>
    </div>

    <!-- Add New Bracket -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ showForm: false }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Commission Brackets</h3>
            <button @click="showForm = !showForm" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                <span x-text="showForm ? 'Cancel' : 'Add Bracket'"></span>
            </button>
        </div>

        <!-- Add Form -->
        <div x-show="showForm" x-cloak class="mb-6 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
            <form method="POST" action="{{ route('admin.commissions.store') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Order ($)</label>
                    <input type="number" name="min_order_amount" step="0.01" min="0" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Order ($)</label>
                    <input type="number" name="max_order_amount" step="0.01" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm"
                        placeholder="Leave blank for unlimited">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rate (%)</label>
                    <input type="number" name="rate" step="0.01" min="0" max="100" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                        Save Bracket
                    </button>
                </div>
            </form>
        </div>

        <!-- Brackets Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Min Order Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Max Order Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Commission Rate</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($commissions as $commission)
                        <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                            <!-- Display Mode -->
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-gray-900">${{ number_format($commission->min_order_amount, 2) }}</td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-gray-900">{{ $commission->max_order_amount ? '$' . number_format($commission->max_order_amount, 2) : 'Unlimited' }}</td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4">
                                    <span class="text-lg font-semibold text-emerald-600">{{ number_format($commission->rate, 2) }}%</span>
                                </td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $commission->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $commission->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </template>
                            <template x-if="!editing">
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button @click="editing = true" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">Edit</button>
                                    <form method="POST" action="{{ route('admin.commissions.destroy', $commission->id) }}" class="inline"
                                          onsubmit="return confirm('Delete this commission bracket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
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
                                            <label class="block text-xs text-gray-500 mb-1">Min ($)</label>
                                            <input type="number" name="min_order_amount" value="{{ $commission->min_order_amount }}" step="0.01" min="0" required
                                                class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Max ($)</label>
                                            <input type="number" name="max_order_amount" value="{{ $commission->max_order_amount }}" step="0.01" min="0"
                                                class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Rate (%)</label>
                                            <input type="number" name="rate" value="{{ $commission->rate }}" step="0.01" min="0" max="100" required
                                                class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" name="is_active" value="1" {{ $commission->is_active ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <label class="text-xs text-gray-600">Active</label>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-medium hover:bg-emerald-700">Save</button>
                                            <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded text-xs font-medium hover:bg-gray-300">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                            </template>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">No commission brackets defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
