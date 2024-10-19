<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md flex justify-center items-center">
            <h2 class="text-white text-2xl font-semibold flex items-center">
                <span class="mr-2">👤</span> {{ $user->name }}'s Profile
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-black text-white p-6 rounded-lg shadow-md">
        <!-- Profile Picture Section -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}'s Profile Picture" class="w-full max-w-[400px] h-auto rounded-full object-cover shadow-lg">
        </div>

        <div class="flex flex-col justify-center items-center mb-4 text-center">
            <h3 class="text-4xl font-bold">{{ $user->name }} <span class="ml-2">👋</span></h3>
            <p class="text-lg italic text-gray-400 mt-2">"{{ $user->bio ?? 'No bio available' }}"</p>
        </div>

        <!-- User Details Section -->
        <div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
            <p class="text-lg flex items-center">
                <span class="font-semibold">📧 Email:</span> <span class="ml-2">{{ $user->email }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🎂 Birthday:</span> <span class="ml-2">{{ $user->birthday ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">💼 Occupation:</span> <span class="ml-2">{{ $user->occupation ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">⚧️ Gender:</span> <span class="ml-2">{{ $user->gender ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🏠 Address:</span> <span class="ml-2">{{ $user->address ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🌍 Nationality:</span> <span class="ml-2">{{ $user->nationality ?? 'Not provided' }}</span>
            </p>
        </div>
 <!-- Send Message Button -->
 <div class="mt-6 flex justify-center">
            <a href="{{ route('messages.chat', ['userId' => $user->id]) }}" class="bg-red-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-red-700">
                ✉️ Send Message
            </a>
        </div>
    </div>
      <!-- Friends and Social Section -->
<div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold">👥 Friends</h3>
    <div class="flex flex-wrap justify-center space-x-4">
        @forelse ($user->friends as $friend)
            <div class="text-center">
                <!-- Make the profile picture clickable -->
                <a href="{{ route('profile.show', $friend->id) }}">
                    <img src="{{ asset('storage/' . $friend->profile_picture) }}" alt="{{ $friend->name }}'s Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-lg">
                </a>
                <!-- Make the friend's name clickable as well -->
                <a href="{{ route('profile.show', $friend->id) }}" class="text-sm text-blue-500 hover:underline">
                    {{ $friend->name }}
                </a>
            </div>
        @empty
            <p class="text-sm text-gray-400">No friends found.</p>
        @endforelse
    </div>
</div>
<!-- Recent Posts Section -->
<div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold">📝 View Posts</h3>
    <div class="space-y-4">
        @forelse ($user->posts as $post)
            <div class="bg-gray-700 p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                <div class="flex justify-between items-center mb-2">
                    <small class="text-gray-400">
                        <span class="text-xl font-bold text-gray-200">{{ $post->user->name }}</span>
                        <span class="text-gray-500"> on {{ $post->created_at->format('F j, Y, g:i a') }}</span>
                    </small>

                    <!-- Three Dots Menu for Edit/Delete -->
                    @if ($post->user_id === Auth::id())
                        <div class="relative">
                            <button class="text-gray-400 hover:text-white" onclick="toggleMenu({{ $post->id }})">⋮</button>
                            <div id="menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-gray-800 rounded-lg shadow-lg z-10">
                                <ul class="text-gray-300 text-sm">
                                    <li>
                                        <button onclick="enableEditPost({{ $post->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded-t-lg">✏️ Edit</button>
                                    </li>
                                    <li>
                                        <button onclick="deletePost({{ $post->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded-b-lg">🗑️ Delete</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <p class="text-lg text-gray-100 mb-4">{{ $post->content }}</p>

                <!-- Display Media: Photo -->
                @if ($post->mediaType === 'photo' && $post->media_url)
                    <div class="flex justify-center">
                        <img src="{{ asset('storage/media/' . $post->media_url) }}" alt="Uploaded photo" class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg mt-4 object-contain">
                    </div>
                @endif

                <!-- Display Media: Video -->
                @if ($post->mediaType === 'video' && $post->media_url)
                    <div class="flex justify-center">
                        <video controls class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg mt-4">
                            <source src="{{ asset('storage/media/' . $post->media_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif

            

                
                <!-- Display Comments -->
                <ul class="mt-2 border-t border-gray-600 pt-2">
                    @foreach ($post->comments as $comment)
                        <li class="mt-2 bg-gray-800 p-3 rounded-lg shadow-md">
                            <small>
                                <span class="text-lg font-bold text-gray-300">{{ $comment->user->name }}</span>
                                <span class="text-gray-500"> on {{ $comment->created_at->format('F j, Y, g:i a') }}</span>
                            </small>
                            <p class="mt-1">{{ $comment->comment }}</p>
                            @if ($comment->mediaType === 'photo' && $comment->media_url)
                                <img src="{{ asset('storage/media/' . $comment->media_url) }}" alt="Comment photo" class="w-64 h-64 rounded-lg object-cover mt-2">
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-gray-400">No posts available.</p>
        @endforelse
    </div>
</div>

       
</x-app-layout>
