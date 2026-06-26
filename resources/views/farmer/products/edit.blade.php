@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('farmer.products.index') }}" class="inline-flex items-center text-sm text-[#9ca3af] hover:text-[#d4a853] transition-colors mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Products
        </a>
        <h1 class="text-3xl font-bold text-white">Edit Product</h1>
        <p class="mt-1 text-[#9ca3af]">Update your product listing</p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-900/30 border border-red-700 rounded-lg p-4">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium text-red-300">Please fix the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-sm text-red-400 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('farmer.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>

            <div class="space-y-4">
                {{-- Product Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-300 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                        <option value="" class="bg-[#1e293b]">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="bg-[#1e293b]" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & Inventory --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Pricing & Inventory</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300 mb-1">Price ($) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                    </div>
                </div>

                {{-- Unit --}}
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-300 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                        <option value="piece" class="bg-[#1e293b]" {{ old('unit', $product->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="dozen" class="bg-[#1e293b]" {{ old('unit', $product->unit) == 'dozen' ? 'selected' : '' }}>Dozen (12)</option>
                        <option value="tray" class="bg-[#1e293b]" {{ old('unit', $product->unit) == 'tray' ? 'selected' : '' }}>Tray</option>
                        <option value="crate" class="bg-[#1e293b]" {{ old('unit', $product->unit) == 'crate' ? 'selected' : '' }}>Crate</option>
                    </select>
                </div>

                {{-- Quantity Available --}}
                <div>
                    <label for="quantity_available" class="block text-sm font-medium text-gray-300 mb-1">Quantity Available <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', $product->quantity_available) }}" min="0" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                </div>

                {{-- Minimum Order --}}
                <div>
                    <label for="min_order" class="block text-sm font-medium text-gray-300 mb-1">Minimum Order <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" id="min_order" value="{{ old('min_order', $product->min_order) }}" min="1" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                </div>
            </div>
        </div>

        {{-- Current Images --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6" x-data="{ removedImages: [] }">
            <h2 class="text-lg font-semibold text-white mb-4">Current Images</h2>

            @if($product->images && count($product->images) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                @foreach($product->images as $image)
                <div class="relative group" x-show="!removedImages.includes('{{ $image }}')">
                    <img src="{{ asset('storage/' . $image) }}" alt="Product image" class="w-full h-32 object-cover rounded-lg border border-[#374151]">
                    <button type="button"
                        @click="removedImages.push('{{ $image }}')"
                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <input type="hidden" name="remove_images[]" value="{{ $image }}" :disabled="!removedImages.includes('{{ $image }}')">
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 mb-4">No images uploaded yet.</p>
            @endif

            {{-- Upload New Images --}}
            <h3 class="text-sm font-medium text-gray-300 mb-2">Add More Images</h3>
            <div x-data="{ newPreviews: [] }">
                <label class="block cursor-pointer">
                    <div class="border-2 border-dashed border-[#374151] rounded-lg p-6 text-center hover:border-[#d4a853]/50 transition-colors">
                        <svg class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Click to upload new images</p>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each</p>
                    </div>
                    <input type="file" name="new_images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden"
                        @change="newPreviews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                </label>
                <div class="grid grid-cols-5 gap-3 mt-4" x-show="newPreviews.length > 0">
                    <template x-for="(preview, index) in newPreviews" :key="index">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#0f1419] border border-[#374151]">
                            <img :src="preview" class="w-full h-full object-cover" alt="New image preview">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Listing Status</h2>

            <div class="flex items-center space-x-6">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="active" {{ old('status', $product->status) == 'active' ? 'checked' : '' }}
                        class="w-4 h-4 text-[#d4a853] border-[#374151] bg-[#1e293b] focus:ring-[#d4a853]">
                    <span class="ml-2 text-sm text-gray-300">Active - Visible on marketplace</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="draft" {{ old('status', $product->status) == 'draft' ? 'checked' : '' }}
                        class="w-4 h-4 text-[#d4a853] border-[#374151] bg-[#1e293b] focus:ring-[#d4a853]">
                    <span class="ml-2 text-sm text-gray-300">Draft - Hidden from marketplace</span>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('farmer.products.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-300 bg-[#1e293b] border border-[#374151] rounded-lg hover:bg-[#374151] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-[#0f1419] bg-[#d4a853] rounded-lg hover:bg-[#f59e0b] transition-colors shadow-sm">
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection
