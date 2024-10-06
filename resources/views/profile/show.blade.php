<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md flex justify-center items-center">
            <h2 class="text-white text-2xl font-semibold flex items-center">
                <span class="mr-2">👤</span> {{ $user->name }}'s Profile
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-black text-white p-6 rounded-lg shadow-md">
        <!-- Emoji Profile Icon Section -->
        <div class="flex justify-center items-center mb-6">
            <span class="text-6xl">😎</span> <!-- Replaced image with emoji -->
        </div>

        <div class="flex justify-between items-center mb-4">
            <div class="text-center">
                <h3 class="text-3xl font-bold">{{ $user->name }} <span class="ml-2">👋</span></h3>
                <p class="text-lg italic text-gray-400">"{{ $user->bio ?? 'No bio available' }}"</p>
            </div>
        </div>

        <!-- User Details Section -->
        <div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
            <p class="text-lg flex items-center">
                <span class="font-semibold">📧 Email:</span> <span class="ml-2">{{ $user->email }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🎂 Birthday:</span> <span class="ml-2">{{ $user->birthday ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">💼 Occupation:</span> <span class="ml-2">{{ $user->occupation ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">⚧️ Gender:</span> <span class="ml-2">{{ $user->gender ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🏠 Address:</span> <span class="ml-2">{{ $user->address ?? 'Not provided' }}</span>
            </p>
            <p class="text-lg flex items-center">
                <span class="font-semibold">🌍 Nationality:</span> <span class="ml-2">{{ $user->nationality ?? 'Not provided' }}</span>
            </p>
        </div>

        <!-- Friends and Social Section -->
        <div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold">👥 Friends</h3>
            <div class="flex space-x-4">
                <!-- Friends list using emojis - replace with dynamic content -->
                <div class="text-center">
                    <span class="text-5xl">🤠</span> <!-- Emoji instead of image -->
                    <p class="text-sm">John Doe</p>
                </div>
                <div class="text-center">
                    <span class="text-5xl">👩‍🦰</span> <!-- Emoji instead of image -->
                    <p class="text-sm">Jane Smith</p>
                </div>
                <div class="text-center">
                    <span class="text-5xl">👩‍🦳</span> <!-- Emoji instead of image -->
                    <p class="text-sm">Alice Brown</p>
                </div>
            </div>
        </div>

        <!-- Recent Posts Section -->
        <div class="mt-8 space-y-4 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold">📝 Recent Posts</h3>
            <div class="space-y-4">
                <!-- Example post card - replace with dynamic content -->
                <div class="bg-black border border-red-600 p-4 rounded-lg">
                    <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. <span class="text-sm text-gray-400">2 hours ago</span></p>
                    <div class="flex justify-end">
                        <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 text-sm">❤️ Like</button>
                        <button class="bg-gray-600 text-white px-3 py-1 rounded-lg ml-2 hover:bg-gray-700 text-sm">💬 Comment</button>
                    </div>
                </div>
                <div class="bg-black border border-red-600 p-4 rounded-lg">
                    <p class="text-lg">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. <span class="text-sm text-gray-400">1 day ago</span></p>
                    <div class="flex justify-end">
                        <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 text-sm">❤️ Like</button>
                        <button class="bg-gray-600 text-white px-3 py-1 rounded-lg ml-2 hover:bg-gray-700 text-sm">💬 Comment</button>
                    </div>
                </div>
            </div>
        </div>

      <!-- Send Message Button -->
<div class="mt-6 flex justify-center">
<a href="{{ route('messages.chat', ['userId' => $user->id]) }}" class="bg-red-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-red-700">
    ✉️ Send Message
</a>
</div>

</x-app-layout>
