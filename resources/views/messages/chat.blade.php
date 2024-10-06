<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">Chat with {{ $user->name }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md mt-6">
        <div class="messages overflow-y-auto h-64 mb-4" id="messagesContainer">
            @foreach($messages as $message)
                <div class="mb-4 message" data-id="{{ $message->id }}">
                    <div class="flex items-start">
                        @if ($message->sender_id === Auth::id())
                            <div class="ml-auto flex items-center">
                                <div class="bg-red-600 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">You</div>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="bg-blue-600 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">{{ $message->sender->name[0] }}</div>
                            </div>
                        @endif
                        <div class="flex-1 ml-2">
                            <div class="{{ $message->sender_id === Auth::id() ? 'bg-red-500 text-white rounded-lg p-3' : 'bg-gray-700 text-white rounded-lg p-3' }}">
                                <strong>{{ $message->sender_id === Auth::id() ? 'You' : $message->sender->name }}</strong>: 
                                <span>{{ $message->content }}</span>
                                <small class="text-gray-400 block">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                            @if ($message->read_at)
                                <span class="text-green-500 text-sm ml-2">✓ Read</span>
                            @else
                                @if ($message->receiver_id === Auth::id())
                                    <span class="text-yellow-500 text-sm ml-2">✉️ Unread</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h4 class="text-lg font-semibold mb-2">Send a Message</h4>
        <form id="sendMessageForm" class="border-t border-gray-600 pt-4 flex items-center">
            <div class="flex-1">
                <textarea id="content" name="content" rows="3" required class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600" placeholder="Type your message..."></textarea>
            </div>
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 ml-2">
                📤 Send
            </button>
            <a href="{{ route('messages.index') }}" class="ml-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                🔙 Back
            </a>
        </form>
    </div>

    <script>
        const sendMessageForm = document.getElementById('sendMessageForm');
        const messagesContainer = document.getElementById('messagesContainer');

        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = '{{ route('messages.send') }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newMessageDiv = document.createElement('div');
                    newMessageDiv.classList.add('mb-4', 'message');
                    newMessageDiv.innerHTML = `
                        <div class="flex items-start">
                            <div class="ml-auto flex items-center">
                                <div class="bg-red-600 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">You</div>
                            </div>
                            <div class="flex-1 ml-2">
                                <div class="bg-red-500 text-white rounded-lg p-3">
                                    <strong>You</strong>: <span>${data.message.content}</span>
                                    <small class="text-gray-400 block">${new Date(data.message.created_at).toLocaleString()}</small>
                                </div>
                                <span class="text-yellow-500 text-sm ml-2">✉️ Unread</span>
                            </div>
                        </div>
                    `;
                    messagesContainer.appendChild(newMessageDiv);
                    this.reset(); // Reset the form after sending

                    // Scroll to the bottom
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Assuming you have Laravel Echo set up to listen for new messages
        Echo.channel('messages')
            .listen('MessageSent', (e) => {
                if (e.message.receiver_id === {{ Auth::id() }} || e.message.sender_id === {{ Auth::id() }}) {
                    const newMessageDiv = document.createElement('div');
                    newMessageDiv.classList.add('mb-4', 'message');
                    newMessageDiv.innerHTML = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-600 rounded-full w-8 h-8 flex items-center justify-center text-white font-bold">${e.message.sender.name[0]}</div>
                            </div>
                            <div class="flex-1 ml-2">
                                <div class="bg-gray-700 text-white rounded-lg p-3">
                                    <strong>${e.message.sender_id === {{ Auth::id() }} ? 'You' : e.message.sender.name}</strong>: 
                                    <span>${e.message.content}</span>
                                    <small class="text-gray-400 block">${new Date(e.message.created_at).toLocaleString()}</small>
                                </div>
                                <span class="text-yellow-500 text-sm ml-2">✉️ Unread</span>
                            </div>
                        </div>
                    `;
                    messagesContainer.appendChild(newMessageDiv);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll to the bottom
                }
            });
    </script>
</x-app-layout>
