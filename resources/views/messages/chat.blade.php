<x-app-layout>
     <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">Chat with {{ $user->name }}</h2>
        </div>
    </x-slot>


<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md mt-6 h-screen"> <!-- Outer div to fill the screen -->
    <div class="messages overflow-y-auto h-[70vh] mb-4" id="messagesContainer"> <!-- Increased height to 70% of the viewport height -->
        @foreach($messages as $message)
            <div class="mb-4 message relative" data-id="{{ $message->id }}">
                <div class="flex items-start {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    @if ($message->sender_id === Auth::id())
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                 alt="Your Profile Picture" 
                                 class="rounded-full w-10 h-10 object-cover"> <!-- Slightly larger profile picture -->
                        </div>
                    @else
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $message->sender->profile_picture) }}" 
                                 alt="{{ $message->sender->name . "'s Profile Picture" }}" 
                                 class="rounded-full w-10 h-10 object-cover"> <!-- Slightly larger profile picture -->
                        </div>
                    @endif
                    
                    <div class="{{ $message->sender_id === Auth::id() ? 'bg-red-500 text-white rounded-lg p-3 ml-2' : 'bg-gray-700 text-white rounded-lg p-3 ml-2' }} inline-block">
                        <span class="message-content">{{ $message->content ?? '' }}</span>

                        @if ($message->media_url)
                            <div class="mt-2">
                                @if ($message->mediaType === 'photo')
                                    <img src="{{ asset('storage/media/' . $message->media_url) }}" alt="Image" class="max-w-[300px] h-auto rounded-lg object-cover inline-block"> <!-- Increased max width -->
                                @elseif ($message->mediaType === 'video')
                                    <video controls class="max-w-[300px] h-auto rounded-lg shadow-md inline-block"> <!-- Increased max width -->
                                        <source src="{{ asset('storage/media/' . $message->media_url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endif

                        <small class="text-gray-400 block mt-1">{{ $message->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                @if ($message->sender_id === Auth::id())
                    <!-- Three Dots Dropdown -->
                    <div class="absolute top-1 right-1">
                        <button class="relative dropdown-toggle">
                            <span class="text-gray-400 cursor-pointer text-lg">⋮</span>
                        </button>
                        <div class="absolute hidden bg-gray-800 rounded-lg shadow-md w-32 right-0 z-10 dropdown-menu">
                            <button class="edit-message text-yellow-500 block px-4 py-2 hover:bg-gray-700" data-id="{{ $message->id }}">✏️ Edit</button>
                            <button class="delete-message text-red-600 block px-4 py-2 hover:bg-gray-700" data-id="{{ $message->id }}">🗑️ Unsent</button>
                        </div>
                    </div>
                @endif

                @if ($message->read_at)
                    <span class="text-green-500 text-sm ml-2">✓ Read</span>
                @else
                    @if ($message->receiver_id === Auth::id())
                        <span class="text-yellow-500 text-sm ml-2">✉️ Unread</span>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <!-- File input placed above the Send a Message header -->
    <h4 class="text-lg font-semibold mb-2">Send a Message</h4>

    <form id="sendMessageForm" class="border-t border-gray-600 pt-4 flex items-center" enctype="multipart/form-data">
        <input type="file" name="media" accept="image/*,video/*" class="mt-2 border border-red-600 bg-gray-900 text-white p-2 rounded-lg">

        <div class="flex-1">
            <textarea id="content" name="content" rows="3" class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600" placeholder="Type your message..."></textarea>
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


    </>

    <style>
        .dropdown-toggle:hover + .dropdown-menu,
        .dropdown-menu:hover {
            display: block;
        }
    </style>

    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const menu = this.nextElementSibling;
                menu.classList.toggle('hidden');
            });
        });

        // Close dropdown if clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                if (!dropdown.previousElementSibling.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Edit Message Modal -->
    <div id="editMessageModal" class="fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 rounded-lg p-6">
            <h4 class="text-lg font-semibold mb-4">Edit Message</h4>
            <textarea id="editContent" rows="3" class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg" placeholder="Edit your message..."></textarea>
            <div class="flex justify-end mt-4">
                <button id="saveEditButton" class="bg-green-500 text-white rounded-lg px-4 py-2">Save</button>
                <button id="closeEditModal" class="bg-gray-600 text-white rounded-lg px-4 py-2 ml-2">Cancel</button>
            </div>
        </div>
    </div>

     <script>
        const sendMessageForm = document.getElementById('sendMessageForm');
        const messagesContainer = document.getElementById('messagesContainer');
        let currentMessageId;

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
                                    ${data.message.media_url ? (data.message.mediaType === 'image' ? '<img src="' + data.message.media_url + '" alt="Image" class="max-w-full h-auto rounded-lg shadow-md">' : '<video controls class="max-w-full h-auto rounded-lg shadow-md"><source src="' + data.message.media_url + '" type="video/mp4">Your browser does not support the video tag.</video>') : ''}
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

        // Edit Message
        document.querySelectorAll('.edit-message').forEach(button => {
            button.addEventListener('click', function() {
                currentMessageId = this.getAttribute('data-id');
                const messageContent = this.closest('.message').querySelector('.message-content').innerText;
                document.getElementById('editContent').value = messageContent;

                // Show the modal
                document.getElementById('editMessageModal').classList.remove('hidden');
            });
        });

        // Close modal
        document.getElementById('closeEditModal').addEventListener('click', function() {
            document.getElementById('editMessageModal').classList.add('hidden');
        });

        // Save edit
        document.getElementById('saveEditButton').addEventListener('click', function() {
            const newContent = document.getElementById('editContent').value;
            const url = `/messages/${currentMessageId}/edit`;

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: newContent })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the message content in the UI
                    const messageDiv = document.querySelector(`.message[data-id="${currentMessageId}"] .message-content`);
                    messageDiv.innerText = newContent;

                    // Hide the modal
                    document.getElementById('editMessageModal').classList.add('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Delete Message
        document.querySelectorAll('.delete-message').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-id');
                const url = `/messages/${messageId}/delete`;

                if (confirm('Are you sure you want to delete this message?')) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the message from the UI
                            const messageDiv = document.querySelector(`.message[data-id="${messageId}"]`);
                            messageDiv.remove();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
    
</x-app-layout>
