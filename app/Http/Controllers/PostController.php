<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Notifications\PostLikedNotification;
use App\Notifications\CommentAddedNotification;

class PostController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $post = new Post();
        $post->content = $request->content;
        $post->user_id = Auth::id();
        $post->visibility = $request->visibility;
        $post->save();

        return response()->json($post, 201);
    }

    public function index()
{
    $authUserId = Auth::id();

    // Fetch posts with user and comments relationships
    $posts = Post::with('user', 'comments.user', 'likes')->latest()->get();

    // Filter posts based on visibility and user
    $filteredPosts = $posts->filter(function($post) use ($authUserId) {
        if ($post->user_id == $authUserId) {
            return true; // The post belongs to the authenticated user
        }

        switch ($post->visibility) {
            case 'Public':
                return true; // Everyone can see public posts
            case 'Friends':
                return $post->user->friends()->where('friend_id', $authUserId)->where('status', 'confirmed')->exists();
            case 'Only me':
                return false; // Only the post owner can see "only me" posts
            default:
                return false; // Default to hidden if visibility is not set properly
        }
    });

    // Add the userHasLiked property for the posts the user has liked
    foreach ($filteredPosts as $post) {
        $post->userHasLiked = $post->likes()->where('user_id', $authUserId)->exists();
        $post->likes_count = $post->likes()->count(); // Fetching the count of likes for each post
    }

    return response()->json($filteredPosts);
}

    
    public function destroy($id) {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function likePost(Post $post)
    {
        $existingLike = $post->likes()->where('user_id', Auth::id())->first();
    
        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
            return response()->json(['message' => 'Post unliked']);
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
            $post->increment('likes_count');
    
            // Notify the post owner
            $liker = Auth::user(); // Get the current user who liked the post
            $post->user->notify(new PostLikedNotification($post, $liker));
    
            return response()->json(['message' => 'Post liked']);
        }
    }
    
    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
    
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->save();
    
        // Notify the post owner
        $commenter = Auth::user(); // Get the current user who commented
        $post->user->notify(new CommentAddedNotification($post, $comment, $commenter));
    
        return response()->json($comment, 201);
    }

    public function edit(Request $request, $id) {
        $request->validate([
            'content' => 'required|max:255',
        ]);
    
        $post = Post::findOrFail($id);
    
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $post->content = $request->content;
        $post->save();
    
        return response()->json($post);
    }
    
    public function editComment(Request $request, $postId, $commentId) {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
    
        $comment = Comment::findOrFail($commentId);
    
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->comment = $request->comment;
        $comment->save();
    
        return response()->json($comment);
    }
    
    public function deleteComment($postId, $commentId) {
        $comment = Comment::findOrFail($commentId);
    
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
    
    public function destroyComment($postId, $commentId) {
        $comment = Comment::findOrFail($commentId);
    
        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
