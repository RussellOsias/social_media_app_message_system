<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification; // Import the notification class
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation; // Add this line if not present
class MessageController extends Controller
{
    public function send(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'nullable|string', // Make content optional for media messages
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi|max:307200', // Adjust max size as needed
        ]);
    
        // Check if the content is null
        if (is_null($validated['content']) && !$request->hasFile('media')) {
            return response()->json(['error' => 'Message content cannot be empty.'], 400);
        }
    
        // Create a new message instance
        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $validated['receiver_id'];
        $message->content = $validated['content'] ?? '';
    
        // Handle media file upload
        if ($request->hasFile('media')) {
            // Store the media file
            $path = $request->file('media')->store('media', 'public');
            $filename = basename($path); // Get the filename
            $message->media_url = $filename; // Save the filename in media_url
    
            // Determine the media type
            $mimeType = $request->file('media')->getClientMimeType();
            $message->mediaType = str_contains($mimeType, 'video') ? 'video' : 'photo'; // Set media type
        } else {
            $message->mediaType = 'text'; // Default to text if no media
        }
    
        // Save the message to the database
        $message->save();
    
        // Notify the recipient of the new message
        $recipient = User::find($message->receiver_id);
        Notification::send($recipient, new NewMessageNotification($message));
    
        return response()->json(['success' => 'Message sent successfully.', 'message' => $message], 201);
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
    
        // Fetch confirmed friends
        $confirmedFriends = DB::table('friend_user')
            ->join('users', 'friend_user.friend_id', '=', 'users.id')
            ->where('friend_user.user_id', Auth::id())
            ->where('friend_user.status', 'confirmed')
            ->select('users.id', 'users.name')
            ->get();
    
        // Fetch recent conversations
        $recentConversations = Message::select('id', 'receiver_id', 'sender_id', 'content', 'created_at')
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->groupBy('receiver_id', 'sender_id', 'content', 'created_at', 'id')
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
        })->orderBy('created_at', 'desc')->take(5)->get();

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
            ->get(['id', 'name']);
    
        return response()->json($users);
    }

    public function edit(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:65535']);

        $message = Message::find($id);

        if (!$message || $message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Message not found or unauthorized.'], 404);
        }

        $message->content = $request->input('content');
        $message->save();

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function destroy($id)
    {
        $message = Message::find($id);

        if (!$message || $message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Message not found or unauthorized.'], 404);
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'Message deleted.']);
    }

    public function deleteAllMessagesWithUser($userId)
    {
        // Check if the user exists
        if (!User::find($userId)) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Delete all messages between the logged-in user and the specified user
        Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
        })->delete();

        return redirect()->route('messages.index')->with('success', 'All messages with the user deleted successfully.');
    }

    private function uploadMedia($media)
    {
        // Generate a unique file name
        $filename = time() . '_' . $media->getClientOriginalName();
        
        // Store the file in the public storage path
        $media->storeAs('media', $filename, 'public'); // Assuming you're using public disk

        // Return the file name without 'media/' prefix
        return $filename; // Return just the file name for the database
    }

    
}
