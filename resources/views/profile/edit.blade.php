<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md text-center">
            <h2 class="text-white text-3xl font-bold">
                🖼️ {{ __('Profile') }}
            </h2>
            <span class="text-lg text-white font-medium">{{ __('Manage your account settings') }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Profile Overview -->
            <div class="bg-black p-6 rounded-lg shadow-md border border-red-600">
                <h3 class="text-white text-2xl font-semibold mb-4">👤 {{ __('Your Profile') }}</h3>

                <!-- Profile Boxes -->
                @foreach([
                    ['label' => __('Name:'), 'value' => $user->name, 'icon' => '✨'],
                    ['label' => __('Email:'), 'value' => $user->email, 'icon' => '📧'],
                    ['label' => __('Birthday:'), 'value' => $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('F j, Y') : 'N/A', 'icon' => '🎂'],
                    ['label' => __('Age:'), 'value' => $user->birthday ? \Carbon\Carbon::parse($user->birthday)->age . ' years' : 'N/A', 'icon' => '🕒'],
                    ['label' => __('Gender:'), 'value' => ucfirst($user->gender), 'icon' => '🚻'],
                    ['label' => __('Occupation:'), 'value' => $user->occupation ?? 'N/A', 'icon' => '💼'],
                    ['label' => __('Address:'), 'value' => $user->address ?? 'N/A', 'icon' => '🏠'],
                    ['label' => __('Nationality:'), 'value' => $user->nationality ?? 'N/A', 'icon' => '🌍'],
                ] as $attribute)
                <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-4">
                    <p class="text-white font-semibold"><strong>{{ $attribute['icon'] }} {{ $attribute['label'] }}</strong> {{ $attribute['value'] }}</p>
                </div>
                @endforeach

                <!-- Contact Information Section -->
                <h4 class="text-white text-xl font-semibold mt-4 mb-2">📞 {{ __('Contact Information') }}</h4>
                @foreach([
                    ['label' => __('Phone Number:'), 'value' => $user->phone ?? 'N/A', 'icon' => '📱'],
                    ['label' => __('Website:'), 'value' => $user->website ? '<a href="' . $user->website . '" class="text-blue-500 underline">' . $user->website . '</a>' : 'N/A', 'icon' => '🌐'],
                ] as $contact)
                <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-4">
                    <p class="text-white font-semibold"><strong>{{ $contact['icon'] }} {{ $contact['label'] }}</strong> {!! $contact['value'] !!}</p>
                </div>
                @endforeach

                <!-- Social Media Links Section -->
                <h4 class="text-white text-xl font-semibold mt-4 mb-2">🔗 {{ __('Social Media Links') }}</h4>
                @foreach([
                    ['label' => __('Facebook:'), 'value' => $user->facebook ? '<a href="' . $user->facebook . '" class="text-blue-500 underline">' . $user->facebook . '</a>' : 'N/A', 'icon' => '📘'],
                    ['label' => __('Twitter:'), 'value' => $user->twitter ? '<a href="' . $user->twitter . '" class="text-blue-500 underline">' . $user->twitter . '</a>' : 'N/A', 'icon' => '🐦'],
                    ['label' => __('Instagram:'), 'value' => $user->instagram ? '<a href="' . $user->instagram . '" class="text-blue-500 underline">' . $user->instagram . '</a>' : 'N/A', 'icon' => '📸'],
                ] as $social)
                <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-4">
                    <p class="text-white font-semibold"><strong>{{ $social['icon'] }} {{ $social['label'] }}</strong> {!! $social['value'] !!}</p>
                </div>
                @endforeach
            </div>

            <!-- Update Profile Information Section -->
            <div class="bg-black p-6 rounded-lg shadow-md border border-red-600">
                <h3 class="text-red-600 text-xl font-semibold mb-4">✏️ {{ __('Update Profile Information') }}</h3>
                <div class="max-w-xl mb-6">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Email Form -->
                <h3 class="text-red-600 text-xl font-semibold mb-4">📧 {{ __('Update Email Address') }}</h3>
                <div class="max-w-xl mb-6">
                    @include('profile.partials.update-user-email-form')
                </div>

                <!-- Update Password Form -->
                <h3 class="text-red-600 text-xl font-semibold mb-4">🔒 {{ __('Update Password') }}</h3>
                <div class="max-w-xl mb-6">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete User Form -->
                <h3 class="text-red-600 text-xl font-semibold mb-4">🗑️ {{ __('Delete Account') }}</h3>
                <div class="max-w-xl mb-6">
                    @include('profile.partials.delete-user-form')
                </div>

                <!-- Additional Features Section -->
                <div class="bg-black p-6 rounded-lg shadow-md border border-red-600">
                    <h3 class="text-white text-xl font-semibold mb-4">✨ {{ __('Additional Features') }}</h3>
                    <ul class="list-disc list-inside text-gray-300">
                        <li>📝 {{ __('View your activity log') }}</li>
                        <li>📊 {{ __('Check your account statistics') }}</li>
                        <li>🔗 {{ __('Manage connected apps and services') }}</li>
                        <li>📩 {{ __('Set up two-factor authentication') }}</li>
                    </ul>
                </div>

                <!-- Support Section -->
                <div class="bg-red-600 p-6 rounded-lg shadow-md border border-red-700">
                    <h3 class="text-white text-xl font-semibold mb-4">🛠️ {{ __('Need Help?') }}</h3>
                    <p class="text-gray-200">If you encounter any issues, feel free to reach out to our support team.</p>
                    <button class="mt-4 px-4 py-2 bg-white text-red-600 rounded hover:bg-gray-200 transition duration-150">Contact Support</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
