@extends('layouts.app')

@section('title', 'Manage Categories - QuailConnect Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Categories</h1>
        <p class="text-gray-600 mt-1">Product and supply categories for the marketplace</p>
    </div>

    <!-- Add New Category -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ showForm: false }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
            <button @click="showForm = !showForm" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                <span x-text="showForm ? 'Cancel' : 'Add Category'"></span>
            </button>
        </div>

        <!-- Add Form -->
        <div x-show="showForm" x-cloak class="mb-6 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm"
                        placeholder="e.g., Fresh Eggs">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm">
                        <option value="product">Product</option>
                        <option value="supply">Supply</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-gray-400">(emoji)</span></label>
                    <input type="text" name="icon"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm"
                        placeholder="Optional">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                        Save Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Categories -->
        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Product Categories</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Icon</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Name</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Slug</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Products</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories->where('type', 'product') as $category)
                            <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-xl">{{ $category->icon ?? '--' }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 font-medium text-gray-900">{{ $category->name }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-gray-500">{{ $category->slug }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-gray-600">{{ $category->products->count() }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $category->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-right space-x-2">
                                        <button @click="editing = true" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">Edit</button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline"
                                              onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                        </form>
                                    </td>
                                </template>

                                <!-- Edit Mode -->
                                <template x-if="editing">
                                    <td colspan="6" class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <input type="text" name="name" value="{{ $category->name }}" required
                                                    class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                            </div>
                                            <div>
                                                <select name="type" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                                    <option value="product" {{ $category->type === 'product' ? 'selected' : '' }}>Product</option>
                                                    <option value="supply" {{ $category->type === 'supply' ? 'selected' : '' }}>Supply</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" name="icon" value="{{ $category->icon }}"
                                                    class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none" placeholder="Icon">
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" name="status" value="1" {{ $category->status ? 'checked' : '' }}
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
                                <td colspan="6" class="py-6 text-center text-gray-500">No product categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Supply Categories -->
        <div>
            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Supply Categories</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Icon</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Name</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Slug</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Supplies</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-500">Status</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories->where('type', 'supply') as $category)
                            <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-xl">{{ $category->icon ?? '--' }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 font-medium text-gray-900">{{ $category->name }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-gray-500">{{ $category->slug }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-gray-600">{{ $category->supplies->count() }}</td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $category->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </template>
                                <template x-if="!editing">
                                    <td class="py-3 px-4 text-right space-x-2">
                                        <button @click="editing = true" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">Edit</button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline"
                                              onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                        </form>
                                    </td>
                                </template>

                                <!-- Edit Mode -->
                                <template x-if="editing">
                                    <td colspan="6" class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <input type="text" name="name" value="{{ $category->name }}" required
                                                    class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                            </div>
                                            <div>
                                                <select name="type" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                                                    <option value="product" {{ $category->type === 'product' ? 'selected' : '' }}>Product</option>
                                                    <option value="supply" {{ $category->type === 'supply' ? 'selected' : '' }}>Supply</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" name="icon" value="{{ $category->icon }}"
                                                    class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-emerald-500 outline-none" placeholder="Icon">
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" name="status" value="1" {{ $category->status ? 'checked' : '' }}
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
                                <td colspan="6" class="py-6 text-center text-gray-500">No supply categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
