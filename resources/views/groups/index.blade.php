<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-2xl font-semibold">{{ __('Groups') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md">
        <!-- Create Group Button -->
        <div class="mb-4">
            <a href="{{ route('groups.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Create New Group</a>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('groups.index') }}" method="GET" class="mb-4">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search groups..." class="w-full border border-gray-600 bg-gray-900 text-white p-2 rounded-lg">
            <button type="submit" class="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Search</button>
        </form>

        <h3 class="text-lg font-semibold text-gray-300 mb-4">Groups List</h3>
        <ul class="space-y-4">
            @foreach ($groups as $group)
                <li class="bg-gray-700 p-4 rounded-lg shadow-md transition transform hover:scale-105">
                    <a href="{{ route('groups.show', $group) }}" class="text-blue-500 hover:underline text-xl font-semibold">{{ $group->name }}</a>
                    <div class="mt-2">
                        <small class="text-gray-400">Owner: {{ $group->owner ? $group->owner->name : 'Unknown' }}</small>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('groups.addUserForm', $group) }}" class="text-green-500 hover:underline">View and add users in the group</a>
                    </div>
                    @if (auth()->id() === $group->owner_id) <!-- Show edit options only to the owner -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('groups.edit', $group) }}" class="text-yellow-500 hover:underline">Edit</a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($groups->isEmpty())
            <p class="text-gray-300 mt-4">No groups found. Consider creating one!</p>
        @endif
    </div>
</x-app-layout>
