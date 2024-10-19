<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-2xl font-semibold">{{ __('Edit Group') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md">
        <form action="{{ route('groups.update', $group) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-gray-400">Group Name:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" class="w-full border border-gray-600 bg-gray-900 text-white p-2 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="owner_id" class="block text-gray-400">Transfer Ownership to:</label>
                <select name="owner_id" id="owner_id" class="w-full border border-gray-600 bg-gray-900 text-white p-2 rounded-lg">
                    <option value="">Select a new owner</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $group->owner_id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Update Group</button>
        </form>
    </div>
</x-app-layout>
