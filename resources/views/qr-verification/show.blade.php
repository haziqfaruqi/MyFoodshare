<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donation Verification - MyFoodshare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Food Donation Details</h1>
            <p class="text-gray-600">Scan verification successful</p>
        </div>

        <!-- Verification Success Alert -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-green-800 font-medium">Verification Successful!</h3>
                    <p class="text-green-700 text-sm">This food donation has been verified and is ready for pickup.</p>
                </div>
            </div>
        </div>

        <!-- Food Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                <h2 class="text-xl font-semibold text-gray-900">Food Details</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Food Item</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $listing->food_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <p class="text-gray-900">{{ $listing->quantity }} {{ $listing->unit }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                {{ $listing->category }}
                            </span>
                        </div>

                        @if($listing->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $listing->description }}</p>
                        </div>
                        @endif

                        @if($listing->dietary_info && count($listing->dietary_info) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Information</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($listing->dietary_info as $info)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $info }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Information</label>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-red-800 font-medium">{{ $listing->expiry_date->format('M j, Y') }}</p>
                                        @if($listing->expiry_time)
                                            <p class="text-red-700 text-sm">{{ $listing->expiry_time->format('g:i A') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($listing->status === 'active') bg-green-100 text-green-800
                                @elseif($listing->status === 'matched') bg-blue-100 text-blue-800
                                @elseif($listing->status === 'picked_up') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                            </span>
                        </div>

                        @if($listing->special_instructions)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-yellow-800">{{ $listing->special_instructions }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Donor & Pickup Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Donor Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Donor Information</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="text-gray-900">{{ $listing->user->name }}</p>
                    </div>
                    @if($listing->user->restaurant_name)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Restaurant/Organization</label>
                        <p class="text-gray-900">{{ $listing->user->restaurant_name }}</p>
                    </div>
                    @endif
                    @if($listing->user->phone)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact</label>
                        <p class="text-gray-900">{{ $listing->user->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pickup Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Pickup Location</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <p class="text-gray-900">{{ $listing->pickup_location }}</p>
                    </div>
                    @if($listing->pickup_address)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="text-gray-900">{{ $listing->pickup_address }}</p>
                    </div>
                    @endif
                    @if($listing->latitude && $listing->longitude)
                    <div>
                        <a href="https://maps.google.com/maps?q={{ $listing->latitude }},{{ $listing->longitude }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                            </svg>
                            Open in Google Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Verification Code -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Verification Code</h3>
                <div class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg">
                    <span class="text-2xl font-mono font-bold text-gray-900 tracking-wider">{{ $qrData['verification_code'] }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-2">Present this code during pickup for verification</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center space-y-4">
            @auth
                @if(auth()->user()->role === 'recipient')
                    @if($userMatch)
                        @if($userMatch->status === 'approved' || $userMatch->status === 'scheduled')
                            <!-- User has approved match - can complete pickup -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-green-800">Pickup Approved!</h3>
                                </div>
                                <p class="text-green-700 text-sm mb-4">
                                    Your request has been approved by {{ $listing->user->restaurant_name ?? $listing->user->name }}.
                                    @if($userMatch->status === 'scheduled' && $userMatch->pickup_scheduled_at)
                                        <br>Pickup scheduled for: <strong>{{ $userMatch->pickup_scheduled_at->format('M j, Y g:i A') }}</strong>
                                    @endif
                                </p>
                                
                                <form method="POST" action="{{ route('food-listing.complete-pickup', ['id' => $listing->id, 'code' => $qrData['verification_code']]) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                            onclick="return confirm('Are you sure you want to complete this pickup? This action cannot be undone.')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Complete Pickup
                                    </button>
                                </form>
                            </div>
                        @elseif($userMatch->status === 'pending')
                            <!-- User has pending match - waiting for approval -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-yellow-800">Interest Submitted</h3>
                                </div>
                                <p class="text-yellow-700 text-sm mb-2">
                                    Your interest has been submitted and is awaiting approval from {{ $listing->user->restaurant_name ?? $listing->user->name }}.
                                </p>
                                <p class="text-yellow-600 text-xs">You'll receive a notification once your request is approved.</p>
                            </div>
                        @elseif($userMatch->status === 'completed')
                            <!-- Match already completed -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-800">Pickup Completed</h3>
                                </div>
                                <p class="text-gray-700 text-sm">
                                    This pickup was completed on {{ $userMatch->completed_at->format('M j, Y g:i A') }}.
                                    Thank you for helping reduce food waste!
                                </p>
                            </div>
                        @endif
                    @else
                        <!-- User has no match - can express interest -->
                        @if($listing->status === 'active')
                            <a href="{{ route('recipient.browse.show', $listing) }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Express Interest
                            </a>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-700">This food donation is no longer available for pickup.</p>
                            </div>
                        @endif
                    @endif
                    <br>
                    <a href="{{ route('recipient.browse.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        ← Back to Browse
                    </a>
                @endif
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800 mb-3">Want to request this food donation?</p>
                    <a href="{{ route('register.recipient') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Register as Recipient
                    </a>
                </div>
            @endauth
        </div>
    </div>
</body>
</html>