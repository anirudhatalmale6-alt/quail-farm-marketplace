<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * List farmer's products with pagination.
     */
    public function index(Request $request)
    {
        $products = Product::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('farmer.products.index', compact('products'));
    }

    /**
     * Show the create product form.
     */
    public function create()
    {
        $categories = Category::where('type', 'product')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('farmer.products.create', compact('categories'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|min:0.01|lt:price',
            'is_on_sale' => 'nullable|boolean',
            'unit' => 'required|in:piece,dozen,tray,crate,pair,each,kg,lb,bag',
            'quantity_available' => 'required|integer|min:0',
            'min_order' => 'required|integer|min:1',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,draft',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        Product::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'is_on_sale' => $request->boolean('is_on_sale'),
            'unit' => $validated['unit'],
            'quantity_available' => $validated['quantity_available'],
            'min_order' => $validated['min_order'],
            'images' => $imagePaths,
            'status' => $validated['status'],
        ]);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the edit product form.
     */
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        $categories = Category::where('type', 'product')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('farmer.products.edit', compact('product', 'categories'));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|min:0.01|lt:price',
            'is_on_sale' => 'nullable|boolean',
            'unit' => 'required|in:piece,dozen,tray,crate,pair,each,kg,lb,bag',
            'quantity_available' => 'required|integer|min:0',
            'min_order' => 'required|integer|min:1',
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,draft',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        // Handle image removal
        $currentImages = $product->images ?? [];
        if ($request->has('remove_images')) {
            $currentImages = array_values(array_diff($currentImages, $request->remove_images));
            foreach ($request->remove_images as $imagePath) {
                \Storage::disk('public')->delete($imagePath);
            }
        }

        // Handle new image uploads
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $currentImages[] = $image->store('products', 'public');
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'is_on_sale' => $request->boolean('is_on_sale'),
            'unit' => $validated['unit'],
            'quantity_available' => $validated['quantity_available'],
            'min_order' => $validated['min_order'],
            'images' => $currentImages,
            'status' => $validated['status'],
        ]);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Soft delete a product.
     */
    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        $product->update(['status' => 'inactive']);
        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product removed successfully.');
    }
}
