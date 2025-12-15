@extends('layouts.restaurant')

@section('title', 'Restaurant Profile - MyFoodshare')

@section('content')
@php
    $user = auth()->user();
    if (!$user->restaurantProfile) {
        // Redirect to create restaurant profile if none exists
        return redirect()->route('restaurant.profile.create');
    }
@endphp
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Restaurant Profile</h1>
                    <p class="text-gray-600 mt-1">Manage your restaurant information and settings</p>
                </div>
                <a href="{{ route('restaurant.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Profile Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4
                        @if(auth()->user()->restaurantProfile->status === 'approved') bg-green-100
                        @elseif(auth()->user()->restaurantProfile->status === 'pending') bg-yellow-100
                        @else bg-red-100">
                        <svg class="w-6 h-6
                            @if(auth()->user()->restaurantProfile->status === 'approved') text-green-600
                            @elseif(auth()->user()->restaurantProfile->status === 'pending') text-yellow-600
                            @else text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->restaurantProfile->restaurant_name }}</h3>
                        <p class="text-sm text-gray-600">
                            Status:
                            <span class="font-medium
                                @if(auth()->user()->restaurantProfile->status === 'approved') text-green-600
                                @elseif(auth()->user()->restaurantProfile->status === 'pending') text-yellow-600
                                @else text-red-600">
                                {{ ucfirst(auth()->user()->restaurantProfile->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                @if(auth()->user()->restaurantProfile->status === 'pending')
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                        Pending Approval
                    </span>
                @endif
            </div>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Restaurant Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Restaurant Information</h2>
                <form method="POST" action="{{ route('restaurant.profile.update') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant Name</label>
                            <input type="text" name="restaurant_name" value="{{ auth()->user()->restaurantProfile->restaurant_name }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cuisine Type</label>
                            <input type="text" name="cuisine_type" value="{{ auth()->user()->restaurantProfile->cuisine_type }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant Capacity</label>
                            <input type="text" name="restaurant_capacity" value="{{ auth()->user()->restaurantProfile->restaurant_capacity }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business License</label>
                            <input type="text" name="business_license" value="{{ auth()->user()->restaurantProfile->business_license }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ auth()->user()->restaurantProfile->description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ auth()->user()->restaurantProfile->address }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="text" name="latitude" value="{{ auth()->user()->restaurantProfile->latitude }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="text" name="longitude" value="{{ auth()->user()->restaurantProfile->longitude }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="space-y-6">
                <!-- Donation Statistics -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Donation Statistics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Food Listings</span>
                            <span class="font-semibold text-gray-900">{{ auth()->user()->createdFoodListings()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Active Listings</span>
                            <span class="font-semibold text-green-600">{{ auth()->user()->createdFoodListings()->where('status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Matches</span>
                            <span class="font-semibold text-blue-600">{{ auth()->user()->createdFoodListings()->withCount('matches')->sum('matches_count') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Completed Pickups</span>
                            <span class="font-semibold text-green-600">{{ auth()->user()->createdFoodListings()->whereHas('matches', function($query) {
                                $query->where('status', 'completed');
                            })->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Donations</h2>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @php
                            $recentListings = auth()->user()->createdFoodListings()->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        @foreach($recentListings as $listing)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 truncate">{{ $listing->food_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $listing->quantity }} {{ $listing->unit }}</p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $listing->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection