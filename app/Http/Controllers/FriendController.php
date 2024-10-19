<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FriendRequestNotification;
use App\Notifications\FriendAcceptedNotification; 
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    // Display friends, pending requests, and suggested friends
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get the search input
        $search = $request->input('search');

        // Suggested friends: Users who are not the current user, are not friends, and have not sent requests
        $suggestedFriends = User::where('id', '!=', $user->id)
            ->whereDoesntHave('friends', function ($query) use ($user) {
                $query->where('friend_id', $user->id);
            })
            ->whereDoesntHave('friendRequestsSent', function ($query) use ($user) {
                $query->where('friend_id', $user->id);
            });

        // If a search term is provided, filter suggested friends
        if ($search) {
            $suggestedFriends->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('bio', 'LIKE', "%{$search}%");
            });
        }

        $suggestedFriends = $suggestedFriends->get();

        // Pending requests: Only requests where the current user is the receiver
        $pendingRequests = DB::table('friend_user')
            ->where('user_id', $user->id) // User 2's ID
            ->where('initiator_id', '!=', $user->id) // Exclude requests initiated by User 2
            ->where('status', 'pending') // Only pending requests
            ->get();

        // Load initiator details
        $pendingRequests = $pendingRequests->map(function ($request) {
            $request->initiator = User::find($request->initiator_id);
            return $request;
        });

        // Current friends: Users who are confirmed friends
        $friends = $user->friends;

        return view('friends', compact('suggestedFriends', 'pendingRequests', 'friends', 'search'));
    }

    // Other methods remain unchanged...

    // Send a friend request
    public function addFriend(Request $request, $friendId)
    {
        $user = Auth::user();

        // Check if a friendship already exists
        if ($user->friends()->where('friend_id', $friendId)->exists() || 
            $user->friendRequestsSent()->where('friend_id', $friendId)->exists()) {
            return redirect()->back()->with('message', 'Friend request already sent or user is already your friend.');
        }

        \Log::info("Friend request sent from user {$user->id} to user {$friendId}");

        // Create two pending friend requests
        DB::table('friend_user')->insert([
            [
                'user_id' => $user->id,
                'friend_id' => $friendId,
                'status' => 'pending',
                'friendship_type' => 'friend',
                'initiator_id' => $user->id,
                'initiated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $friendId,
                'friend_id' => $user->id,
                'status' => 'pending',
                'friendship_type' => 'friend',
                'initiator_id' => $user->id,
                'initiated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Notify the friend about the request
        $friend = User::findOrFail($friendId);
        $friend->notify(new FriendRequestNotification($user));

        return redirect()->back()->with('message', 'Friend request sent!');
    }

    // Accept a pending friend request
    public function acceptFriend($friendId)
    {
        $user = Auth::user();

        // Check if a pending request exists where the current user is not the initiator
        $pendingFriendship = DB::table('friend_user')
            ->where('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->where('initiator_id', '!=', $user->id)
            ->first();

        if ($pendingFriendship) {
            DB::transaction(function () use ($user, $friendId) {
                // Update both records to confirmed
                DB::table('friend_user')
                    ->where('user_id', $user->id)
                    ->where('friend_id', $friendId)
                    ->update(['status' => 'confirmed', 'updated_at' => now()]);

                DB::table('friend_user')
                    ->where('user_id', $friendId)
                    ->where('friend_id', $user->id)
                    ->update(['status' => 'confirmed', 'updated_at' => now()]);

                // Notify both users about the new friendship
                $friend = User::findOrFail($friendId);
                $friend->notify(new FriendAcceptedNotification($user));
                $user->notify(new FriendAcceptedNotification($friend));
            });

            return redirect()->back()->with('message', 'Friend request accepted.');
        }

        return redirect()->back()->with('error', 'No pending friend request found.');
    }

    // Cancel a pending friend request
    public function cancelFriendRequest($friendId)
    {
        $user = Auth::user();

        if (DB::table('friend_user')->where([
            ['user_id', $user->id], ['friend_id', $friendId], ['status', 'pending']
        ])->delete()) {
            return redirect()->back()->with('message', 'Friend request cancelled.');
        }

        return redirect()->back()->with('error', 'No pending friend request found.');
    }

    // Reject a friend request
    public function rejectFriend($friendId)
    {
        $user = Auth::user();

        // Start a transaction to ensure both deletions succeed or fail together
        DB::transaction(function () use ($user, $friendId) {
            // Delete the incoming friend request for the user rejecting the request
            DB::table('friend_user')
                ->where([
                    ['friend_id', $user->id],
                    ['user_id', $friendId],
                    ['status', 'pending']
                ])->delete();

            // Delete the outgoing friend request for the user who sent the request
            DB::table('friend_user')
                ->where([
                    ['user_id', $user->id],
                    ['friend_id', $friendId],
                    ['status', 'pending']
                ])->delete();
        });

        return redirect()->back()->with('message', 'Friend request rejected.');
    }

    // Unfriend a user
    public function unfriend($friendId)
    {
        $user = Auth::user();

        $deleted = DB::table('friend_user')->where(function ($query) use ($user, $friendId) {
            $query->where([
                ['user_id', $user->id],
                ['friend_id', $friendId]
            ])->orWhere([
                ['user_id', $friendId],
                ['friend_id', $user->id]
            ]);
        })->delete();

        if ($deleted) {
            return redirect()->back()->with('message', 'You have unfriended the user.');
        }

        return redirect()->back()->with('error', 'Failed to unfriend the user.');
    }

    // Fetch friends for messaging
    public function getFriends()
    {
        $user = Auth::user();
        $friends = $user->friends; // Fetch confirmed friends using the relationship method

        return response()->json($friends); // Return as JSON for API response
    }
}
