<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $userId = Auth::id();

        // Get unique conversation partners with latest message
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function ($messages, $partnerId) use ($userId) {
                $latest = $messages->first();
                $unreadCount = $messages->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();

                return (object) [
                    'partner' => User::find($partnerId),
                    'latest_message' => $latest,
                    'unread_count' => $unreadCount,
                ];
            })
            ->filter(function ($conv) {
                return $conv->partner !== null;
            })
            ->values();

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user.
     */
    public function show($userId)
    {
        $authId = Auth::id();
        $otherUser = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('messages', 'otherUser'));
    }

    /**
     * Send a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string|max:5000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'subject' => $validated['subject'] ?? null,
            'body' => $validated['body'],
            'order_id' => $validated['order_id'] ?? null,
        ]);

        return redirect()->route('messages.show', $validated['receiver_id'])
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Mark messages from a user as read.
     */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
        ]);

        Message::where('sender_id', $validated['sender_id'])
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }
}
