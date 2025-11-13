<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();
        
        $conversations = Message::where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($userId) {
                return $message->from_user_id == $userId ? $message->to_user_id : $message->from_user_id;
            })
            ->map(function($messages) use ($userId) {
                $otherUser = $messages->first()->from_user_id == $userId 
                    ? $messages->first()->toUser 
                    : $messages->first()->fromUser;
                $unreadCount = $messages->where('to_user_id', $userId)->where('is_read', false)->count();
                return [
                    'user' => $otherUser,
                    'last_message' => $messages->first(),
                    'unread_count' => $unreadCount,
                ];
            })
            ->sortByDesc(function($conversation) {
                return $conversation['last_message']->created_at;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $currentUser = auth()->user();
        
        $messages = Message::where(function($query) use ($currentUser, $user) {
            $query->where('from_user_id', $currentUser->id)
                  ->where('to_user_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('from_user_id', $user->id)
                  ->where('to_user_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('from_user_id', $user->id)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return redirect()->route('messages.show', $user->id)->with('success', 'Message sent!');
    }

    public function unreadCount()
    {
        $count = Message::where('to_user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
