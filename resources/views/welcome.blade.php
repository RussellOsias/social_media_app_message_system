<x-guest-layout class="bg-black min-h-screen">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-black p-6 rounded-lg shadow-md max-w-md mx-auto mt-10 border border-red-600 text-white">
        <!-- Welcome Header with Emoji -->
        <h2 class="text-center text-3xl font-semibold text-white mb-6">
            👋 {{ __('Russell Osias Social Media') }}
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('📧 Email Address')" class="text-lg font-medium text-gray-200" />
                <x-text-input id="email" class="block mt-1 w-full p-3 border border-gray-500 rounded-lg focus:ring-2 focus:ring-blue-400 bg-gray-800 text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <!-- Password -->
            <div class="mb-6">
                <x-input-label for="password" :value="__('🔒 Your Password')" class="text-lg font-medium text-gray-200" />
                <x-text-input id="password" class="block mt-1 w-full p-3 border border-gray-500 rounded-lg focus:ring-2 focus:ring-blue-400 bg-gray-800 text-white" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center text-sm text-gray-400">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-500 bg-gray-800 text-blue-600 shadow-sm focus:ring-blue-400" name="remember">
                    <span class="ms-2">{{ __('Remember Me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-400 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Sign In Button -->
            <div class="mt-6 text-center">
                <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg mt-3 transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                    {{ __('Sign In') }} 🚪
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center justify-center">
            <div class="w-full h-px bg-gray-700"></div>
            <span class="px-2 text-sm text-gray-400">or</span>
            <div class="w-full h-px bg-gray-700"></div>
        </div>

        <!-- Create Account Section -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-400">{{ __("Don't have an account?") }}</p>
            <a href="{{ route('register') }}">
                <button class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 rounded-lg mt-3 transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                    {{ __('Create an Account') }} ✍️
                </button>
            </a>
        </div>

        <!-- Social Login Section -->
        <div class="mt-8 text-center">
            <p class="text-gray-400 text-sm">Sign in with</p>
            <div class="flex justify-center space-x-4 mt-4">
                <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-110">
                    <span class="text-lg">🔵</span> <!-- Facebook-like button -->
                </a>
                <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-gray-700 transition duration-300 ease-in-out transform hover:scale-110">
                    <span class="text-lg">🔘</span> <!-- Custom social login button -->
                </a>
                <a href="#" class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white hover:bg-red-600 transition duration-300 ease-in-out transform hover:scale-110">
                    <span class="text-lg">🔴</span> <!-- Google-like button -->
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
