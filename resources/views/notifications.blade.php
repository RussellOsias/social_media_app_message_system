<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __('🔔 Notifications') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 bg-black text-white p-6 rounded-lg shadow-md">
        @if(session('message'))
            <div class="bg-green-600 text-white p-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex space-x-4 mb-4">
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    ✅ Mark All as Read
                </button>
            </form>

            <form method="POST" action="{{ route('notifications.deleteAllRead') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    🗑️ Delete All Read Notifications
                </button>
            </form>
        </div>

        <div class="flex space-x-8">
            <!-- Unread Notifications -->
            <div class="w-1/2 pr-4">
                <h3 class="text-xl font-bold mb-2">New Notifications</h3>
                <ul class="space-y-4">
                    @forelse ($notifications->whereNull('read_at') as $notification)
                        <li class="mb-4 bg-gray-800 p-4 rounded-lg shadow-md transition duration-200 hover:bg-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-lg text-gray-300">{{ $notification->data['message'] }}</p>
                                    <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                            Mark as Read
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-400">You have no new notifications.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Read Notifications -->
            <div class="w-1/2 pl-4">
                <h3 class="text-xl font-bold mb-2">Read Notifications</h3>
                <ul class="space-y-4">
                    @forelse ($notifications->whereNotNull('read_at') as $notification)
                        <li class="mb-4 bg-gray-800 p-4 rounded-lg shadow-md transition duration-200 hover:bg-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-lg text-gray-300">{{ $notification->data['message'] }}</p>
                                    <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <form method="POST" action="{{ route('notifications.delete', $notification->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-400">You have no read notifications.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        <footer class="mt-10 border-t border-gray-600 pt-4 text-gray-400 text-center">
            <div>&copy; {{ date('Y') }} Russell Osias. All rights reserved.</div>
        </footer>
    </div>
</x-app-layout>
