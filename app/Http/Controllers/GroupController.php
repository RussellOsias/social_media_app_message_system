<?php 

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupPost;
use App\Models\GroupComment;
use App\Models\GroupLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $groups = Group::with('users')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->get();

        return view('groups.index', compact('groups', 'search'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ]);

        $group->users()->attach(Auth::id());

        return redirect()->route('groups.index')->with('success', 'Group created successfully!');
    }

    public function show(Group $group)
    {
        $posts = $group->posts()->with('user', 'comments.user')->get(); 
        return view('groups.show', compact('group', 'posts'));
    }

    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        if (!$group->users()->where('user_id', $request->user_id)->exists()) {
            $group->users()->attach($request->user_id);
            return redirect()->back()->with('success', "{$user->name} has been added to the group.");
        }

        return redirect()->back()->with('error', 'User is already in the group.');
    }

    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user->id);
        return redirect()->back()->with('success', "{$user->name} has been removed from the group.");
    }

    public function addUserForm(Group $group)
    {
        $group->load(['users', 'pendingRequests']); // Eager load relationships
        $users = User::where('id', '!=', Auth::id())->get(); 
        return view('groups.add_user', compact('group', 'users'));
    }

    public function storePost(Request $request, Group $group)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = GroupPost::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'group_id' => $group->id,
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Post created successfully!');
    }

    public function storeComment(Request $request, GroupPost $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = GroupComment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'group_post_id' => $post->id,
        ]);

        return redirect()->route('groups.show', $post->group)->with('success', 'Comment added successfully!');
    }

    public function likePost(Request $request, GroupPost $post)
    {
        if ($post->likes()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('groups.show', $post->group)->with('info', 'You already liked this post.');
        }

        GroupLike::create([
            'user_id' => Auth::id(),
            'group_post_id' => $post->id,
        ]);

        return redirect()->route('groups.show', $post->group)->with('success', 'Post liked successfully!');
    }

    public function joinRequest(Request $request, Group $group)
    {
        // Assuming the user is authenticated
        $userId = auth()->id();
    
        // Check if the user is already a member or has a pending request
        if ($group->users->contains($userId) || $group->pendingRequests->contains('user_id', $userId)) {
            return redirect()->back()->with('error', 'You are already a member or have a pending request.');
        }
    
        // Create a new pending request
        $group->pendingRequests()->create([
            'user_id' => $userId, // Use the authenticated user's ID
            'group_id' => $group->id,
        ]);
    
        return redirect()->back()->with('success', 'Your request to join the group has been submitted.');
    }
    

    public function approveRequest(Group $group, User $user)
    {
        $group->users()->attach($user);
        $group->pendingRequests()->where('user_id', $user->id)->delete();
        return redirect()->back()->with('success', "{$user->name} has been approved.");
    }

    public function denyRequest(Group $group, User $user)
    {
        $group->pendingRequests()->where('user_id', $user->id)->delete();
        return redirect()->back()->with('error', "{$user->name} has been denied.");
    }
    // Add at the top with the other methods
public function edit(Group $group)
{
    // Get all users except the current owner for ownership transfer
    $users = User::where('id', '!=', $group->owner_id)->get();
    return view('groups.edit', compact('group', 'users'));
}

public function update(Request $request, Group $group)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'owner_id' => 'nullable|exists:users,id', // Transfer ownership is optional
    ]);

    // Update the group name
    $group->update(['name' => $request->name]);

    // Transfer ownership if a new owner is selected
    if ($request->owner_id) {
        $group->owner_id = $request->owner_id;
        $group->save();
    }

    return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
}

public function destroy(Group $group)
{
    $group->delete();
    return redirect()->route('groups.index')->with('success', 'Group deleted successfully!');
}

    
}
