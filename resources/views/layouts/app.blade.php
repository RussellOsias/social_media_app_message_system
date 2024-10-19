<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom styles for red and black theme -->
    <style>
        body {
            background-color: #000; /* Black background */
            color: #fff; /* White text for contrast */
            font-family: 'Figtree', sans-serif; /* Updated font family */
            line-height: 1.6; /* Improved readability */
        }
        .header-bg {
            background: linear-gradient(to right, #ff0000, #cc0000); /* Gradient header */
            padding: 1rem 0; /* Header padding */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Shadow for depth */
        }
        .header-text {
            color: #ffffff; /* White text */
            font-size: 2rem; /* Larger font size */
            text-align: center; /* Center text */
            margin: 0; /* Remove margin */
        }
        .btn-red {
            background-color: #ff0000; /* Red button */
            color: #fff; /* White text */
            padding: 0.5rem 1rem;
            border-radius: 0.5rem; /* Rounded corners */
            transition: background-color 0.3s ease, transform 0.2s; /* Smooth transition */
        }
        .btn-red:hover {
            background-color: #cc0000; /* Darker red on hover */
            transform: scale(1.05); /* Slightly enlarge button */
        }
        .bg-black {
            background-color: #111; /* Darker black for content background */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding for content */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Light shadow for depth */
            transition: background-color 0.3s ease; /* Transition effect */
        }
        .bg-black:hover {
            background-color: #222; /* Slightly lighter on hover */
        }
        .border-red {
            border-color: #ff0000; /* Red border */
        }
        .post {
            background: rgba(255, 0, 0, 0.1); /* Light red background for posts */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding for posts */
            margin-bottom: 1rem; /* Space between posts */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Shadow for depth */
            transition: transform 0.2s; /* Smooth scaling */
        }
        .post:hover {
            transform: scale(1.02); /* Slightly enlarge post */
        }
        .create-post {
            background: rgba(255, 255, 255, 0.1); /* Light gray background */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 1rem; /* Padding for create post */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Shadow for depth */
            margin-bottom: 1.5rem; /* Space below create post section */
        }
        .textarea {
            width: 100%; /* Full width */
            border: 1px solid #ff0000; /* Red border */
            background: rgba(255, 255, 255, 0.2); /* Light gray background */
            border-radius: 0.5rem; /* Rounded corners */
            padding: 0.5rem; /* Padding for textarea */
            color: #fff; /* White text */
            transition: background-color 0.3s, border-color 0.3s; /* Transition effects */
        }
        .textarea:focus {
            outline: none; /* Remove outline */
            border-color: #cc0000; /* Darker red border on focus */
            background: rgba(255, 255, 255, 0.3); /* Lighter background */
        }
        .footer {
            background-color: #111; /* Dark footer */
            text-align: center; /* Centered footer text */
            padding: 1rem; /* Padding for footer */
            color: #fff; /* White text */
        }
        .social-icons {
            display: flex; /* Flexbox for social icons */
            justify-content: center; /* Center the icons */
            margin-top: 0.5rem; /* Margin above icons */
        }
        .social-icons a {
            color: #ff0000; /* Red color for icons */
            margin: 0 1rem; /* Space between icons */
            font-size: 1.5rem; /* Icon size */
            transition: color 0.3s; /* Smooth transition */
        }
        .social-icons a:hover {
            color: #cc0000; /* Darker red on hover */
        }
        .flex-container {
            display: flex; /* Use flexbox */
            flex-direction: column; /* Stack items vertically */
            height: 100vh; /* Full viewport height */
        }
        .sidebar {
            background-color: #111; /* Black sidebar */
            width: 250px; /* Fixed width for sidebar */
            padding: 1rem; /* Padding for sidebar */
            display: flex;
            flex-direction: column; /* Stack items vertically */
        }
        .content {
            flex-grow: 1; /* Take remaining space */
            padding: 1rem; /* Padding for content */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex-container">
        @include('layouts.navigation')

        <div class="flex flex-1">
            <!-- Sidebar -->
<aside class="sidebar bg-gray-900 text-white">
    <!-- Profile Section -->
    <div class="profile-section flex flex-col items-center py-4">
        <!-- Profile Picture -->
        @if(Auth::user()->profile_picture)
            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                 alt="Profile Picture"
                 class="w-24 h-24 rounded-full object-cover mb-2">
        @else
            <img src="{{ asset('images/default-avatar.png') }}"
                 alt="Default Profile Picture"
                 class="w-24 h-24 rounded-full object-cover mb-2">
        @endif
        
        <!-- User Name -->
        <h2 class="text-xl font-semibold">{{ Auth::user()->name }}</h2>
    </div>

    <!-- Sidebar Navigation -->
    <div class="flex flex-col pt-4">
        <x-responsive-nav-link :href="route('dashboard')" class="text-white hover:text-red-600 transition duration-150">
            🏠 {{ __('Home Page') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('friends')" class="text-white hover:text-red-600 transition duration-150">
            👥 {{ __('Friendlist') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('messages.index')" class="text-white hover:text-red-600 transition duration-150">
            💬 {{ __('Messages') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('notifications')" class="text-white hover:text-red-600 transition duration-150">
            🔔 {{ __('Notifications') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('groups.index')" class="text-white hover:text-red-600 transition duration-150">
            🗂️ {{ __('Group Pages') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:text-red-600 transition duration-150">
            👤 {{ __('Edit Profile') }}
        </x-responsive-nav-link>
    </div>
</aside>


            <!-- Main Content -->
            <main class="content bg-black flex-grow">
                <!-- Page Heading -->
                @isset($header)
                    <header class="header-bg shadow">
                        <div class="max-w-7xl mx-auto">
                            <h1 class="header-text">{{ $header }}</h1>
                        </div>
                    </header>
                @endisset

                <!-- Page Content Here -->
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-black border-red border-2 shadow-sm rounded-lg p-6">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024 Russell Osias Social Media. All rights reserved.</p>
            <div class="footer-links" style="display: flex; justify-content: center; gap: 1rem;">
                <a href="{{ route('privacy') }}" style="color: #ff0000; text-decoration: underline;">Privacy Policy</a> |
                <a href="{{ route('terms') }}" style="color: #ff0000; text-decoration: underline;">Terms of Service</a> |
                <a href="{{ route('about') }}" style="color: #ff0000; text-decoration: underline;">About</a>
            </div>
            <div class="social-icons">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
            </div>
        </footer>
    </div>
</body>
</html>
