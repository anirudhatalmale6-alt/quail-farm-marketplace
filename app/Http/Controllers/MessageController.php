<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $conversations = $this->getConversations(Auth::id());
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

        // Mark unread messages from the other user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Load conversations for the sidebar
        $conversations = $this->getConversations($authId);

        return view('messages.show', compact('messages', 'otherUser', 'conversations'));
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

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'subject' => $validated['subject'] ?? null,
            'body' => $validated['body'],
            'order_id' => $validated['order_id'] ?? null,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'subject' => $message->subject,
                    'created_at' => $message->created_at->toISOString(),
                    'read_at' => null,
                ],
            ]);
        }

        return redirect()->route('messages.show', $validated['receiver_id'])
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Fetch new messages for AJAX polling.
     */
    public function getMessages($userId)
    {
        $authId = Auth::id();
        $since = (int) request('since', 0);

        $newMessages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->where('id', '>', $since)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark incoming messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Find the latest read message ID for auth user's sent messages
        $latestReadId = Message::where('sender_id', $authId)
            ->where('receiver_id', $userId)
            ->whereNotNull('read_at')
            ->max('id');

        return response()->json([
            'messages' => $newMessages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'subject' => $msg->subject,
                    'created_at' => $msg->created_at->toISOString(),
                    'read_at' => $msg->read_at ? $msg->read_at->toISOString() : null,
                ];
            })->values(),
            'read_up_to' => $latestReadId,
        ]);
    }

    /**
     * Return total unread message count as JSON.
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
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

    /**
     * Build the conversations list for a given user.
     */
    private function getConversations($userId)
    {
        return Message::where('sender_id', $userId)
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
            ->filter(fn($conv) => $conv->partner !== null)
            ->values();
    }
}
