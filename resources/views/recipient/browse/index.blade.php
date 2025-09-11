@extends('layouts.recipient')

@section('title', 'Browse Food - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="text-center sm:text-left mb-4">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Browse Available Food</h1>
                <p class="text-gray-600 mt-1">Discover food donations near you</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 justify-center sm:justify-end">
                <a href="{{ route('recipient.browse.map') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span class="hidden sm:inline">Map View</span>
                    <span class="sm:hidden">Map</span>
                </a>
                <a href="{{ route('recipient.matches.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">My Matches</span>
                    <span class="sm:hidden">Matches</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-8">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Search food items..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="radius" class="block text-sm font-medium text-gray-700 mb-1">Radius (km)</label>
                        <select name="radius" id="radius" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="5" {{ $radius == 5 ? 'selected' : '' }}>5 km</option>
                            <option value="10" {{ $radius == 10 ? 'selected' : '' }}>10 km</option>
                            <option value="20" {{ $radius == 20 ? 'selected' : '' }}>20 km</option>
                            <option value="50" {{ $radius == 50 ? 'selected' : '' }}>50 km</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-center md:justify-end">
                    <button type="submit" class="w-full md:w-auto bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors font-medium">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Listings Grid -->
        @if($listings->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($listings as $listing)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($listing->images && count($listing->images) > 0)
                            <div class="h-48 bg-gray-200">
                                <img src="{{ asset('storage/' . $listing->images[0]) }}" 
                                     alt="{{ $listing->food_name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="p-4 sm:p-6">
                            <div class="mb-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 pr-2">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $listing->food_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $listing->user->name }}</p>
                                        @if($listing->user->restaurant_name)
                                            <p class="text-xs text-gray-500">{{ $listing->user->restaurant_name }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $listing->quantity }} {{ $listing->unit }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($listing->category) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($listing->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if(isset($listing->distance))
                                    {{ number_format($listing->distance, 1) }}km away
                                @else
                                    {{ $listing->pickup_location }}
                                @endif
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Expires {{ $listing->expiry_date->format('M d, Y') }}
                                @if($listing->expiry_time)
                                    at {{ $listing->expiry_time->format('H:i') }}
                                @endif
                            </div>
                            
                            <div class="space-y-2">
                                <a href="{{ route('recipient.browse.show', $listing) }}" 
                                   class="block w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium text-center">
                                    View Details
                                </a>
                                @if($listing->matches->where('recipient_id', auth()->id())->count() > 0)
                                    <span class="block w-full bg-gray-100 text-gray-500 py-2 px-4 rounded-md text-sm font-medium text-center">
                                        Interest Expressed
                                    </span>
                                @else
                                    <form action="{{ route('recipient.browse.express-interest', $listing) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                                            Express Interest
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination would go here if using paginate() -->
            
        @else
            <div class="bg-white rounded-lg shadow-lg p-6 sm:p-12">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-base sm:text-lg font-medium text-gray-900">No food listings found</h3>
                    <p class="mt-2 text-sm text-gray-500 px-4">
                        Try adjusting your search criteria or expanding your search radius.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('recipient.browse.index') }}" 
                           class="inline-block bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection