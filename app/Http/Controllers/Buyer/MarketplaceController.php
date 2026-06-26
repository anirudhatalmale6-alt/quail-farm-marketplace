<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Browse all active products with search, category filter, and price sort.
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')
            ->where('quantity_available', '>', 0)
            ->with(['user', 'category', 'reviews']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                      ->orderByDesc('reviews_avg_rating');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('type', 'product')
            ->where('status', 'active')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('buyer.marketplace.index', compact('products', 'categories'));
    }

    /**
     * View single product details with farmer info, reviews, and order button.
     */
    public function show($id)
    {
        $product = Product::where('status', 'active')
            ->with(['user', 'category', 'reviews.reviewer'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        // Get farmer's other products
        $relatedProducts = Product::where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Farmer rating
        $farmerRating = \App\Models\Review::where('reviewed_user_id', $product->user_id)
            ->avg('rating');

        $farmerReviewCount = \App\Models\Review::where('reviewed_user_id', $product->user_id)
            ->count();

        return view('buyer.marketplace.show', compact(
            'product',
            'relatedProducts',
            'farmerRating',
            'farmerReviewCount'
        ));
    }
}
