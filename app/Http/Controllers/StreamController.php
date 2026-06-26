<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\StreamLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreamController extends Controller
{
    /**
     * Show all published streams.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Stream::where('is_published', true)
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $streams = $query->paginate(12);
        $categories = [
            'all' => 'All',
            'marketing' => 'Marketing',
            'farm_tour' => 'Farm Tours',
            'product_highlight' => 'Product Highlights',
            'announcement' => 'Announcements',
            'tutorial' => 'Tutorials',
        ];

        return view('streams.index', compact('streams', 'categories', 'category'));
    }

    /**
     * Show the create stream form.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isFarmer() && !$user->isAdmin()) {
            abort(403, 'Only farmers and admins can upload streams.');
        }

        return view('streams.create');
    }

    /**
     * Store a new stream.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isFarmer() && !$user->isAdmin()) {
            abort(403, 'Only farmers and admins can upload streams.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'video_url' => 'required|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'duration' => 'nullable|string|max:20',
            'category' => 'required|in:marketing,farm_tour,product_highlight,announcement,tutorial',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('stream-thumbnails', 'public');
        }

        Stream::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'video_url' => $validated['video_url'],
            'thumbnail' => $thumbnailPath,
            'duration' => $validated['duration'] ?? null,
            'category' => $validated['category'],
        ]);

        return redirect()->route('streams.index')->with('success', 'Stream uploaded successfully!');
    }

    /**
     * Show a single stream.
     */
    public function show($id)
    {
        $stream = Stream::with(['user', 'likes'])->findOrFail($id);
        $stream->increment('views_count');

        $related = Stream::where('category', $stream->category)
            ->where('id', '!=', $stream->id)
            ->where('is_published', true)
            ->with('user')
            ->latest()
            ->limit(6)
            ->get();

        return view('streams.show', compact('stream', 'related'));
    }

    /**
     * Toggle like on a stream.
     */
    public function like($id)
    {
        $stream = Stream::findOrFail($id);
        $userId = Auth::id();

        $existing = StreamLike::where('stream_id', $stream->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $stream->decrement('likes_count');
            $liked = false;
        } else {
            StreamLike::create([
                'stream_id' => $stream->id,
                'user_id' => $userId,
            ]);
            $stream->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $stream->fresh()->likes_count,
        ]);
    }

    /**
     * Delete a stream.
     */
    public function destroy($id)
    {
        $stream = Stream::findOrFail($id);
        $user = Auth::user();

        if ($stream->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You can only delete your own streams.');
        }

        $stream->delete();

        return redirect()->route('streams.index')->with('success', 'Stream deleted successfully.');
    }
}
