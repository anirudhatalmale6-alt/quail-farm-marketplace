<?php

namespace App\Http\Controllers;

use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedPost;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    /**
     * Show the feed page with all published posts.
     */
    public function index()
    {
        $posts = FeedPost::where('is_published', true)
            ->with(['user', 'comments.user', 'likes'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('feed.index', compact('posts'));
    }

    /**
     * Create a new feed post.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isFarmer() && !$user->isAdmin()) {
            abort(403, 'Only farmers and admins can create posts.');
        }

        $validated = $request->validate([
            'type' => 'required|in:update,announcement,promotion,story',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('feed-images', 'public');
        }

        FeedPost::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        return redirect()->route('feed.index')->with('success', 'Post published successfully!');
    }

    /**
     * Toggle like on a feed post.
     */
    public function like($id)
    {
        $post = FeedPost::findOrFail($id);
        $userId = Auth::id();

        $existing = FeedLike::where('feed_post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            FeedLike::create([
                'feed_post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }

    /**
     * Add a comment to a feed post.
     */
    public function comment(Request $request, $id)
    {
        $post = FeedPost::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = FeedComment::create([
            'feed_post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        $post->increment('comments_count');

        // Notify the post author if commenter is different
        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'feed_post',
                'title' => Auth::user()->name . ' commented on your post',
                'message' => \Illuminate\Support\Str::limit($validated['content'], 100),
                'link' => route('feed.index'),
            ]);
        }

        $comment->load('user');

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'user_avatar' => $comment->user->avatar,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
            'comments_count' => $post->fresh()->comments_count,
        ]);
    }

    /**
     * Delete a feed post.
     */
    public function destroy($id)
    {
        $post = FeedPost::findOrFail($id);
        $user = Auth::user();

        if ($post->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You can only delete your own posts.');
        }

        $post->delete();

        return redirect()->route('feed.index')->with('success', 'Post deleted successfully.');
    }
}
