@extends('layouts.admin')

@section('title', 'Active Listing Details - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Active Food Listing</h1>
                <p class="text-gray-600 mt-1">Detailed view and management of active donation</p>
            </div>
            <a href="{{ route('admin.active-listings.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Active Listings
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Food Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Food Information</h2>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($listing->status) }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($listing->approval_status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Food Name</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $listing->food_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                {{ ucfirst($listing->category) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <p class="text-gray-900 font-semibold">{{ $listing->quantity }} {{ $listing->unit }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                            <div>
                                <p class="text-gray-900">{{ $listing->expiry_date->format('M d, Y') }}</p>
                                @if($listing->expiry_time)
                                    <p class="text-sm text-gray-500">at {{ $listing->expiry_time->format('H:i') }}</p>
                                @endif
                                <p class="text-xs mt-1
                                    @if($listing->expiry_date->isToday()) text-yellow-600
                                    @elseif($listing->expiry_date->isPast()) text-red-600
                                    @else text-gray-500
                                    @endif">
                                    {{ $listing->expiry_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $listing->description ?: 'No description provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pickup & Location -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Pickup Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Location</label>
                            <p class="text-gray-900">{{ $listing->pickup_location ?: 'Not specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $listing->pickup_address ?: 'Not provided' }}</p>
                        </div>
                        
                        @if($listing->latitude && $listing->longitude)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GPS Coordinates</label>
                                <p class="text-gray-900 font-mono text-sm">{{ $listing->latitude }}, {{ $listing->longitude }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location Map</label>
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">Map integration available</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($listing->special_instructions)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <p class="text-gray-900 bg-yellow-50 p-3 rounded border">{{ $listing->special_instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Matches & Activity -->
                @if($listing->matches->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Recipient Matches</h2>
                        
                        <div class="space-y-4">
                            @foreach($listing->matches as $match)
                                <div class="border rounded-lg p-4 {{ $match->status === 'approved' ? 'border-green-200 bg-green-50' : 'border-gray-200' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-blue-600">{{ substr($match->recipient->name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $match->recipient->name }}</p>
                                                @if($match->recipient->organization_name)
                                                    <p class="text-sm text-gray-500">{{ $match->recipient->organization_name }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400">Matched {{ $match->matched_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($match->status === 'approved') bg-green-100 text-green-800
                                                @elseif($match->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                            @if($match->distance)
                                                <p class="text-xs text-gray-500 mt-1">{{ number_format($match->distance, 1) }}km away</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Timeline</h2>
                    
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Listing created by <span class="font-medium text-gray-900">{{ $listing->donor->name }}</span>
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $listing->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            @if($listing->approved_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Approved by <span class="font-medium text-gray-900">{{ $listing->approver->name ?? 'Admin' }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-400">{{ $listing->approved_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            
                            @foreach($listing->matches->take(3) as $match)
                                <li>
                                    <div class="relative {{ $loop->last ? '' : 'pb-8' }}">
                                        <div class="relative flex space-x-3">
                                            <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Matched with <span class="font-medium text-gray-900">{{ $match->recipient->name }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-400">{{ $match->matched_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Donor Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Donor Information</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm text-gray-600">Contact Person</label>
                            <p class="font-medium">{{ $listing->donor->name }}</p>
                        </div>
                        
                        @if($listing->donor->restaurant_name)
                            <div>
                                <label class="block text-sm text-gray-600">Restaurant</label>
                                <p class="font-medium">{{ $listing->donor->restaurant_name }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm text-gray-600">Email</label>
                            <p class="text-sm">{{ $listing->donor->email }}</p>
                        </div>
                        
                        @if($listing->donor->phone)
                            <div>
                                <label class="block text-sm text-gray-600">Phone</label>
                                <p class="text-sm">{{ $listing->donor->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Matches</span>
                            <span class="font-medium">{{ $listing->matches->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Pending Matches</span>
                            <span class="font-medium">{{ $listing->matches->where('status', 'pending')->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Days Active</span>
                            <span class="font-medium">{{ $listing->created_at->diffInDays(now()) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Days Until Expiry</span>
                            <span class="font-medium {{ $listing->expiry_date->isPast() ? 'text-red-600' : ($listing->expiry_date->isToday() ? 'text-yellow-600' : '') }}">
                                {{ $listing->expiry_date->diffInDays(now()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Actions</h3>
                    
                    <div class="space-y-3">
                        @if($listing->expiry_date->isToday() || $listing->expiry_date->isPast())
                            <form method="POST" action="{{ route('admin.active-listings.expire', $listing) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 transition-colors text-sm font-medium"
                                        onclick="return confirm('Mark this listing as expired?')">
                                    Mark as Expired
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.active-listings.deactivate', $listing) }}">
                            @csrf
                            @method('PATCH')
                            <textarea name="reason" placeholder="Reason for deactivation..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 text-sm" 
                                      rows="2"></textarea>
                            <button type="submit" class="w-full mt-2 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors text-sm font-medium"
                                    onclick="return confirm('Are you sure you want to deactivate this listing?')">
                                Deactivate Listing
                            </button>
                        </form>
                    </div>
                </div>

                @if($listing->admin_notes)
                    <!-- Admin Notes -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h3>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">{{ $listing->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection