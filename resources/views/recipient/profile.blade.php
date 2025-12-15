@extends('layouts.recipient')

@section('title', 'Organization Profile - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Organization Profile</h1>
                    <p class="text-gray-600 mt-1">Manage your organization information and settings</p>
                </div>
                <a href="{{ route('recipient.dashboard') }}" class="text-blue-600 hover:text-blue-800">
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
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->recipient->organization_name }}</h3>
                        <p class="text-sm text-gray-600">Status: <span class="font-medium text-green-600">Active</span></p>
                        <p class="text-sm text-gray-600">Rating: <span class="font-medium text-yellow-500">★ {{ number_format(auth()->user()->recipient->rating, 1) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Organization Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Organization Information</h2>
                <form method="POST" action="{{ route('recipient.profile.update') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                            <input type="text" name="organization_name" value="{{ auth()->user()->recipient->organization_name }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input type="text" name="contact_person" value="{{ auth()->user()->recipient->contact_person }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organization Capacity</label>
                            <input type="text" name="capacity" value="{{ auth()->user()->recipient->capacity }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ auth()->user()->recipient->address }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dietary Requirements</label>
                            <textarea name="dietary_requirements" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ auth()->user()->recipient->dietary_requirements }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Needs Preferences</label>
                            <textarea name="needs_preferences" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ auth()->user()->recipient->needs_preferences }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="space-y-6">
                <!-- Collection Statistics -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Collection Statistics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Matches</span>
                            <span class="font-semibold text-gray-900">{{ auth()->user()->recipient->matches()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Completed Pickups</span>
                            <span class="font-semibold text-green-600">{{ auth()->user()->recipient->matches()->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Average Rating</span>
                            <span class="font-semibold text-yellow-600">★ {{ number_format(auth()->user()->recipient->rating, 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Active This Month</span>
                            <span class="font-semibold text-blue-600">{{ auth()->user()->recipient->matches()->whereMonth('created_at', now()->month)->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Collections</h2>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @php
                            $recentMatches = auth()->user()->recipient->matches()->with('foodListing.restaurantProfile')->orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        @foreach($recentMatches as $match)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 truncate">{{ $match->foodListing->food_name }}</p>
                                    <p class="text-sm text-gray-600">
    {{ $match->foodListing->restaurantProfile ? $match->foodListing->restaurantProfile->restaurant_name : $match->foodListing->creator->name ?? 'Unknown Donor' }}
</p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $match->created_at->diffForHumans() }}
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