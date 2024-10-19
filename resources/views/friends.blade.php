<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-6 rounded-t-lg shadow-md">
            <h2 class="text-white text-3xl font-semibold text-center">{{ __('👥 Friends') }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 bg-gray-900 text-white p-6 rounded-lg shadow-md">
        @if(session('message'))
            <div class="bg-green-600 text-white p-2 rounded mb-4 transition-all duration-300">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-2 rounded mb-4 transition-all duration-300">{{ session('error') }}</div>
        @endif

        <!-- Search Form -->
        <form method="GET" action="{{ route('friends.index') }}" class="mb-4">
            <div class="flex">
                <input type="text" name="search" placeholder="Search by name..." class="flex-grow p-2 rounded-l-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg transition duration-200">🔍 Search</button>
            </div>
        </form>

        <!-- Suggested Friends Section -->
        <section class="mt-8">
            <h2 class="text-xl text-gray-300 font-bold border-b border-gray-700 pb-2">🤔 Search and Suggestive friends for you</h2>
            <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($suggestedFriends as $suggested)
                    <li class="flex flex-col justify-between p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            @if($suggested->profile_picture)
                                <img src="{{ asset('storage/' . $suggested->profile_picture) }}" alt="{{ $suggested->name }}'s Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-md mr-4">
                            @else
                                <div class="w-16 h-16 flex items-center justify-center bg-gray-600 rounded-full text-white text-2xl font-bold">
                                    {{ strtoupper(substr($suggested->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('profile.show', $suggested->id) }}" class="text-blue-400 font-semibold text-lg hover:underline">
                                    {{ $suggested->name }}
                                </a>
                                <p class="text-gray-400 text-sm mt-1">"{{ $suggested->bio ?? 'No bio available' }}"</p>
                               
                            </div>
                        </div>
                        @php
                            $friendship = Auth::user()->friends()->where('friend_id', $suggested->id)->first();
                        @endphp
                        <div class="flex justify-between">
                            @if($friendship && $friendship->pivot->status === 'pending')
                                <form method="POST" action="{{ route('friends.cancel', $suggested->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-lg transition duration-200" aria-label="Cancel Friend Request for {{ $suggested->name }}">
                                        {{ __('🚫 Cancel Request') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('friends.add', $suggested->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg transition duration-200" aria-label="Add {{ $suggested->name }} as a Friend">
                                        {{ __('➕ Add Friend') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-md">No suggested friends available.</li>
                @endforelse
            </ul>
        </section>

        <!-- Remaining Sections (Pending Requests, Current Friends) -->
        <section class="mt-10">
            <h2 class="text-xl text-gray-300 font-bold border-b border-gray-700 pb-2">📩 Pending Friend Requests</h2>
            <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pendingRequests as $pending)
                    <li class="flex flex-col justify-between p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            @if($pending->initiator->profile_picture)
                                <img src="{{ asset('storage/' . $pending->initiator->profile_picture) }}" alt="{{ $pending->initiator->name }}'s Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-md mr-4">
                            @else
                                <div class="w-16 h-16 flex items-center justify-center bg-gray-600 rounded-full text-white text-2xl font-bold">
                                    {{ strtoupper(substr($pending->initiator->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('profile.show', $pending->initiator_id) }}" class="text-blue-400 font-semibold text-lg hover:underline">{{ $pending->initiator->name }}</a>
                                <p class="text-gray-400 text-sm mt-1">"{{ $pending->initiator->bio ?? 'No bio available' }}"</p>
                                <p class="text-gray-400 text-sm mt-1">Pending Request</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('friends.accept', $pending->initiator_id) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-4 rounded-lg transition duration-200" aria-label="Accept Friend Request from {{ $pending->initiator->name }}">
                                    {{ __('✅ Accept') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('friends.reject', $pending->initiator_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-lg transition duration-200" aria-label="Reject Friend Request from {{ $pending->initiator->name }}">
                                    {{ __('❌ Reject') }}
                                </button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-md">No pending friend requests.</li>
                @endforelse
            </ul>
        </section>

      
        <!-- Current Friends Section -->
        <section class="mt-10">
            <h2 class="text-xl text-gray-300 font-bold border-b border-gray-700 pb-2">👫 Your Friends</h2>
            <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($friends as $friend)
                    <li class="flex flex-col justify-between p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            @if($friend->profile_picture)
                                <img src="{{ asset('storage/' . $friend->profile_picture) }}" alt="{{ $friend->name }}'s Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-md mr-4">
                            @else
                                <div class="w-16 h-16 flex items-center justify-center bg-gray-600 rounded-full text-white text-2xl font-bold">
                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('profile.show', $friend->id) }}" class="text-blue-400 font-semibold text-lg hover:underline">{{ $friend->name }}</a>
                                <p class="text-gray-400 text-sm mt-1">"{{ $friend->bio ?? 'No bio available' }}"</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('friends.unfriend', $friend->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-lg transition duration-200" aria-label="Unfriend {{ $friend->name }}">
                                {{ __('👤 Unfriend') }}
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="p-4 border border-gray-700 bg-gray-800 rounded-lg shadow-md">You have no friends.</li>
                @endforelse
            </ul>
        </section>


       
    </div>
</x-app-layout>
