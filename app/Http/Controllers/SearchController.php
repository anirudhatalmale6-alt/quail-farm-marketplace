<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supply;
use App\Models\User;
use App\Models\FeedPost;
use App\Models\Stream;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $type = $request->input('type', 'all');

        if (strlen($query) < 2) {
            if ($request->wantsJson()) {
                return response()->json(['results' => [], 'total' => 0]);
            }
            return view('search.results', ['query' => $query, 'results' => collect(), 'type' => $type, 'total' => 0]);
        }

        $results = collect();
        $total = 0;
        $limit = $request->wantsJson() ? 5 : 20;

        if ($type === 'all' || $type === 'products') {
            $products = Product::where('status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('slug', 'LIKE', "%{$query}%");
                })
                ->with(['user:id,name,farm_name', 'category:id,name'])
                ->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END", ["{$query}%", "%{$query}%"])
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'type' => 'product',
                        'title' => $product->name,
                        'subtitle' => '$' . number_format($product->price, 2) . ' / ' . $product->unit,
                        'description' => \Illuminate\Support\Str::limit($product->description, 80),
                        'meta' => $product->user->farm_name ?? $product->user->name,
                        'category' => $product->category->name ?? null,
                        'url' => route('buyer.marketplace.show', $product->id),
                        'icon' => 'product',
                        'score' => str_starts_with(strtolower($product->name), strtolower(request('q', ''))) ? 100 : 50,
                    ];
                });
            $results = $results->merge($products);
            $total += Product::where('status', 'active')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%");
            })->count();
        }

        if ($type === 'all' || $type === 'farmers') {
            $farmers = User::where('role', 'farmer')
                ->where('status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('farm_name', 'LIKE', "%{$query}%")
                      ->orWhere('bio', 'LIKE', "%{$query}%")
                      ->orWhere('city', 'LIKE', "%{$query}%")
                      ->orWhere('state', 'LIKE', "%{$query}%");
                })
                ->withCount('products')
                ->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN farm_name LIKE ? THEN 2 ELSE 3 END", ["{$query}%", "{$query}%"])
                ->limit($limit)
                ->get()
                ->map(function ($farmer) {
                    return [
                        'id' => $farmer->id,
                        'type' => 'farmer',
                        'title' => $farmer->farm_name ?? $farmer->name,
                        'subtitle' => $farmer->name,
                        'description' => \Illuminate\Support\Str::limit($farmer->bio, 80),
                        'meta' => ($farmer->city ? $farmer->city . ', ' : '') . ($farmer->state ?? ''),
                        'category' => $farmer->products_count . ' products',
                        'url' => route('messages.show', $farmer->id),
                        'icon' => 'farmer',
                        'score' => str_starts_with(strtolower($farmer->name), strtolower(request('q', ''))) ? 100 : 50,
                    ];
                });
            $results = $results->merge($farmers);
            $total += User::where('role', 'farmer')->where('status', 'active')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")->orWhere('farm_name', 'LIKE', "%{$query}%");
            })->count();
        }

        if ($type === 'all' || $type === 'feed') {
            $posts = FeedPost::where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                })
                ->with('user:id,name,avatar')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'type' => 'feed',
                        'title' => $post->title ?: \Illuminate\Support\Str::limit($post->content, 50),
                        'subtitle' => 'by ' . $post->user->name,
                        'description' => \Illuminate\Support\Str::limit($post->content, 80),
                        'meta' => $post->likes_count . ' likes, ' . $post->comments_count . ' comments',
                        'category' => ucfirst($post->type),
                        'url' => route('feed.index'),
                        'icon' => 'feed',
                        'score' => 30,
                    ];
                });
            $results = $results->merge($posts);
            $total += FeedPost::where('is_published', true)->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")->orWhere('content', 'LIKE', "%{$query}%");
            })->count();
        }

        if ($type === 'all' || $type === 'streams') {
            $streams = Stream::where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%");
                })
                ->with('user:id,name')
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($stream) {
                    return [
                        'id' => $stream->id,
                        'type' => 'stream',
                        'title' => $stream->title,
                        'subtitle' => 'by ' . $stream->user->name,
                        'description' => \Illuminate\Support\Str::limit($stream->description, 80),
                        'meta' => $stream->views_count . ' views',
                        'category' => ucfirst($stream->category),
                        'url' => route('streams.show', $stream->id),
                        'icon' => 'stream',
                        'score' => 25,
                    ];
                });
            $results = $results->merge($streams);
            $total += Stream::where('is_published', true)->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%");
            })->count();
        }

        if ($type === 'all' || $type === 'categories') {
            $categories = Category::where('status', 'active')
                ->where('name', 'LIKE', "%{$query}%")
                ->withCount(['products', 'supplies'])
                ->limit($limit)
                ->get()
                ->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'type' => 'category',
                        'title' => $cat->name,
                        'subtitle' => ucfirst($cat->type) . ' category',
                        'description' => $cat->products_count . ' products, ' . $cat->supplies_count . ' supplies',
                        'meta' => null,
                        'category' => null,
                        'url' => route('buyer.marketplace.index') . '?category=' . $cat->id,
                        'icon' => 'category',
                        'score' => 20,
                    ];
                });
            $results = $results->merge($categories);
            $total += Category::where('status', 'active')->where('name', 'LIKE', "%{$query}%")->count();
        }

        if ($type === 'all' || $type === 'supplies') {
            $supplies = Supply::where('status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->with(['user:id,name', 'category:id,name'])
                ->orderByRaw("CASE WHEN name LIKE ? THEN 1 ELSE 2 END", ["{$query}%"])
                ->limit($limit)
                ->get()
                ->map(function ($supply) {
                    return [
                        'id' => $supply->id,
                        'type' => 'supply',
                        'title' => $supply->name,
                        'subtitle' => '$' . number_format($supply->price, 2) . ' / ' . $supply->unit,
                        'description' => \Illuminate\Support\Str::limit($supply->description, 80),
                        'meta' => $supply->user->name ?? '',
                        'category' => $supply->category->name ?? null,
                        'url' => '#',
                        'icon' => 'supply',
                        'score' => 40,
                    ];
                });
            $results = $results->merge($supplies);
            $total += Supply::where('status', 'active')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%");
            })->count();
        }

        $results = $results->sortByDesc('score')->values();

        if ($request->wantsJson()) {
            return response()->json([
                'results' => $results->take(8),
                'total' => $total,
                'query' => $query,
            ]);
        }

        return view('search.results', [
            'query' => $query,
            'results' => $results,
            'type' => $type,
            'total' => $total,
        ]);
    }

    public function suggestions(Request $request)
    {
        $query = trim($request->input('q', ''));
        if (strlen($query) < 1) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = collect();

        $products = Product::where('status', 'active')
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('name');
        $suggestions = $suggestions->merge($products);

        $farmers = User::where('role', 'farmer')
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('farm_name', 'LIKE', "%{$query}%");
            })
            ->limit(2)
            ->pluck('farm_name');
        $suggestions = $suggestions->merge($farmers->filter());

        $categories = Category::where('status', 'active')
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(2)
            ->pluck('name');
        $suggestions = $suggestions->merge($categories);

        return response()->json([
            'suggestions' => $suggestions->unique()->values()->take(6),
        ]);
    }
}
