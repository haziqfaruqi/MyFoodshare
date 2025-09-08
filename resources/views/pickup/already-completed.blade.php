@extends('layouts.app')

@section('title', 'Already Completed - MyFoodshare')

@section('navbar')
@if(auth()->user()->role === 'donor')
    @include('layouts.restaurant')
@elseif(auth()->user()->role === 'recipient')  
    @include('layouts.recipient')
@else
    @include('layouts.admin')
@endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="h-16 w-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Pickup Already Completed</h1>
            
            <p class="text-gray-600 mb-6">
                This pickup verification has already been completed.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Food Item:</span>
                        <span class="font-medium">{{ $verification->foodListing->food_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Completed:</span>
                        <span class="font-medium">{{ $verification->pickup_completed_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Recipient:</span>
                        <span class="font-medium">{{ $verification->recipient->name }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('recipient.matches.index') }}" 
                   class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors font-medium inline-block">
                    View My Matches
                </a>
                
                <a href="{{ route('pickup.scanner') }}" 
                   class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-md hover:bg-gray-300 transition-colors font-medium inline-block">
                    Scan Another Code
                </a>
            </div>
        </div>
    </div>
</div>
@endsection