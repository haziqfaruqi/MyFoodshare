@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 text-center">
        <div class="text-red-600 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Invalid QR Code</h1>
        <p class="text-gray-600 mb-6">
            The QR code you scanned is either invalid, expired, or has already been used.
        </p>

        <div class="space-y-3">
            <a href="{{ route('home') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200 block">
                Return to Home
            </a>

            <button onclick="history.back()" class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-400 transition duration-200">
                Go Back
            </button>
        </div>

        <div class="mt-6 text-sm text-gray-500">
            <p>If you believe this is an error, please contact the restaurant or support team.</p>
        </div>
    </div>
</div>
@endsection