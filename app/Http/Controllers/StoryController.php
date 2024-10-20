<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    // Store a new story
    public function store(Request $request)
    {
        try {
            $request->validate([
                'content' => 'nullable|string',
                'media' => 'nullable|file|mimes:jpeg,jpg,png,mp4|max:20480' // 20MB max
            ]);

            $story = new Story();
            $story->user_id = Auth::id();
            $story->content = $request->content;

            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $path = $file->store('media', 'public');
                $story->media_url = basename($path);  // Store only the filename
                $story->mediaType = $file->getClientOriginalExtension() === 'mp4' ? 'video' : 'photo'; // Use mediaType
            } else {
                $story->mediaType = 'text';
            }

            $story->save();

            return response()->json(['message' => 'Story uploaded successfully.']);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Story upload error: ' . $e->getMessage());
            return response()->json(['message' => 'Error uploading story.'], 500);
        }
    }

    // Show all stories
    public function index()
    {
        $stories = Story::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($stories);
    }

    // Show user's stories
    public function showUserStories()
    {
        $stories = Story::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json($stories);
    }

    // Delete a specific story
    public function delete($id)
    {
        $story = Story::findOrFail($id);
    
        // Ensure the authenticated user is the owner of the story
        if ($story->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
    
        // Delete the media file if exists
        if ($story->media_url) {
            Storage::disk('public')->delete('media/' . $story->media_url);
        }
    
        $story->delete();
    
        // Redirect to the dashboard after deletion
        return redirect()->route('dashboard')->with('message', 'Story deleted successfully.');
    }
    

    // (Optional) Remove expired stories
    public function removeExpiredStories()
    {
        $expiredStories = Story::where('expires_at', '<=', now())->get();
        foreach ($expiredStories as $story) {
            // Delete the media file if exists
            if ($story->media_url) {
                Storage::disk('public')->delete('media/' . $story->media_url);
            }
            $story->delete();
        }
    }

    public function show($id)
    {
        $story = Story::with('user')->findOrFail($id); // Get the specific story with the user who created it
        $stories = Story::with('user')
                        ->where('user_id', $story->user_id) // Get only stories from the same user
                        ->orderBy('created_at', 'desc')
                        ->get();
        $selectedUser = $story->user; // Get the user who owns the story
    
        return view('story', compact('story', 'stories', 'selectedUser')); // Pass the selected user to the view
    }
    

    // Display all stories
    public function showStories()
    {
        // Optionally remove expired stories before showing the list
        $this->removeExpiredStories();

        // Fetch remaining stories
        $stories = Story::where('expires_at', '>', now())->get();
        return view('stories.index', compact('stories'));
    }
}
