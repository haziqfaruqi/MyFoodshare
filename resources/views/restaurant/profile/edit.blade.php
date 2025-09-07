@extends('layouts.restaurant')

@section('title', 'Edit Restaurant Profile - MyFoodshare')

@push('head')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

<style>
#map {
    height: 400px;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
}
.leaflet-popup-content {
    font-size: 14px;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Restaurant Profile</h1>
                <p class="text-gray-600 mt-1">Update your restaurant information</p>
            </div>
            <a href="{{ route('restaurant.profile.show') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition-colors text-sm font-medium flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Profile
            </a>
        </div>

        <form method="POST" action="{{ route('restaurant.profile.update') }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Contact Person Name *</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                               placeholder="+60 12-345 6789">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="restaurant_name" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Name</label>
                        <input type="text" name="restaurant_name" id="restaurant_name" 
                               value="{{ old('restaurant_name', $user->restaurant_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('restaurant_name') border-red-500 @enderror"
                               placeholder="Your Restaurant Name">
                        @error('restaurant_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Restaurant Details -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Restaurant Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="cuisine_type" class="block text-sm font-medium text-gray-700 mb-2">Cuisine Type</label>
                        <select name="cuisine_type" id="cuisine_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('cuisine_type') border-red-500 @enderror">
                            <option value="">Select cuisine type...</option>
                            <option value="Malaysian" {{ old('cuisine_type', $user->cuisine_type) === 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                            <option value="Chinese" {{ old('cuisine_type', $user->cuisine_type) === 'Chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="Indian" {{ old('cuisine_type', $user->cuisine_type) === 'Indian' ? 'selected' : '' }}>Indian</option>
                            <option value="Western" {{ old('cuisine_type', $user->cuisine_type) === 'Western' ? 'selected' : '' }}>Western</option>
                            <option value="Italian" {{ old('cuisine_type', $user->cuisine_type) === 'Italian' ? 'selected' : '' }}>Italian</option>
                            <option value="Japanese" {{ old('cuisine_type', $user->cuisine_type) === 'Japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="Korean" {{ old('cuisine_type', $user->cuisine_type) === 'Korean' ? 'selected' : '' }}>Korean</option>
                            <option value="Thai" {{ old('cuisine_type', $user->cuisine_type) === 'Thai' ? 'selected' : '' }}>Thai</option>
                            <option value="Mixed" {{ old('cuisine_type', $user->cuisine_type) === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                            <option value="Other" {{ old('cuisine_type', $user->cuisine_type) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('cuisine_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="restaurant_capacity" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Capacity</label>
                        <input type="number" name="restaurant_capacity" id="restaurant_capacity" 
                               value="{{ old('restaurant_capacity', $user->restaurant_capacity) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('restaurant_capacity') border-red-500 @enderror"
                               placeholder="Number of people" min="1">
                        @error('restaurant_capacity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Describe your restaurant, specialties, and any additional information...">{{ old('description', $user->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Location Information</h2>
                <p class="text-sm text-gray-600 mb-6">Providing accurate location helps recipients find nearby food donations.</p>
                
                <div class="space-y-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Address</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                  placeholder="Full address including street, city, state, postal code">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Interactive Map -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Set Location on Map
                            <span class="text-xs text-gray-500">(Click on the map to set your restaurant's location)</span>
                        </label>
                        <div id="map" class="mb-4"></div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Click anywhere on the map to set your restaurant's precise location</span>
                            <button type="button" onclick="getCurrentLocation()" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                                Use Current Location
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Latitude 
                                <span class="text-xs text-gray-500">(Auto-filled from map)</span>
                            </label>
                            <input type="number" name="latitude" id="latitude" step="any"
                                   value="{{ old('latitude', $user->latitude) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                                   placeholder="e.g., 3.1390" readonly>
                            @error('latitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Longitude 
                                <span class="text-xs text-gray-500">(Auto-filled from map)</span>
                            </label>
                            <input type="number" name="longitude" id="longitude" step="any"
                                   value="{{ old('longitude', $user->longitude) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                                   placeholder="e.g., 101.6869" readonly>
                            @error('longitude')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <div class="text-sm">
                                <p class="text-green-800 font-medium">Interactive Map Instructions</p>
                                <ul class="text-green-700 mt-1 list-disc list-inside">
                                    <li>Click anywhere on the map to set your restaurant's location</li>
                                    <li>Use the "Use Current Location" button to auto-detect your location</li>
                                    <li>Drag the red marker to fine-tune your position</li>
                                    <li>Coordinates will automatically update in the fields below</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Non-Editable Information -->
            <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Non-Editable Information
                </h3>
                <p class="text-gray-600 text-sm mb-4">The following information cannot be changed and requires admin assistance:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Account Status</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst($user->status) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Business License</label>
                        <p class="text-gray-900">{{ $user->business_license ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Account Type</label>
                        <p class="text-gray-900">{{ ucfirst($user->role) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Member Since</label>
                        <p class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('restaurant.profile.show') }}" 
                   class="bg-gray-100 text-gray-700 py-2 px-6 rounded-md hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-orange-600 text-white py-2 px-6 rounded-md hover:bg-orange-700 transition-colors font-medium">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default coordinates (Kuala Lumpur city center)
    const defaultLat = {{ old('latitude', $user->latitude) ?: '3.1390' }};
    const defaultLng = {{ old('longitude', $user->longitude) ?: '101.6869' }};
    
    // Initialize the map
    const map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Add marker
    let marker = null;
    
    // If coordinates exist, add marker
    if (defaultLat && defaultLng && defaultLat !== '3.1390' && defaultLng !== '101.6869') {
        marker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map);
        
        marker.bindPopup('<b>{{ $user->restaurant_name ?: $user->name }}</b><br/>Your restaurant location').openPopup();
        
        // Handle marker drag
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    }
    
    // Handle map clicks
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng], {
            draggable: true,
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map);
        
        marker.bindPopup('<b>{{ $user->restaurant_name ?: $user->name }}</b><br/>Your restaurant location').openPopup();
        
        // Update coordinates
        updateCoordinates(lat, lng);
        
        // Handle marker drag
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    });
    
    // Function to update coordinate inputs
    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        
        // Remove readonly to allow form submission
        document.getElementById('latitude').removeAttribute('readonly');
        document.getElementById('longitude').removeAttribute('readonly');
        
        // Visual feedback
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        latInput.classList.add('bg-green-50', 'border-green-300');
        lngInput.classList.add('bg-green-50', 'border-green-300');
        
        setTimeout(() => {
            latInput.classList.remove('bg-green-50', 'border-green-300');
            lngInput.classList.remove('bg-green-50', 'border-green-300');
        }, 1500);
    }
    
    // Make updateCoordinates function globally available
    window.updateCoordinates = updateCoordinates;
    window.map = map;
    window.marker = marker;
});

// Get current location function
function getCurrentLocation() {
    if (navigator.geolocation) {
        // Show loading state
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Getting location...';
        button.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Update map view
                window.map.setView([lat, lng], 15);
                
                // Remove existing marker
                if (window.marker) {
                    window.map.removeLayer(window.marker);
                }
                
                // Add new marker
                window.marker = L.marker([lat, lng], {
                    draggable: true,
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(window.map);
                
                window.marker.bindPopup('<b>Current Location</b><br/>Detected automatically').openPopup();
                
                // Update coordinates
                window.updateCoordinates(lat, lng);
                
                // Handle marker drag
                window.marker.on('dragend', function(e) {
                    const position = window.marker.getLatLng();
                    window.updateCoordinates(position.lat, position.lng);
                });
                
                // Reset button
                button.textContent = originalText;
                button.disabled = false;
            },
            function(error) {
                alert('Error getting your location: ' + error.message);
                
                // Reset button
                button.textContent = originalText;
                button.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}
</script>
@endpush