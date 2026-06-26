@extends('layouts.app')

@section('title', 'Add New Product')

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
        <h1 class="text-3xl font-bold text-white">Add New Product</h1>
        <p class="mt-1 text-[#9ca3af]">List a new product on the marketplace</p>
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
    <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>

            <div class="space-y-4">
                {{-- Product Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors"
                        placeholder="e.g., Fresh Quail Eggs - Free Range">
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-300 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                        <option value="" class="bg-[#1e293b]">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="bg-[#1e293b]" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors"
                        placeholder="Describe your product, its quality, and any special features...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & Inventory --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6" x-data="{ onSale: {{ old('is_on_sale') ? 'true' : 'false' }}, origPrice: '{{ old('price', '') }}', salePrice: '{{ old('sale_price', '') }}' }">
            <h2 class="text-lg font-semibold text-white mb-4">Pricing & Inventory</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Original Price --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-300 mb-1">Original Price ($) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                        <input type="number" name="price" id="price" x-model="origPrice" value="{{ old('price') }}" step="0.01" min="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors"
                            placeholder="0.00">
                    </div>
                </div>

                {{-- Unit --}}
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-300 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors">
                        <option value="" class="bg-[#1e293b]">Select unit</option>
                        <option value="piece" class="bg-[#1e293b]" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="dozen" class="bg-[#1e293b]" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen (12)</option>
                        <option value="tray" class="bg-[#1e293b]" {{ old('unit') == 'tray' ? 'selected' : '' }}>Tray</option>
                        <option value="crate" class="bg-[#1e293b]" {{ old('unit') == 'crate' ? 'selected' : '' }}>Crate</option>
                        <option value="pair" class="bg-[#1e293b]" {{ old('unit') == 'pair' ? 'selected' : '' }}>Pair</option>
                        <option value="each" class="bg-[#1e293b]" {{ old('unit') == 'each' ? 'selected' : '' }}>Each</option>
                        <option value="kg" class="bg-[#1e293b]" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                        <option value="lb" class="bg-[#1e293b]" {{ old('unit') == 'lb' ? 'selected' : '' }}>Pound</option>
                        <option value="bag" class="bg-[#1e293b]" {{ old('unit') == 'bag' ? 'selected' : '' }}>Bag</option>
                    </select>
                </div>

                {{-- Sale Toggle --}}
                <div class="sm:col-span-2">
                    <label class="flex items-center cursor-pointer gap-3">
                        <input type="hidden" name="is_on_sale" value="0">
                        <input type="checkbox" name="is_on_sale" value="1" x-model="onSale"
                            class="w-5 h-5 rounded border-[#374151] bg-[#1e293b] text-[#d4a853] focus:ring-[#d4a853]">
                        <span class="text-sm font-medium text-gray-300">Enable sale / price reduction</span>
                    </label>
                </div>

                {{-- Sale Price (shown when on sale) --}}
                <div x-show="onSale" x-cloak>
                    <label for="sale_price" class="block text-sm font-medium text-gray-300 mb-1">Sale Price ($) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#10b981]">$</span>
                        <input type="number" name="sale_price" id="sale_price" x-model="salePrice" value="{{ old('sale_price') }}" step="0.01" min="0.01"
                            class="w-full pl-8 pr-4 py-2.5 bg-[#1e293b] border border-[#10b981]/50 rounded-lg text-[#10b981] placeholder-gray-500 focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981] transition-colors"
                            placeholder="0.00">
                    </div>
                </div>

                {{-- Discount Preview --}}
                <div x-show="onSale && origPrice > 0 && salePrice > 0 && salePrice < origPrice" x-cloak class="flex items-center gap-3">
                    <div class="bg-[#ef4444]/10 border border-[#ef4444]/30 rounded-lg px-4 py-2.5 flex items-center gap-2">
                        <span class="text-[#ef4444] font-bold text-lg" x-text="Math.round(((origPrice - salePrice) / origPrice) * 100) + '% OFF'"></span>
                    </div>
                    <div class="text-sm">
                        <span class="text-[#6b7280] line-through" x-text="'$' + parseFloat(origPrice).toFixed(2)"></span>
                        <span class="text-[#10b981] font-bold ml-1" x-text="'$' + parseFloat(salePrice).toFixed(2)"></span>
                    </div>
                </div>

                {{-- Quantity Available --}}
                <div>
                    <label for="quantity_available" class="block text-sm font-medium text-gray-300 mb-1">Quantity Available <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available') }}" min="0" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors"
                        placeholder="0">
                </div>

                {{-- Minimum Order --}}
                <div>
                    <label for="min_order" class="block text-sm font-medium text-gray-300 mb-1">Minimum Order <span class="text-red-500">*</span></label>
                    <input type="number" name="min_order" id="min_order" value="{{ old('min_order', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 bg-[#1e293b] border border-[#374151] rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-[#d4a853] focus:border-[#d4a853] transition-colors"
                        placeholder="1">
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-[#1e293b] rounded-xl shadow-sm border border-[#374151] p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Product Images</h2>

            <div x-data="{ previews: [] }">
                <label class="block">
                    <div class="border-2 border-dashed border-[#374151] rounded-lg p-8 text-center hover:border-[#d4a853]/50 transition-colors cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400 mb-1">Click to upload images</p>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each (max 5 images)</p>
                    </div>
                    <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden"
                        @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                </label>

                {{-- Image Previews --}}
                <div class="grid grid-cols-5 gap-3 mt-4" x-show="previews.length > 0">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="aspect-square rounded-lg overflow-hidden bg-[#0f1419] border border-[#374151]">
                            <img :src="preview" class="w-full h-full object-cover" alt="Preview">
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
                    <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                        class="w-4 h-4 text-[#d4a853] border-[#374151] bg-[#1e293b] focus:ring-[#d4a853]">
                    <span class="ml-2 text-sm text-gray-300">Active - Visible on marketplace</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }}
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
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
