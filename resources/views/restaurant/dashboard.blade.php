@extends('layouts.restaurant')

@section('title', 'Restaurant Dashboard - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ auth()->user()->restaurant_name }} Dashboard
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('restaurant.listings.create') }}" class="bg-green-600 p-6 rounded-lg hover:bg-green-700 transition-all duration-200 transform hover:scale-105 shadow-lg group text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium opacity-80">Create New</p>
                        <p class="text-lg font-semibold mt-1">Food Listing</p>
                    </div>
                    <svg class="h-8 w-8 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('restaurant.listings.index') }}" class="bg-white hover:bg-gray-50 border-2 border-blue-500 p-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium opacity-80">Manage</p>
                        <p class="text-blue-600 text-lg font-semibold mt-1">Listings</p>
                    </div>
                    <svg class="h-8 w-8 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </a>

            <div class="bg-white hover:bg-gray-50 border-2 border-purple-500 p-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium opacity-80">Track</p>
                        <p class="text-purple-600 text-lg font-semibold mt-1">Donations</p>
                    </div>
                    <svg class="h-8 w-8 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white hover:bg-gray-50 border-2 border-orange-500 p-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium opacity-80">View</p>
                        <p class="text-orange-600 text-lg font-semibold mt-1">Reports</p>
                    </div>
                    <svg class="h-8 w-8 text-orange-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Stats Overview -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['total_listings'] }}</div>
                            <div class="text-sm text-gray-600">Total Listings</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['active_listings'] }}</div>
                            <div class="text-sm text-gray-600">Active Listings</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['total_matches'] }}</div>
                            <div class="text-sm text-gray-600">Total Matches</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['completed_donations'] }}</div>
                            <div class="text-sm text-gray-600">Completed</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Recent Listings</h2>
                        <a href="{{ route('restaurant.listings.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                            View All
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentListings as $listing)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $listing->food_name }}</h3>
                                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                {{ $listing->quantity }} {{ $listing->unit }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $listing->pickup_location }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $listing->expiry_date->format('M j') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                                            @if($listing->status === 'active') bg-green-100 text-green-800
                                            @elseif($listing->status === 'matched') bg-blue-100 text-blue-800
                                            @elseif($listing->status === 'picked_up') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p>No listings yet. Create your first listing to get started!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Pending Matches -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Pending Matches</h2>
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 00-15 0v5h5l-5 5-5-5h5V7a9.5 9.5 0 0119 0v10z"></path>
                        </svg>
                    </div>
                    <div class="space-y-3">
                        @forelse($pendingMatches as $match)
                            <div class="border-l-4 border-blue-500 pl-3 py-2">
                                <p class="text-sm text-gray-900 font-medium">{{ $match->recipient->organization_name }}</p>
                                <p class="text-xs text-gray-600">{{ $match->foodListing->food_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $match->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No pending matches</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Today's Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Active Listings</span>
                            <span class="font-semibold text-green-600">{{ $stats['active_listings'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Pending Matches</span>
                            <span class="font-semibold text-blue-600">{{ $pendingMatches->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Matches</span>
                            <span class="font-semibold text-purple-600">{{ $stats['total_matches'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-semibold text-orange-600">{{ $stats['completed_donations'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="bg-gradient-to-br from-green-500 to-blue-500 rounded-lg shadow-lg p-6 text-white">
                    <h2 class="text-lg font-semibold mb-4">Need Help?</h2>
                    <p class="text-green-100 mb-4 text-sm">
                        Our support team is here to help you maximize your impact.
                    </p>
                    <a href="{{ route('contact') }}" class="bg-white text-green-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors inline-block">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection