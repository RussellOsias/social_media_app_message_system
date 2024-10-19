<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Notifications\PostLikedNotification;
use App\Notifications\CommentAddedNotification;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4|max:307200', // Adjust max size to 300MB (307200 KB)
        ]);
    
        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = auth()->id();
    
        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('media', 'public');
            $filename = basename($path);
            $post->media_url = $filename;
            $mimeType = $request->file('file')->getClientMimeType();
            $post->mediaType = str_contains($mimeType, 'video') ? 'video' : 'photo';
        }
    
        $post->save();
    
        return response()->json($post, 201);
    }

    public function index()
    {
        $authUserId = Auth::id();
        $posts = Post::with('user', 'comments.user', 'likes')->latest()->get();
        $filteredPosts = $posts->filter(function($post) use ($authUserId) {
            if ($post->user_id == $authUserId) {
                return true;
            }
            switch ($post->visibility) {
                case 'Public':
                    return true;
                case 'Friends':
                    return $post->user->friends()->where('friend_id', $authUserId)->where('status', 'confirmed')->exists();
                case 'Only me':
                    return false;
                default:
                    return false;
            }
        });

        foreach ($filteredPosts as $post) {
            $post->userHasLiked = $post->likes()->where('user_id', $authUserId)->exists();
            $post->likes_count = $post->likes()->count();
        }

        return response()->json($filteredPosts);
    }

    public function destroy($id)
    {
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
            $liker = Auth::user();
            $post->user->notify(new PostLikedNotification($post, $liker));
            return response()->json(['message' => 'Post liked']);
        }
    }

    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4|max:307200', // Allow file upload for comments
        ]);
    
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
    
        // Handle file upload for comments
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('media', 'public');
            $filename = basename($path);
            $comment->media_url = $filename;
            $mimeType = $request->file('file')->getClientMimeType();
            $comment->mediaType = str_contains($mimeType, 'video') ? 'video' : 'photo'; // Fixed line
        }
    
        $comment->save();
        $commenter = Auth::user();
        $post->user->notify(new CommentAddedNotification($post, $comment, $commenter));
    
        return response()->json($comment, 201);
    }
    
    public function edit(Request $request, $id)
    {
        $request->validate(['content' => 'required|max:255']);
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $post->content = $request->content;
        $post->save();
        return response()->json($post);
    }

    public function editComment(Request $request, $postId, $commentId)
    {
        $request->validate(['comment' => 'required|string|max:255']);
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $comment->comment = $request->comment;
        $comment->save();
        return response()->json($comment);
    }

    public function deleteComment($postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
