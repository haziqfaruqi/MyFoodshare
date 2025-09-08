@extends('layouts.app')

@section('title', 'Invalid Code - MyFoodshare')

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
                <svg class="h-16 w-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Invalid Verification Code</h1>
            
            <p class="text-gray-600 mb-6">
                The verification code "<span class="font-mono font-medium">{{ $code }}</span>" is not valid or has expired.
            </p>

            <div class="space-y-4">
                <p class="text-sm text-gray-500">This could happen if:</p>
                <ul class="text-sm text-gray-500 text-left max-w-xs mx-auto space-y-1">
                    <li>• The code was entered incorrectly</li>
                    <li>• The pickup has already been completed</li>
                    <li>• The code has expired</li>
                    <li>• The food donation was cancelled</li>
                </ul>
            </div>

            <div class="mt-8 space-y-3">
                <a href="{{ route('pickup.scanner') }}" 
                   class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors font-medium inline-block">
                    Scan Another Code
                </a>
                
                <a href="{{ route('recipient.matches.index') }}" 
                   class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-md hover:bg-gray-300 transition-colors font-medium inline-block">
                    View My Matches
                </a>
            </div>
        </div>
    </div>
</div>
@endsection