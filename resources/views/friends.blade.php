<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('👥 Friends') }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-black text-white p-6 rounded-lg shadow-md">
        @if(session('message'))
            <div class="bg-green-600 text-white p-2 rounded mb-4">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        <!-- Suggested Friends Section -->
        <section class="mt-6">
            <h2 class="text-xl text-gray-300 font-bold border-b border-gray-600 pb-2">🤔 Suggested Friends</h2>
            <ul class="mt-4">
                @forelse($suggestedFriends as $suggested)
                    <li class="flex justify-between items-center p-4 border-b border-gray-600 bg-gray-800 rounded-md hover:bg-gray-700 transition duration-200">
                        <a href="{{ route('profile.show', $suggested->id) }}" class="text-blue-400 font-semibold hover:underline">
                            {{ $suggested->name }}
                        </a>
                        @php
                            $friendship = Auth::user()->friends()->where('friend_id', $suggested->id)->first();
                        @endphp
                        @if($friendship && $friendship->pivot->status === 'pending')
                            <form method="POST" action="{{ route('friends.cancel', $suggested->id) }}">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg" aria-label="Cancel Friend Request for {{ $suggested->name }}">
                                    {{ __('🚫 Cancel Request') }}
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('friends.add', $suggested->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg" aria-label="Add {{ $suggested->name }} as a Friend">
                                    {{ __('➕ Add Friend') }}
                                </button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="p-4">No suggested friends available.</li>
                @endforelse
            </ul>
        </section>

  <!-- Pending Friend Requests Section -->
<section class="mt-10">
    <h2 class="text-xl text-gray-300 font-bold border-b border-gray-600 pb-2">📩 Pending Friend Requests</h2>
    <ul class="mt-4">
        @forelse($pendingRequests as $pending)
            <li class="flex justify-between items-center p-4 border-b border-gray-600 bg-gray-800 rounded-md hover:bg-gray-700 transition duration-200">
                <a href="{{ route('profile.show', $pending->initiator_id) }}" class="text-blue-400 font-semibold hover:underline">{{ $pending->initiator->name }}</a>
                <div class="flex space-x-2">
                    <form method="POST" action="{{ route('friends.accept', $pending->initiator_id) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg" aria-label="Accept Friend Request from {{ $pending->initiator->name }}">
                            {{ __('✅ Accept') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('friends.reject', $pending->initiator_id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg" aria-label="Reject Friend Request from {{ $pending->initiator->name }}">
                            {{ __('❌ Reject') }}
                        </button>
                    </form>
                </div>
            </li>
        @empty
            <li class="p-4">No pending friend requests.</li>
        @endforelse
    </ul>
</section>



        <!-- Current Friends Section -->
        <section class="mt-10">
            <h2 class="text-xl text-gray-300 font-bold border-b border-gray-600 pb-2">👫 Your Friends</h2>
            <ul class="mt-4">
                @forelse($friends as $friend)
                    <li class="flex justify-between items-center p-4 border-b border-gray-600 bg-gray-800 rounded-md hover:bg-gray-700 transition duration-200">
                        <a href="{{ route('profile.show', $friend->id) }}" class="text-blue-400 font-semibold hover:underline">{{ $friend->name }}</a>
                        <form method="POST" action="{{ route('friends.unfriend', $friend->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg" aria-label="Unfriend {{ $friend->name }}">
                                {{ __('👤 Unfriend') }}
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="p-4">You have no friends.</li>
                @endforelse
            </ul>
        </section>

    
    </div>
</x-app-layout>
