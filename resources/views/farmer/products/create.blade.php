@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('farmer.products.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600 transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Products
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
        <p class="mt-1 text-gray-500">List a new product on the marketplace</p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium text-red-800">Please fix the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>

            <div class="space-y-4">
                {{-- Product Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                        placeholder="e.g., Fresh Quail Eggs - Free Range">
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                        placeholder="Describe your product, its quality, and any special features...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & Inventory --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Inventory</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price ($) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">$</span>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            placeholder="0.00">
                    </div>
                </div>

                {{-- Unit --}}
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        <option value="">Select unit</option>
                        <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen (12)</option>
                        <option value="tray" {{ old('unit') == 'tray' ? 'selected' : '' }}>Tray</option>
                        <option value="crate" {{ old('unit') == 'crate' ? 'selected' : '' }}>Crate</option>
                    </select>
                </div>

                {{-- Quantity Available --}}
                <div>
                    <label for="quantity_available" class="block text-sm font-medium text-gray-700 mb-1">Quantity Available <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available') }}" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                        placeholder="0">
                </div>

                {{-- Minimum Order --}}
                <div>
                    <label for="min_order" class="block text-sm font-medium text-gray-700 mb-1">Minimum Order <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" id="min_order" value="{{ old('min_order', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                        placeholder="1">
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h2>

            <div x-data="{ previews: [] }">
                <label class="block">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-400 transition-colors cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-600 mb-1">Click to upload images</p>
                        <p class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB each (max 5 images)</p>
                    </div>
                    <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden"
                        @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                </label>

                {{-- Image Previews --}}
                <div class="grid grid-cols-5 gap-3 mt-4" x-show="previews.length > 0">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                            <img :src="preview" class="w-full h-full object-cover" alt="Preview">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Listing Status</h2>

            <div class="flex items-center space-x-6">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                    <span class="ml-2 text-sm text-gray-700">Active - Visible on marketplace</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                    <span class="ml-2 text-sm text-gray-700">Draft - Hidden from marketplace</span>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('farmer.products.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
