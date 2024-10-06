<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification; // Import the notification class
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Send Message Request', $request->all());

        // Validate the request
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:65535',
        ]);

        // Create a new message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ]);

        // Notify the recipient of the new message
        $recipient = User::find($message->receiver_id);
        Notification::send($recipient, new NewMessageNotification($message));

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function markAsRead($messageId)
    {
        $message = Message::find($messageId);

        if (!$message || $message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Message not found or unauthorized.'], 404);
        }

        // Update read_at when the receiver reads the message
        if ($message->read_at === null) {
            $message->read_at = now();
            $message->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read.',
        ]);
    }

    public function index()
    {
        // Fetch messages for the logged-in user
        $messages = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch confirmed friends without last_seen_at
        $confirmedFriends = DB::table('friend_user')
            ->join('users', 'friend_user.friend_id', '=', 'users.id')
            ->where('friend_user.user_id', Auth::id())
            ->where('friend_user.status', 'confirmed')
            ->select('users.id', 'users.name') // Removed last_seen_at
            ->get();

        // Fetch recent conversations
        $recentConversations = Message::select('receiver_id', 'sender_id', 'content', 'created_at')
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->groupBy('receiver_id', 'sender_id', 'content', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('messages.index', [
            'messages' => $messages,
            'confirmedFriends' => $confirmedFriends,
            'recentConversations' => $recentConversations,
        ]);
    }

    public function chat($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('messages.index')->withErrors('User not found.');
        }

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return view('messages.chat', [
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function retrieve($userId)
    {
        if (!User::find($userId)) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Get the latest message time for each conversation
        $recentConversations = Message::select('sender_id', 'receiver_id', DB::raw('MAX(created_at) as latest_message_time'))
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->groupBy('sender_id', 'receiver_id')
            ->orderBy('latest_message_time', 'desc')
            ->take(5)
            ->get();

        // Fetch the latest messages based on the results of the first query
        $recentMessages = Message::where(function ($query) use ($recentConversations) {
            foreach ($recentConversations as $conversation) {
                $query->orWhere(function ($query) use ($conversation) {
                    $query->where('sender_id', $conversation->sender_id)
                          ->where('receiver_id', $conversation->receiver_id)
                          ->orWhere(function ($query) use ($conversation) {
                              $query->where('sender_id', $conversation->receiver_id)
                                    ->where('receiver_id', $conversation->sender_id);
                          });
                });
            }
        })->orderBy('created_at', 'desc')->take(5)->get(); // Get the latest 5 messages for those conversations

        return response()->json([
            'success' => true,
            'messages' => $recentMessages,
        ]);
    }

    public function search(Request $request)
    {
        \Log::info('Search Query:', ['query' => $request->input('query')]);
        $query = $request->input('query');
    
        // Fetch all users matching the search query
        $users = User::where('name', 'LIKE', '%' . $query . '%')
            ->get(['id', 'name']); // Removed last_seen_at and any restrictions
    
        return response()->json($users);
    }
    
}
