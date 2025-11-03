<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display conversations for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all unique conversations (people user has messaged or been messaged by)
        $conversations = Message::where(function($query) use ($user) {
                $query->where('from_user_id', $user->id)
                      ->orWhere('to_user_id', $user->id);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($user) {
                // Group by the other user in the conversation
                return $message->from_user_id == $user->id 
                    ? $message->to_user_id 
                    : $message->from_user_id;
            })
            ->map(function($messages, $otherUserId) use ($user) {
                $otherUser = User::find($otherUserId);
                if (!$otherUser) return null;
                
                $latestMessage = $messages->first();
                $unreadCount = $messages->where('to_user_id', $user->id)
                    ->where('is_read', false)
                    ->count();
                
                return [
                    'user' => $otherUser,
                    'latest_message' => $latestMessage,
                    'unread_count' => $unreadCount,
                ];
            })
            ->filter()
            ->values();

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Ensure user can only view conversations they're part of
        if (!$currentUser->isAdmin() && $user->isAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'You can only view messages from admins.');
        }

        // Mark messages as read FIRST (before getting messages)
        // This ensures they're marked before the view renders
        $updatedCount = Message::where('from_user_id', $user->id)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Get all messages between current user and selected user
        $messages = Message::where(function($query) use ($currentUser, $user) {
                $query->where('from_user_id', $currentUser->id)
                      ->where('to_user_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('from_user_id', $user->id)
                      ->where('to_user_id', $currentUser->id);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('messages', 'user'));
    }

    /**
     * Send a message
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        // Admins can message anyone, users can only message admins or reply
        $currentUser = Auth::user();
        
        if (!$currentUser->isAdmin()) {
            // Regular users can only message admins
            if (!$user->isAdmin()) {
                abort(403, 'You can only send messages to administrators.');
            }
        }

        Message::create([
            'from_user_id' => $currentUser->id,
            'to_user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return redirect()->route('messages.show', $user)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Get unread message count for notifications
     */
    public function unreadCount()
    {
        $count = Message::where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
