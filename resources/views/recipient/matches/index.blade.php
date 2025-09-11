@extends('layouts.recipient')

@section('title', 'My Matches - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Matches</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Track your food donation interests and pickups</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-3">
                <a href="{{ route('pickup.scanner') }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors text-sm font-medium flex items-center justify-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden xs:inline">Scan QR Code</span>
                    <span class="xs:hidden">Scan QR</span>
                </a>
                <a href="{{ route('recipient.browse.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="hidden xs:inline">Browse More Food</span>
                    <span class="xs:hidden">Browse</span>
                </a>
                <a href="{{ route('recipient.browse.map') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span class="hidden xs:inline">Map View</span>
                    <span class="xs:hidden">Map</span>
                </a>
            </div>
        </div>

        <!-- Status Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:flex-wrap gap-4 sm:items-center">
                <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">Filter by status:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('recipient.matches.index') }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All
                    </a>
                    <a href="{{ route('recipient.matches.index', ['status' => 'pending']) }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('recipient.matches.index', ['status' => 'approved']) }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                        Approved
                    </a>
                    <a href="{{ route('recipient.matches.index', ['status' => 'completed']) }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') === 'completed' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                        Completed
                    </a>
                    <a href="{{ route('recipient.matches.index', ['status' => 'rejected']) }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                        Rejected
                    </a>
                    <a href="{{ route('recipient.matches.index', ['status' => 'cancelled']) }}" 
                       class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ request('status') === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Cancelled
                    </a>
                </div>
            </div>
        </div>

        <!-- Matches List -->
        @if($matches->count() > 0)
            <div class="space-y-6">
                @foreach($matches as $match)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="block lg:flex lg:items-start lg:justify-between">
                                <!-- Match Details -->
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center mb-4 gap-2 sm:gap-3">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3
                                                @if($match->status === 'completed') bg-green-500
                                                @elseif($match->status === 'approved') bg-blue-500
                                                @elseif($match->status === 'rejected') bg-red-500
                                                @elseif($match->status === 'cancelled') bg-gray-500
                                                @else bg-yellow-500
                                                @endif">
                                            </div>
                                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900">{{ $match->foodListing->food_name }}</h3>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium self-start sm:self-center
                                            @if($match->status === 'completed') bg-green-100 text-green-800
                                            @elseif($match->status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($match->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($match->status === 'cancelled') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($match->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                        <!-- Donor Info -->
                                        <div>
                                            <p class="text-gray-500 mb-1">Donor</p>
                                            <p class="font-medium text-gray-900">{{ $match->foodListing->user->name }}</p>
                                            @if($match->foodListing->user->restaurant_name)
                                                <p class="text-xs text-gray-500">{{ $match->foodListing->user->restaurant_name }}</p>
                                            @endif
                                        </div>
                                        
                                        <!-- Quantity -->
                                        <div>
                                            <p class="text-gray-500 mb-1">Quantity</p>
                                            <p class="font-medium text-gray-900">{{ $match->foodListing->quantity }} {{ $match->foodListing->unit }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($match->foodListing->category) }}</p>
                                        </div>
                                        
                                        <!-- Distance -->
                                        <div>
                                            <p class="text-gray-500 mb-1">Distance</p>
                                            <p class="font-medium text-gray-900">
                                                @if($match->distance)
                                                    {{ number_format($match->distance, 1) }} km
                                                @else
                                                    Not calculated
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">From your location</p>
                                        </div>
                                        
                                        <!-- Expiry -->
                                        <div>
                                            <p class="text-gray-500 mb-1">Best Before</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $match->foodListing->expiry_date->format('M d, Y') }}
                                            </p>
                                            @if($match->foodListing->expiry_time)
                                                <p class="text-xs text-gray-500">
                                                    @if(is_string($match->foodListing->expiry_time))
                                                        {{ $match->foodListing->expiry_time }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($match->foodListing->expiry_time)->format('H:i') }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($match->foodListing->description)
                                        <div class="mt-4">
                                            <p class="text-gray-700 text-sm line-clamp-2">{{ $match->foodListing->description }}</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Pickup Location -->
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-start">
                                            <svg class="h-4 w-4 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $match->foodListing->pickup_location }}</p>
                                                @if($match->foodListing->pickup_address)
                                                    <p class="text-xs text-gray-500">{{ $match->foodListing->pickup_address }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Special Instructions -->
                                    @if($match->foodListing->special_instructions)
                                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="h-4 w-4 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-yellow-800">Special Instructions</p>
                                                    <p class="text-sm text-yellow-700">{{ $match->foodListing->special_instructions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="lg:ml-6 mt-4 lg:mt-0 flex flex-row lg:flex-col gap-2 lg:space-y-0 lg:min-w-36">
                                    <a href="{{ route('recipient.browse.show', $match->foodListing) }}" 
                                       class="flex-1 lg:flex-none bg-green-600 text-white py-2 px-3 rounded-md hover:bg-green-700 transition-colors text-xs sm:text-sm font-medium text-center">
                                        View Details
                                    </a>
                                    
                                    @if($match->status === 'pending')
                                        <form action="{{ route('recipient.matches.cancel', $match) }}" method="POST" class="flex-1 lg:flex-none">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to cancel this match?')"
                                                    class="w-full bg-red-600 text-white py-2 px-3 rounded-md hover:bg-red-700 transition-colors text-xs sm:text-sm font-medium">
                                                Cancel Interest
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($match->status === 'confirmed')
                                        <form action="{{ route('recipient.matches.complete', $match) }}" method="POST" class="flex-1 lg:flex-none">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-blue-600 text-white py-2 px-3 rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                                                Mark Completed
                                            </button>
                                        </form>
                                        
                                        @if($match->qr_code && $match->pickupVerification)
                                            <a href="{{ route('pickup.verify', $match->qr_code) }}"
                                               class="flex-1 lg:flex-none w-full bg-gray-600 text-white py-2 px-3 rounded-md hover:bg-gray-700 transition-colors text-xs sm:text-sm font-medium text-center block">
                                                View Pickup Details
                                            </a>
                                        @endif
                                    @endif
                                    
                                    @if($match->status === 'scheduled')
                                        <form action="{{ route('recipient.matches.complete', $match) }}" method="POST" class="flex-1 lg:flex-none">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full bg-blue-600 text-white py-2 px-3 rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                                                Mark Completed
                                            </button>
                                        </form>
                                        
                                        <div class="text-xs text-gray-500 text-center">
                                            @if($match->pickup_scheduled_at)
                                                Scheduled for:<br>
                                                {{ \Carbon\Carbon::parse($match->pickup_scheduled_at)->format('M d, H:i') }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($match->status === 'completed')
                                        <div class="text-center">
                                            <svg class="h-6 w-6 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-xs text-green-600 font-medium">Completed</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Timeline -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Interest expressed 
                                            @if($match->matched_at && method_exists($match->matched_at, 'diffForHumans'))
                                                {{ $match->matched_at->diffForHumans() }}
                                            @elseif($match->created_at && method_exists($match->created_at, 'diffForHumans'))
                                                {{ $match->created_at->diffForHumans() }}
                                            @else
                                                recently
                                            @endif
                                        </div>
                                        
                                        @if($match->approved_at)
                                            <div class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approved 
                                                @if(method_exists($match->approved_at, 'diffForHumans'))
                                                    {{ $match->approved_at->diffForHumans() }}
                                                @else
                                                    {{ $match->approved_at }}
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($match->completed_at)
                                            <div class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Completed 
                                                @if(method_exists($match->completed_at, 'diffForHumans'))
                                                    {{ $match->completed_at->diffForHumans() }}
                                                @else
                                                    {{ $match->completed_at }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $matches->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-12">
                <div class="text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No matches found</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        You haven't expressed interest in any food donations yet.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('recipient.browse.index') }}" 
                           class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                            Browse Available Food
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeQrModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pickup QR Code</h3>
                <div id="qrCodeContainer" class="mb-4">
                    <!-- QR code will be inserted here -->
                </div>
                <p class="text-sm text-gray-500 mb-4">Show this code to the donor during pickup</p>
                <button onclick="closeQrModal()" 
                        class="bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showQrCode(qrCode) {
    // In a real implementation, you would generate the actual QR code
    document.getElementById('qrCodeContainer').innerHTML = 
        '<div class="w-48 h-48 bg-gray-200 flex items-center justify-center mx-auto rounded-lg">' +
        '<div class="text-center">' +
        '<svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M12 12v4h4.01"></path>' +
        '</svg>' +
        '<p class="text-sm text-gray-600">QR Code: ' + qrCode + '</p>' +
        '</div>' +
        '</div>';
    
    document.getElementById('qrModal').classList.remove('hidden');
}

function closeQrModal() {
    document.getElementById('qrModal').classList.add('hidden');
}
</script>
@endsection