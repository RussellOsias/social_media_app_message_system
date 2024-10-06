@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 bg-black text-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Help & Support</h2>

    <div class="space-y-4">
        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium">Frequently Asked Questions</h3>
            <p class="text-gray-300">Find answers to common questions.</p>
            <a href="{{ route('help.faq') }}" class="text-red-600 hover:underline">View FAQs</a>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium">Contact Support</h3>
            <p class="text-gray-300">Get in touch with our support team.</p>
            <a href="{{ route('help.contact') }}" class="text-red-600 hover:underline">Contact Support</a>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-medium">Report an Issue</h3>
            <p class="text-gray-300">Let us know if you encounter any issues.</p>
            <a href="{{ route('help.report') }}" class="text-red-600 hover:underline">Report an Issue</a>
        </div>
    </div>
</div>
@endsection
