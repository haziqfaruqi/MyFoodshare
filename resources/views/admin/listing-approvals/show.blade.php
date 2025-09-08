@extends('layouts.admin')

@section('title', 'Review Food Listing - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Review Food Listing</h1>
                <p class="text-gray-600 mt-1">Detailed review of pending food donation</p>
            </div>
            <a href="{{ route('admin.listing-approvals.index') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Listings
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Food Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Food Information</h2>
                    
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
                            <p class="text-gray-900">{{ $listing->quantity }} {{ $listing->unit }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                            <p class="text-gray-900">{{ $listing->expiry_date->format('M d, Y') }}</p>
                            @if($listing->expiry_time)
                                <p class="text-sm text-gray-500">at {{ $listing->expiry_time->format('H:i') }}</p>
                            @endif
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
                        @endif
                        
                        @if($listing->special_instructions)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <p class="text-gray-900 bg-yellow-50 p-3 rounded border">{{ $listing->special_instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Additional Information</h2>
                    
                    @if($listing->dietary_info)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Information</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($listing->dietary_info as $info)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        {{ ucfirst($info) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Submitted:</span>
                            <span class="font-medium">{{ $listing->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium capitalize">{{ $listing->approval_status }}</span>
                        </div>
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
                        
                        <div>
                            <label class="block text-sm text-gray-600">Account Status</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($listing->donor->status === 'active') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($listing->donor->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($listing->approval_status === 'pending')
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        
                        <div class="space-y-4">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.listing-approvals.approve', $listing) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                                    <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                              rows="2" placeholder="Any notes for the donor..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                                    Approve Listing
                                </button>
                            </form>
                            
                            <!-- Reject Button -->
                            <button onclick="showRejectModal()" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors font-medium">
                                Reject Listing
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                @if($listing->approval_status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($listing->approval_status) }}
                            </span>
                            @if($listing->approved_at)
                                <p class="text-sm text-gray-500 mt-2">{{ $listing->approved_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Matches -->
                @if($listing->matches->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recipient Matches</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $listing->matches->count() }} recipient(s) interested</p>
                        
                        <div class="space-y-2">
                            @foreach($listing->matches->take(3) as $match)
                                <div class="flex items-center justify-between text-sm">
                                    <span>{{ $match->recipient->name }}</span>
                                    @if($match->distance)
                                        <span class="text-gray-500">{{ number_format($match->distance, 1) }}km</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form method="POST" action="{{ route('admin.listing-approvals.reject', $listing) }}">
            @csrf
            @method('PATCH')
            <div class="mt-3 text-center">
                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Reject Food Listing</h3>
                <p class="text-sm text-gray-500 mt-2">Please provide a reason for rejecting this listing</p>
                
                <div class="mt-4 text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection *</label>
                    <textarea name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                              rows="3" placeholder="Please provide a clear reason for rejection..."></textarea>
                </div>
                
                <div class="flex justify-center space-x-4 mt-6">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Reject Listing
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endsection