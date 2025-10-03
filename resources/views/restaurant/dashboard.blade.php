@extends('layouts.restaurant')

@section('title', 'Restaurant Dashboard - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ auth()->user()->restaurant_name }} Dashboard
            </p>
        </div>

        <!-- Impact Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <div class="text-sm font-medium text-gray-500">Meals Provided</div>
                    <div class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($impactStats['meals_provided']) }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l-3-9"></path>
                    </svg>
                    <div class="text-sm font-medium text-gray-500">Food Waste Reduced</div>
                    <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($impactStats['food_waste_reduced'], 1) }} kg</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <div class="text-sm font-medium text-gray-500">Total Donations</div>
                    <div class="text-xl sm:text-2xl font-bold text-purple-600">{{ number_format($impactStats['total_donations']) }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm font-medium text-gray-500">Completed Pickups</div>
                    <div class="text-xl sm:text-2xl font-bold text-orange-600">{{ number_format($impactStats['completed_pickups']) }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <a href="{{ route('restaurant.listings.create') }}" class="bg-green-600 p-4 sm:p-6 rounded-lg hover:bg-green-700 transition-all duration-200 shadow-lg group text-white">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-green-100 text-xs sm:text-sm font-medium opacity-80">Create New</p>
                    <p class="text-sm sm:text-lg font-semibold mt-1">Food Listing</p>
                </div>
            </a>

            <a href="{{ route('restaurant.listings.index') }}" class="bg-white hover:bg-gray-50 border-2 border-blue-500 p-4 sm:p-6 rounded-lg transition-all duration-200 shadow-lg group">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto mb-2 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-blue-600 text-xs sm:text-sm font-medium opacity-80">Manage</p>
                    <p class="text-blue-600 text-sm sm:text-lg font-semibold mt-1">Listings</p>
                </div>
            </a>

            <a href="{{ route('restaurant.track-donations') }}" class="bg-white hover:bg-gray-50 border-2 border-purple-500 p-4 sm:p-6 rounded-lg transition-all duration-200 shadow-lg group">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto mb-2 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-purple-600 text-xs sm:text-sm font-medium opacity-80">Track</p>
                    <p class="text-purple-600 text-sm sm:text-lg font-semibold mt-1">Donations</p>
                </div>
            </a>

            <a href="{{ route('restaurant.reports') }}" class="bg-white hover:bg-gray-50 border-2 border-orange-500 p-4 sm:p-6 rounded-lg transition-all duration-200 shadow-lg group">
                <div class="text-center">
                    <svg class="h-8 w-8 mx-auto mb-2 text-orange-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-orange-600 text-xs sm:text-sm font-medium opacity-80">View</p>
                    <p class="text-orange-600 text-sm sm:text-lg font-semibold mt-1">Reports</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Stats Overview -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Overview</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div class="text-center p-3 sm:p-4 bg-green-50 rounded-lg">
                            <div class="text-lg sm:text-2xl font-bold text-green-600">{{ $stats['total_listings'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Total Listings</div>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-blue-50 rounded-lg">
                            <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $stats['active_listings'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Active Listings</div>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-purple-50 rounded-lg">
                            <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ $stats['total_matches'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Total Matches</div>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-orange-50 rounded-lg">
                            <div class="text-lg sm:text-2xl font-bold text-orange-600">{{ $stats['completed_donations'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Completed</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Recent Listings</h2>
                        <a href="{{ route('restaurant.listings.index') }}" class="text-green-600 hover:text-green-700 text-xs sm:text-sm font-medium">
                            View All
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentListings as $listing)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-900 text-sm sm:text-base">{{ $listing->food_name }}</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                                            @if($listing->status === 'active') bg-green-100 text-green-800
                                            @elseif($listing->status === 'matched') bg-blue-100 text-blue-800
                                            @elseif($listing->status === 'picked_up') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            {{ $listing->quantity }} {{ $listing->unit }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate">{{ $listing->pickup_location }}</span>
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $listing->expiry_date->format('M j') }}
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
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Pending Matches</h2>
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 00-15 0v5h5l-5 5-5-5h5V7a9.5 9.5 0 0119 0v10z"></path>
                        </svg>
                    </div>
                    <div class="space-y-3">
                        @forelse($pendingMatches as $match)
                            <div class="border-l-4 border-blue-500 pl-3 py-2">
                                <p class="text-xs sm:text-sm text-gray-900 font-medium">{{ $match->recipient->organization_name }}</p>
                                <p class="text-xs text-gray-600">{{ $match->foodListing->food_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $match->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No pending matches</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Today's Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Active Listings</span>
                            <span class="font-semibold text-green-600">{{ $stats['active_listings'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pending Matches</span>
                            <span class="font-semibold text-blue-600">{{ $pendingMatches->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Matches</span>
                            <span class="font-semibold text-purple-600">{{ $stats['total_matches'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Completed</span>
                            <span class="font-semibold text-orange-600">{{ $stats['completed_donations'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="bg-gradient-to-br from-green-500 to-blue-500 rounded-lg shadow-lg p-4 sm:p-6 text-white">
                    <h2 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Need Help?</h2>
                    <p class="text-green-100 mb-3 sm:mb-4 text-xs sm:text-sm">
                        Our support team is here to help you maximize your impact.
                    </p>
                    <a href="{{ route('contact') }}" class="bg-white text-green-600 px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium hover:bg-gray-100 transition-colors inline-block">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
// Initialize Pusher
const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: {{ env("PUSHER_SCHEME") === "https" ? "true" : "false" }}
});

// QR Code Management Functions
async function generateQrCode(listingId) {
    const button = document.querySelector(`[data-listing-id="${listingId}"] .generate-qr-btn`);
    const container = document.querySelector(`[data-listing-id="${listingId}"] .qr-code-container`);

    try {
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Generating...';

        const response = await fetch(`/api/restaurant/listings/${listingId}/generate-qr`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + '{{ auth()->user()->createToken("qr-generation")->plainTextToken ?? "" }}',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.qr_code_image) {
            // Show QR code
            container.querySelector('.qr-code-image').src = data.qr_code_image;
            container.classList.remove('hidden');
            button.innerHTML = 'QR Code Generated';
            button.classList.replace('bg-blue-600', 'bg-green-600');

            // Setup real-time listening for this pickup
            setupPickupTracking(data.verification_code, listingId);

            // Show pickup updates section
            const updatesContainer = document.querySelector(`[data-listing-id="${listingId}"] .pickup-updates`);
            updatesContainer.classList.remove('hidden');
        } else {
            throw new Error(data.error || 'Failed to generate QR code');
        }
    } catch (error) {
        alert('Error generating QR code: ' + error.message);
        button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Generate QR Code';
        button.disabled = false;
    }
}

function downloadQrCode(listingId) {
    const img = document.querySelector(`[data-listing-id="${listingId}"] .qr-code-image`);
    const link = document.createElement('a');
    link.download = `food-pickup-qr-${listingId}.png`;
    link.href = img.src;
    link.click();
}

function setupPickupTracking(verificationCode, listingId) {
    // Subscribe to private channel for this restaurant
    const channel = pusher.subscribe(`private-restaurant.{{ auth()->id() }}`);

    channel.bind('qr-code-scanned', function(data) {
        if (data.food_listing && data.food_listing.id == listingId) {
            showScanNotification(listingId, data);
            updatePickupStatus(listingId, 'scanned');
        }
    });

    channel.bind('pickup-completed', function(data) {
        if (data.food_listing && data.food_listing.id == listingId) {
            showCompletionNotification(listingId, data);
            updatePickupStatus(listingId, 'completed');
        }
    });
}

function showScanNotification(listingId, data) {
    const notification = document.querySelector(`[data-listing-id="${listingId}"] .scan-notification`);
    const timeElement = notification.querySelector('.scan-time');

    timeElement.textContent = `Scanned at ${new Date(data.scanned_at).toLocaleTimeString()}`;
    notification.classList.remove('hidden');

    // Add animation
    notification.style.animation = 'pulse 1s ease-in-out';
}

function showCompletionNotification(listingId, data) {
    const notification = document.querySelector(`[data-listing-id="${listingId}"] .completion-notification`);
    const timeElement = notification.querySelector('.completion-time');
    const starsElement = notification.querySelector('.rating-stars');

    timeElement.textContent = `Completed at ${new Date(data.completed_at).toLocaleTimeString()}`;

    // Show star rating
    const stars = '★'.repeat(data.quality_rating || 5) + '☆'.repeat(5 - (data.quality_rating || 5));
    starsElement.textContent = stars;

    notification.classList.remove('hidden');

    // Add animation
    notification.style.animation = 'slideInRight 0.5s ease-out';
}

function updatePickupStatus(listingId, status) {
    const statusElement = document.querySelector(`[data-listing-id="${listingId}"] .pickup-status`);

    switch(status) {
        case 'scanned':
            statusElement.className = 'pickup-status inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            statusElement.textContent = 'QR Code Scanned';
            break;
        case 'completed':
            statusElement.className = 'pickup-status inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
            statusElement.textContent = 'Pickup Completed';
            // Add checkmark
            statusElement.innerHTML = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Pickup Completed';
            break;
    }
}

// Real-time notifications for general dashboard updates
const restaurantChannel = pusher.subscribe(`private-restaurant.{{ auth()->id() }}`);

restaurantChannel.bind('qr-code-scanned', function(data) {
    // Show toast notification
    showToast('QR Code Scanned', `${data.recipient.name} scanned QR code for ${data.food_listing.food_name}`, 'success');
});

restaurantChannel.bind('pickup-completed', function(data) {
    // Show toast notification
    showToast('Pickup Completed', `${data.recipient.name} completed pickup of ${data.food_listing.food_name}`, 'success');

    // Update stats
    updateDashboardStats();
});

function showToast(title, message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 translate-x-full`;

    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

    toast.innerHTML = `
        <div class="flex items-start p-4">
            <div class="flex-shrink-0">
                <div class="w-2 h-2 ${bgColor} rounded-full"></div>
            </div>
            <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium text-gray-900">${title}</p>
                <p class="mt-1 text-sm text-gray-500">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

async function updateDashboardStats() {
    try {
        const response = await fetch(`{{ route('restaurant.dashboard') }}?ajax=1`);
        if (response.ok) {
            // This would require updating the controller to return JSON when requested
            // For now, we'll just refresh certain elements
            location.reload();
        }
    } catch (error) {
        console.error('Failed to update dashboard stats:', error);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
@endsection