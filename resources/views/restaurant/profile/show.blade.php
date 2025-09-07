@extends('layouts.restaurant')

@section('title', 'Restaurant Profile - MyFoodshare')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Restaurant Profile</h1>
                <p class="text-gray-600 mt-1">Manage your restaurant information and settings</p>
            </div>
            <a href="{{ route('restaurant.profile.edit') }}" 
               class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-medium flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Profile Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <p class="text-gray-900 font-medium">{{ $user->name ?: 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <p class="text-gray-900 font-medium">{{ $user->phone ?: 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant Name</label>
                            <p class="text-gray-900 font-medium">{{ $user->restaurant_name ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Restaurant Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cuisine Type</label>
                            <p class="text-gray-900 font-medium">{{ $user->cuisine_type ?: 'Not specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant Capacity</label>
                            <p class="text-gray-900 font-medium">
                                {{ $user->restaurant_capacity ? $user->restaurant_capacity . ' people' : 'Not specified' }}
                            </p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $user->description ?: 'No description provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Location Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $user->address ?: 'No address provided' }}</p>
                        </div>
                        
                        @if($user->latitude && $user->longitude)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                    <p class="text-gray-900 font-mono">{{ $user->latitude }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                    <p class="text-gray-900 font-mono">{{ $user->longitude }}</p>
                                </div>
                            </div>
                            
                            <!-- Interactive Map -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location Preview</label>
                                <div id="map" class="w-full h-48 rounded-lg border"></div>
                                <p class="text-xs text-gray-500 mt-1 text-center">{{ $user->latitude }}, {{ $user->longitude }}</p>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-yellow-800 font-medium">GPS Location Not Set</p>
                                        <p class="text-yellow-700 text-sm">Add GPS coordinates to enable location-based matching</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account Type</span>
                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($user->role) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        
                        @if($user->approved_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Approved</span>
                                <span class="text-sm font-medium text-gray-900">{{ $user->approved_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Verification Details (Read-only) -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Details</h3>
                    <p class="text-sm text-gray-500 mb-4">These details cannot be edited. Contact admin for changes.</p>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Business License</label>
                            <p class="text-sm text-gray-900">{{ $user->business_license ?: 'Not provided' }}</p>
                        </div>
                        
                        @if($user->admin_notes)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Admin Notes</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ $user->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('restaurant.listings.create') }}" 
                           class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium block text-center">
                            Create New Listing
                        </a>
                        <a href="{{ route('restaurant.listings.index') }}" 
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium block text-center">
                            View My Listings
                        </a>
                        <a href="{{ route('restaurant.dashboard') }}" 
                           class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors text-sm font-medium block text-center">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($user->latitude && $user->longitude)
        // Initialize map
        const map = L.map('map').setView([{{ $user->latitude }}, {{ $user->longitude }}], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add marker for restaurant location
        const marker = L.marker([{{ $user->latitude }}, {{ $user->longitude }}])
            .addTo(map)
            .bindPopup('{{ $user->restaurant_name ?: "Restaurant Location" }}')
            .openPopup();
            
        // Disable dragging and zooming for read-only view
        map.dragging.disable();
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
        map.boxZoom.disable();
        map.keyboard.disable();
        if (map.tap) map.tap.disable();
    @else
        // Show placeholder if no coordinates
        document.getElementById('map').innerHTML = '<div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center border"><div class="text-center"><svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><p class="mt-2 text-sm text-gray-600">No location set</p></div></div>';
    @endif
});
</script>
@endpush

@endsection