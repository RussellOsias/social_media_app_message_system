<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('Story') }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md" x-data="{ showModal: false }">
        <div class="bg-gray-700 p-4 rounded-lg shadow-md mb-6 border border-gray-600">
            <h3 class="text-gray-400 text-xl mb-4">📸 Story Details</h3>
            <div class="relative" id="currentStory">
                @if ($story) <!-- Ensure that story data exists -->
                    <div class="absolute top-2 left-2 flex items-center z-10">
                        <!-- Display the profile picture of the story's author overlapping the story -->
                        <img src="{{ asset('storage/' . $story->user->profile_picture) }}" alt="{{ $story->user->name }}'s Profile Picture" class="rounded-full w-20 h-20 object-cover border-2 border-gray-800"> <!-- Further increased size -->
                        <p class="text-gray-300 text-2xl font-bold ml-2">{{ $story->user->name }}</p> <!-- Increased user name size -->
                    </div>

                    <div class="absolute top-2 right-2">
                        <!-- Button to open the delete confirmation modal only for the story owner -->
                        @if ($story->user_id === Auth::id())
                            <button @click="showModal = true" class="bg-red-600 hover:bg-red-700 text-white py-1 px-2 rounded">
                                Delete
                            </button>
                        @endif

                        <!-- Modal (Hidden by default, shows when "showModal" is true) -->
                        <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
                            <div class="bg-black bg-opacity-50 absolute inset-0"></div>
                            <div class="bg-white p-6 rounded-lg shadow-lg z-10">
                                <h2 class="text-xl text-gray-800 mb-4">Are you sure you want to delete this story?</h2>
                                <form action="{{ route('story.delete', $story->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex justify-end space-x-4">
                                        <!-- Close Modal Button -->
                                        <button @click.prevent="showModal = false" type="button" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                                            Cancel
                                        </button>
                                        <!-- Confirm Delete Button -->
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                            Confirm Delete
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if ($story->mediaType === 'photo')
                        <img src="{{ asset('storage/media/' . $story->media_url) }}" class="w-full h-auto rounded-lg" alt="Story Image">
                    @elseif ($story->mediaType === 'video')
                        <video src="{{ asset('storage/media/' . $story->media_url) }}" class="w-full h-auto rounded-lg" controls autoplay muted></video>
                    @endif
                @else
                    <p class="text-gray-400">No story found.</p>
                @endif
            </div>
        </div>

        <h3 class="text-gray-400 text-xl mb-4">Other Stories</h3>
        <div class="flex flex-wrap space-x-4 overflow-x-auto no-scrollbar">
            @foreach ($stories as $userStory)
                @if ($userStory->user_id === $selectedUser->id) <!-- Filter stories for the selected user -->
                    <div class="flex-shrink-0 w-24 h-40 bg-gray-600 rounded-lg shadow-md p-2 mb-4 relative">
                        <a href="{{ route('stories.show', $userStory->id) }}" class="flex flex-col h-full">
                            @if ($userStory->mediaType === 'photo')
                                <img src="{{ asset('storage/media/' . $userStory->media_url) }}" class="w-full h-32 rounded-lg object-cover mb-2" alt="Story Image">
                            @elseif ($userStory->mediaType === 'video')
                                <video src="{{ asset('storage/media/' . $userStory->media_url) }}" class="w-full h-32 rounded-lg mb-2" autoplay muted loop></video>
                            @endif
                            <!-- Display the profile picture and name for other stories -->
                            <div class="absolute bottom-2 left-2 flex items-center z-10">
                                  </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>
