@extends('layouts.recipient')

@section('title', 'Recipient Dashboard - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->organization_name ?: auth()->user()->name }}</h1>
                    <p class="text-gray-600 mt-1">Find and claim food donations near you</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Nearby Food</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['nearby_listings'] }}</p>
                        <p class="text-sm text-blue-600">Available now</p>
                    </div>
                    <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_matches'] }}</p>
                        <p class="text-sm text-yellow-600">Awaiting approval</p>
                    </div>
                    <svg class="h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Approved</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['approved_matches'] }}</p>
                        <p class="text-sm text-green-600">Ready for pickup</p>
                    </div>
                    <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['completed_pickups'] }}</p>
                        <p class="text-sm text-purple-600">Successfully picked up</p>
                    </div>
                    <svg class="h-12 w-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Total Matches</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_matches'] }}</p>
                        <p class="text-sm text-indigo-600">All time</p>
                    </div>
                    <svg class="h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Nearby Food Listings -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Nearby Food Listings</h2>
                        <a href="{{ route('recipient.browse.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">View All</a>
                    </div>
                    
                    @if($nearbyListings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($nearbyListings as $listing)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">{{ $listing->food_name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $listing->user->name }}</p>
                                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                @if(isset($listing->distance))
                                                    {{ number_format($listing->distance, 1) }}km away
                                                @else
                                                    {{ $listing->pickup_location }}
                                                @endif
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Expires {{ $listing->expiry_date->format('M d') }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $listing->quantity }} {{ $listing->unit }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('recipient.browse.show', $listing) }}" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium text-center block">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No food listings nearby</h3>
                            <p class="mt-1 text-sm text-gray-500">Try expanding your search radius or check back later.</p>
                        </div>
                    @endif
                </div>

                <!-- My Recent Matches -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">My Recent Matches</h2>
                        <a href="{{ route('recipient.matches.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">View All</a>
                    </div>
                    
                    @if($myMatches->count() > 0)
                        <div class="space-y-4">
                            @foreach($myMatches as $match)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-3 
                                            @if($match->status === 'completed') bg-green-500
                                            @elseif($match->status === 'approved') bg-blue-500
                                            @elseif($match->status === 'rejected') bg-red-500
                                            @else bg-yellow-500
                                            @endif">
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $match->foodListing->food_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $match->foodListing->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $match->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($match->status === 'completed') bg-green-100 text-green-800
                                            @elseif($match->status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($match->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($match->status) }}
                                        </span>
                                        @if($match->distance)
                                            <p class="text-xs text-gray-500 mt-1">{{ number_format($match->distance, 1) }}km</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No matches yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start browsing food listings to create your first match.</p>
                            <div class="mt-4">
                                <a href="{{ route('recipient.browse.index') }}" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                    Browse Food
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('recipient.browse.index') }}" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium block text-center">
                            Browse Available Food
                        </a>
                        <a href="{{ route('recipient.browse.map') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium block text-center">
                            View Map
                        </a>
                        <a href="{{ route('recipient.matches.index') }}" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-colors text-sm font-medium block text-center">
                            My Matches
                        </a>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tips for Success</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Act quickly</p>
                                <p>Popular food items get claimed fast</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Check expiry times</p>
                                <p>Plan pickup before food expires</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Use map view</p>
                                <p>Find food closest to your location</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection