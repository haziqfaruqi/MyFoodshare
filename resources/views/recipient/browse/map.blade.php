@extends('layouts.recipient')

@section('title', 'Map View - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Food Map</h1>
                <p class="text-gray-600 mt-1">View available food locations near you</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('recipient.browse.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    List View
                </a>
                <a href="{{ route('recipient.matches.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    My Matches
                </a>
            </div>
        </div>

        <!-- Radius Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-8">
            <form method="GET" class="flex items-center space-x-4">
                <label for="radius" class="text-sm font-medium text-gray-700">Search Radius:</label>
                <select name="radius" id="radius" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="5" {{ $radius == 5 ? 'selected' : '' }}>5 km</option>
                    <option value="10" {{ $radius == 10 ? 'selected' : '' }}>10 km</option>
                    <option value="20" {{ $radius == 20 ? 'selected' : '' }}>20 km</option>
                    <option value="50" {{ $radius == 50 ? 'selected' : '' }}>50 km</option>
                </select>
                <span class="text-sm text-gray-500">{{ $listings->count() }} locations found</span>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Map Container -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div id="map" class="w-full h-96" style="min-height: 600px;">
                        <!-- Placeholder for map -->
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Interactive Map</h3>
                                <p class="mt-2 text-sm text-gray-500">Google Maps integration would be displayed here</p>
                                <p class="mt-1 text-xs text-gray-400">Showing {{ $listings->count() }} food locations within {{ $radius }}km</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listings Sidebar -->
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nearby Food Listings</h3>
                    
                    @if($listings->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($listings as $listing)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" 
                                     onclick="focusMapLocation({{ $listing->latitude }}, {{ $listing->longitude }})" 
                                     data-listing-id="{{ $listing->id }}">
                                    
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $listing->food_name }}</h4>
                                            <p class="text-xs text-gray-600">{{ $listing->user->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $listing->quantity }} {{ $listing->unit }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
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
                                    
                                    <div class="flex items-center text-xs text-gray-500 mb-3">
                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Expires {{ $listing->expiry_date->format('M d') }}
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('recipient.browse.show', $listing) }}" 
                                           class="flex-1 bg-green-600 text-white py-1 px-3 rounded text-xs font-medium text-center hover:bg-green-700 transition-colors">
                                            View
                                        </a>
                                        @if($listing->matches->where('recipient_id', auth()->id())->count() > 0)
                                            <span class="flex-1 bg-gray-100 text-gray-500 py-1 px-3 rounded text-xs font-medium text-center">
                                                Interested
                                            </span>
                                        @else
                                            <form action="{{ route('recipient.browse.express-interest', $listing) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-blue-600 text-white py-1 px-3 rounded text-xs font-medium hover:bg-blue-700 transition-colors">
                                                    Interest
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No locations found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try expanding your search radius</p>
                        </div>
                    @endif
                </div>

                <!-- Legend -->
                <div class="bg-white rounded-lg shadow-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Map Legend</h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-gray-600">Available Food</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-gray-600">Your Location</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-gray-600">Interested</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Placeholder JavaScript for map functionality
// In a real implementation, you would integrate with Google Maps API or similar
function focusMapLocation(lat, lng) {
    console.log('Focusing on location:', lat, lng);
    // This would center the map on the selected listing
    
    // Highlight the selected listing
    document.querySelectorAll('[data-listing-id]').forEach(el => {
        el.classList.remove('bg-green-50', 'border-green-200');
    });
    event.currentTarget.classList.add('bg-green-50', 'border-green-200');
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    // This is where you would initialize your map
    console.log('Map would be initialized here');
    
    // Sample data for demonstration
    const listings = @json($listings->toArray());
    console.log('Listings for map:', listings);
});
</script>
@endsection