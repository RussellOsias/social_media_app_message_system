<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('Messages') }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md mt-6">
        <div class="mb-4">
            <h3 class="text-lg font-semibold mb-2">👥Friends</h3>
            <div class="space-y-2">
                @foreach($confirmedFriends as $friend)
                    <a href="{{ route('messages.chat', $friend->id) }}" class="flex items-center bg-gray-700 p-4 rounded-lg shadow-md border border-gray-600 transition-transform transform hover:scale-105 hover:bg-gray-600">
                        <div class="flex-1">
                            <span class="block font-medium">{{ $friend->name }}</span>
                            <!-- Remove last_seen_at information -->
                        </div>
                        <div class="flex-shrink-0">
                            <span class="bg-green-500 text-white rounded-full px-2 py-1 text-xs">Online</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">🕒 Recent Conversations</h3>
            <div class="space-y-2">
                @foreach($recentConversations as $conversation)
                    <a href="{{ route('messages.chat', $conversation->receiver_id === Auth::id() ? $conversation->sender_id : $conversation->receiver_id) }}" class="flex items-center bg-gray-700 p-4 rounded-lg shadow-md border border-gray-600 transition-transform transform hover:scale-105 hover:bg-gray-600">
                        <div class="flex-1">
                            <span class="block font-medium">{{ $conversation->sender_id === Auth::id() ? $conversation->receiver->name : $conversation->sender->name }}</span>
                            <span class="text-gray-400 text-sm">💬 Last message: "{{ $conversation->content }}"</span>
                        </div>
                        <div class="flex-shrink-0">
                            <small class="text-gray-500">{{ $conversation->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">🔍 Search for other people</h3>
            <input type="text" id="searchInput" placeholder="Search..." class="bg-gray-700 text-white p-3 rounded-lg w-full mb-4 placeholder-gray-400" />
            <div id="searchResults" class="space-y-2"></div>
        </div>
    </div>

    <script>
   document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value;
    if (query.length > 0) {
        fetch(`{{ route('messages.search') }}?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(users => {
                const resultsDiv = document.getElementById('searchResults');
                resultsDiv.innerHTML = ''; // Clear previous results
                users.forEach(user => {
                    const userLink = document.createElement('a');
                    userLink.href = `{{ url('messages/chat') }}/${user.id}`;
                    userLink.className = 'flex items-center bg-gray-700 p-4 rounded-lg shadow-md border border-gray-600 transition-transform transform hover:scale-105 hover:bg-gray-600';
                    userLink.innerHTML = `
                        <div class="flex-1">
                            <span class="block font-medium">${user.name}</span>
                            <span class="text-gray-400 text-sm">Last seen: ${user.last_seen_at ? user.last_seen_at : 'Offline'}</span>
                        </div>
                    `;
                    resultsDiv.appendChild(userLink);
                });
            });
    } else {
        document.getElementById('searchResults').innerHTML = ''; // Clear if query is empty
    }
});

</script>
</x-app-layout>