@extends('layouts.recipient')

@section('title', $listing->food_name . ' - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('recipient.dashboard') }}" class="text-green-600 hover:text-green-800">Dashboard</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('recipient.browse.index') }}" class="text-green-600 hover:text-green-800">Browse Food</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">{{ $listing->food_name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Food Images -->
                @if($listing->images && count($listing->images) > 0)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                            @foreach($listing->images as $image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="{{ $listing->food_name }}" 
                                         class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Food Details -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $listing->food_name }}</h1>
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ $listing->user->name }}</span>
                                @if($listing->user->restaurant_name)
                                    <span class="ml-2 text-gray-400">•</span>
                                    <span class="ml-2">{{ $listing->user->restaurant_name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $listing->quantity }} {{ $listing->unit }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($listing->category) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($listing->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $listing->description }}</p>
                        </div>
                    @endif
                    
                    <!-- Dietary Information -->
                    @if($listing->dietary_info && count($listing->dietary_info) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Dietary Information</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($listing->dietary_info as $info)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $info }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Special Instructions -->
                    @if($listing->special_instructions)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Special Instructions</h3>
                            <p class="text-gray-700 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                {{ $listing->special_instructions }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Action Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Take Action</h3>
                    
                    @if($existingMatch)
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-blue-800 font-medium">Interest Already Expressed</span>
                            </div>
                            <p class="text-blue-700 text-sm mt-1">
                                Status: <span class="font-medium">{{ ucfirst($existingMatch->status) }}</span>
                            </p>
                            @if($existingMatch->created_at)
                                <p class="text-blue-600 text-xs mt-1">{{ $existingMatch->created_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        
                        <a href="{{ route('recipient.matches.index') }}" 
                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium text-center block">
                            View My Matches
                        </a>
                    @else
                        <form action="{{ route('recipient.browse.express-interest', $listing) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition-colors font-medium text-center">
                                Express Interest
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2 text-center">The donor will be notified of your interest</p>
                    @endif
                </div>

                <!-- Location & Timing -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Location & Timing</h3>
                    
                    <div class="space-y-4">
                        <!-- Distance -->
                        @if($distance)
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($distance, 1) }} km away</p>
                                    <p class="text-xs text-gray-500">Distance from your location</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Pickup Location -->
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $listing->pickup_location }}</p>
                                @if($listing->pickup_address)
                                    <p class="text-xs text-gray-500">{{ $listing->pickup_address }}</p>
                                @endif
                                <p class="text-xs text-gray-500">Pickup location</p>
                            </div>
                        </div>
                        
                        <!-- Expiry Time -->
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $listing->expiry_date->format('M d, Y') }}
                                    @if($listing->expiry_time)
                                        at {{ $listing->expiry_time->format('H:i') }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">Best before</p>
                            </div>
                        </div>
                        
                        <!-- Created -->
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v8a2 2 0 002 2h4a2 2 0 002-2V7M9 11h6"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $listing->created_at->diffForHumans() }}</p>
                                <p class="text-xs text-gray-500">Listed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification QR Code</h3>
                    <div class="text-center">
                        <div class="inline-block bg-white p-4 border border-gray-200 rounded-lg">
                            @php
                                use SimpleSoftwareIO\QrCode\Facades\QrCode;
                            @endphp
                            {!! QrCode::size(150)->generate($listing->getQrCodeUrl()) !!}
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Scan this code during pickup for verification
                        </p>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <a href="{{ route('recipient.browse.index') }}" 
                       class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center block">
                        ← Back to Browse
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection