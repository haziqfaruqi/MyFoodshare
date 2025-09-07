@extends('layouts.restaurant')

@section('title', 'View Listing - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('restaurant.listings.index') }}" class="flex items-center text-gray-600 hover:text-gray-900 mb-4">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Listings
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $listing->food_name }}</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full 
                            {{ $listing->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($listing->status === 'matched' ? 'bg-blue-100 text-blue-800' : 
                               ($listing->status === 'picked_up' ? 'bg-purple-100 text-purple-800' : 
                               ($listing->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                            {{ ucfirst($listing->status) }}
                        </span>
                        <span class="text-sm text-gray-500">Created {{ $listing->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if($listing->status === 'active')
                        <a href="{{ route('restaurant.listings.edit', $listing) }}" 
                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Edit Listing
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Images -->
                @if($listing->images && count($listing->images) > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Images</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($listing->images as $image)
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Food image" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-sm text-gray-900">{{ $listing->category }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <p class="text-sm text-gray-900">{{ $listing->quantity }} {{ $listing->unit }}</p>
                        </div>
                        @if($listing->description)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-sm text-gray-900">{{ $listing->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Expiry Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Expiry Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Best Before Date</label>
                            <p class="text-sm text-gray-900">{{ $listing->expiry_date->format('M d, Y') }}</p>
                        </div>
                        @if($listing->expiry_time)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Best Before Time</label>
                                <p class="text-sm text-gray-900">{{ $listing->expiry_time->format('g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                    @if($listing->isExpired())
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-red-700 font-medium">This listing has expired</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pickup Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pickup Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Location</label>
                            <p class="text-sm text-gray-900">{{ $listing->pickup_location }}</p>
                        </div>
                        @if($listing->special_instructions)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <p class="text-sm text-gray-900">{{ $listing->special_instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dietary Information -->
                @if($listing->dietary_info && count($listing->dietary_info) > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Dietary Information</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($listing->dietary_info as $dietary)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    {{ $dietary }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($listing->status) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Matches</span>
                            <span class="text-sm font-medium text-gray-900">{{ $matches->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $listing->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Matches -->
                @if($matches->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Matches ({{ $matches->count() }})</h3>
                        <div class="space-y-3">
                            @foreach($matches as $match)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $match->recipient->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $match->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $match->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($match->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($match->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($match->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($listing->status === 'active')
                            <a href="{{ route('restaurant.listings.edit', $listing) }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Listing
                            </a>
                        @endif
                        <form action="{{ route('restaurant.listings.destroy', $listing) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this listing?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Listing
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection