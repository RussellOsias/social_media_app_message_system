<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('Create Group') }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6 bg-gray-800 rounded-lg">
        <form action="{{ route('groups.store') }}" method="POST" class="bg-gray-700 p-4 rounded-lg shadow-md">
            @csrf
            <div>
                <label for="name" class="text-white">Group Name</label>
                <input type="text" id="name" name="name" required class="w-full border border-gray-600 bg-gray-900 text-white p-2 rounded-lg">
            </div>
            <button type="submit" class="mt-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">
                Create
            </button>
        </form>
    </div>
</x-app-layout>
