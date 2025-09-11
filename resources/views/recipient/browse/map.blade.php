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
                        <!-- Map will be loaded here -->
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

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
let map;
let markers = [];
let userMarker = null;

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});

function initMap() {
    try {
        const listings = @json($listings->values());
        const userLocation = [{{ auth()->user()->latitude ?? 3.1390 }}, {{ auth()->user()->longitude ?? 101.6869 }}];

        // Initialize the map
        map = L.map('map').setView(userLocation, 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // Custom icons
        const userIcon = L.divIcon({
            html: `<div class="w-6 h-6 bg-blue-500 border-2 border-white rounded-full shadow-lg"></div>`,
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const foodIcon = L.divIcon({
            html: `<div class="w-8 h-8 bg-green-500 border-2 border-white rounded-full shadow-lg flex items-center justify-center">
                     <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                       <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                     </svg>
                   </div>`,
            className: 'custom-div-icon',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const interestedIcon = L.divIcon({
            html: `<div class="w-8 h-8 bg-yellow-500 border-2 border-white rounded-full shadow-lg flex items-center justify-center">
                     <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                       <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                     </svg>
                   </div>`,
            className: 'custom-div-icon',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        // Add user location marker
        userMarker = L.marker(userLocation, { icon: userIcon })
            .addTo(map)
            .bindPopup('<div class="text-center"><strong>Your Location</strong></div>');

        // Add markers for each food listing
        listings.forEach(listing => {
            if (listing.latitude && listing.longitude) {
                addMarker(listing, foodIcon, interestedIcon);
            }
        });

        // Auto-fit map to show all markers if there are any listings
        if (markers.length > 0) {
            const group = new L.featureGroup([...markers, userMarker]);
            map.fitBounds(group.getBounds().pad(0.1));
            
            // Set max zoom
            if (map.getZoom() > 15) {
                map.setZoom(15);
            }
        }

    } catch (error) {
        console.error('Error initializing map:', error);
        showMapError();
    }
}

function addMarker(listing, foodIcon, interestedIcon) {
    const position = [parseFloat(listing.latitude), parseFloat(listing.longitude)];
    
    // Check if user has shown interest
    const hasInterest = listing.matches && listing.matches.some(match => 
        match.recipient_id === {{ auth()->id() }}
    );
    
    const icon = hasInterest ? interestedIcon : foodIcon;
    
    const marker = L.marker(position, { icon: icon }).addTo(map);

    // Create popup content
    const popupContent = `
        <div class="p-2 max-w-xs">
            <h3 class="font-semibold text-gray-900 mb-1">${listing.food_name}</h3>
            <p class="text-sm text-gray-600 mb-2">${listing.user?.name || 'Unknown donor'}</p>
            <p class="text-sm text-green-600 font-medium mb-2">${listing.quantity} ${listing.unit}</p>
            <p class="text-xs text-gray-500 mb-2">${listing.pickup_location}</p>
            <p class="text-xs text-gray-500 mb-3">Expires: ${new Date(listing.expiry_date).toLocaleDateString()}</p>
            <div class="flex space-x-2">
                <a href="/recipient/browse/${listing.id}" 
                   class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition-colors">
                    View Details
                </a>
                ${hasInterest ? 
                    '<span class="bg-gray-100 text-gray-500 px-3 py-1 rounded text-xs">Interested</span>' :
                    `<form action="/recipient/browse/${listing.id}/express-interest" method="POST" class="inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                            Express Interest
                        </button>
                    </form>`
                }
            </div>
        </div>
    `;

    marker.bindPopup(popupContent);
    
    // Add click event to highlight sidebar item
    marker.on('click', function() {
        highlightListingCard(listing.id);
    });

    // Store listing ID with marker for later reference
    marker.listingId = listing.id;
    markers.push(marker);
}

function focusMapLocation(lat, lng) {
    if (map) {
        const position = [parseFloat(lat), parseFloat(lng)];
        map.setView(position, 16);
        
        // Find and open popup for the corresponding marker
        const marker = markers.find(m => {
            const markerPos = m.getLatLng();
            return Math.abs(markerPos.lat - lat) < 0.0001 && Math.abs(markerPos.lng - lng) < 0.0001;
        });
        
        if (marker) {
            marker.openPopup();
        }
    }
    
    // Highlight the selected listing in sidebar
    highlightListingCard(event.currentTarget.dataset.listingId);
}

function highlightListingCard(listingId) {
    document.querySelectorAll('[data-listing-id]').forEach(el => {
        el.classList.remove('bg-green-50', 'border-green-200');
    });
    
    const targetCard = document.querySelector(`[data-listing-id="${listingId}"]`);
    if (targetCard) {
        targetCard.classList.add('bg-green-50', 'border-green-200');
        targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function showMapError() {
    document.getElementById('map').innerHTML = `
        <div class="w-full h-full flex items-center justify-center bg-gray-100">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Map Not Available</h3>
                <p class="mt-2 text-sm text-gray-500">Unable to load map</p>
                <p class="mt-1 text-xs text-gray-400">Showing {{ $listings->count() }} food locations within {{ $radius }}km</p>
            </div>
        </div>
    `;
}

// Add custom CSS for markers
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .custom-div-icon {
            background: none !important;
            border: none !important;
        }
    </style>
`);
</script>
@endsection