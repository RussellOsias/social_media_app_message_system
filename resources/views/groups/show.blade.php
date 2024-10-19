<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-3xl font-bold text-center">{{ $group->name }}</h2>
        <p class="text-gray-200 text-center mt-2 font-semibold">Owned by: {{ optional($group->owner)->name ?? 'Unknown' }}</p>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-900 text-white p-8 rounded-lg shadow-lg mt-6">
        <h2 class="text-xl font-semibold text-gray-300 mb-6">Posts</h2>

        <!-- Check if the authenticated user is a member of the group -->
        @if ($group->users->contains(auth()->id()))
            <!-- Form for adding new posts -->
            <form action="{{ route('groups.storePost', $group) }}" method="POST" class="mb-6">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="w-full border border-gray-600 bg-gray-800 text-white p-4 rounded-lg shadow-md" rows="4" placeholder="What's on your mind?" required></textarea>
                </div>
                <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 shadow-md">Post</button>
            </form>
        @else
            <p class="text-gray-300 mb-4">You must be a member of this group to post.</p>
        @endif

        @if ($posts->isEmpty())
            <p class="text-gray-300">No posts yet. Be the first to post!</p>
        @else
            @foreach ($posts->sortByDesc('created_at') as $post) <!-- Sort posts by newest first -->
                <div class="mb-6 bg-gray-800 p-4 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <div class="flex justify-between items-center mb-2">
                        <h5 class="font-semibold text-lg {{ $post->user_id === auth()->id() ? 'text-red-500' : 'text-blue-400' }}">{{ optional($post->user)->name ?? 'Unknown' }}</h5>
                        <span class="text-gray-400 text-sm">{{ $post->likes()->count() }} Like{{ $post->likes()->count() !== 1 ? 's' : '' }}</span>
                    </div>
                    <p class="text-gray-300 mb-2">{{ $post->content }}</p>
                    <p class="text-gray-500 text-xs mb-2"><small>Posted on {{ $post->created_at->format('M d, Y H:i') }}</small></p>

                    <!-- Like button -->
                    <form action="{{ route('groups.posts.like', $post) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="text-blue-500 hover:underline transition duration-200">
                            <span role="img" aria-label="like">👍</span> Like
                        </button>
                    </form>

                    <h6 class="mt-4 text-gray-300 font-semibold">Comments:</h6>
                    
                    <!-- Check if the authenticated user is a member of the group for commenting -->
                    @if ($group->users->contains(auth()->id()))
                        <form action="{{ route('groups.comments.store', $post) }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="form-group mb-3">
                                <input type="text" name="content" class="w-full border border-gray-600 bg-gray-700 text-white p-2 rounded-lg" placeholder="Add a comment" required>
                            </div>
                            <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md">Comment</button>
                        </form>
                    @else
                        <p class="text-gray-300 mb-4">You must be a member of this group to comment.</p>
                    @endif

                    <div class="mt-2">
                        @foreach ($post->comments as $comment)
                            <div class="border p-2 mb-1 bg-gray-700 rounded-lg">
                                <strong class="text-green-400">{{ optional($comment->user)->name ?? 'Unknown' }}</strong>: 
                                <span class="text-green-300">{{ $comment->content }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
