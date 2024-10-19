<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-2xl font-semibold">{{ __('Join Group') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (auth()->id() === $group->owner_id)
            <!-- Form for the group owner to manually add users -->
            <h3 class="text-lg font-semibold text-gray-300 mt-6">Add User to Group:</h3>
            <form action="{{ route('groups.addUser', $group) }}" method="POST" class="mb-4">
                @csrf
                <label for="user_id" class="block text-gray-400">Select User:</label>
                <select name="user_id" id="user_id" class="w-full border border-gray-600 bg-gray-900 text-white p-2 rounded-lg">
                    @foreach ($users as $user)
                        @if (!$group->users->contains($user->id))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
                <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Add User</button>
            </form>
        @endif

        <!-- Form for users to request to join the group -->
        @if (!$group->users->contains(auth()->id()) && !$group->pendingRequests->contains('user_id', auth()->id()))
            <form action="{{ route('groups.joinRequest', $group) }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Request to Join</button>
            </form>
        @elseif ($group->pendingRequests->contains('user_id', auth()->id()))
            <p class="text-yellow-300 mb-4">Your request to join is pending approval.</p>
        @endif

        <!-- Display pending join requests for the group owner -->
        @if (auth()->id() === $group->owner_id)
            <h3 class="text-lg font-semibold text-gray-300 mt-6">Pending Join Requests:</h3>
            @if ($group->pendingRequests->isEmpty())
                <p class="text-gray-300">No pending requests.</p>
            @else
                <ul class="mt-2">
                    @foreach ($group->pendingRequests as $request)
                        <li class="bg-gray-700 p-2 rounded-lg mb-2 flex justify-between items-center">
                            {{ $request->user->name }}
                            <div>
                                <form action="{{ route('groups.approveRequest', [$group, $request->user]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-500 hover:underline">Approve</button>
                                </form>
                                <form action="{{ route('groups.denyRequest', [$group, $request->user]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Deny</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif

        <h3 class="text-lg font-semibold text-gray-300 mt-6">Users in this Group:</h3>
        @if ($group->users->isEmpty())
            <p class="text-gray-300">No users in this group yet.</p>
        @else
            <ul class="mt-2">
                @foreach ($group->users as $member)
                    <li class="bg-gray-700 p-2 rounded-lg mb-2 flex justify-between items-center">
                        {{ $member->name }}
                        @if (auth()->id() === $group->owner_id)
                            <form action="{{ route('groups.removeUser', [$group, $member]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Remove</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
