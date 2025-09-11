@extends('layouts.restaurant')

@section('title', 'Manage Matches - Restaurant Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Recipient Interests</h1>
            <p class="text-gray-600 mt-1">Review and approve pickup requests from recipients</p>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('restaurant.matches.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Search recipients or food items..." 
                                       class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="focus:ring-green-500 focus:border-green-500 block w-full text-sm border-gray-300 rounded-md">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-center md:justify-end">
                        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex overflow-x-auto px-4 sm:px-6" aria-label="Tabs">
                    <a href="{{ route('restaurant.matches.index', ['status' => 'pending'] + request()->except('status')) }}" 
                       class="@if($status === 'pending') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0">
                        <span class="hidden sm:inline">Pending</span>
                        <span class="sm:hidden">Pend</span>
                        @if($statusCounts['pending'] > 0)
                            <span class="ml-1 sm:ml-2 px-1.5 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $statusCounts['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('restaurant.matches.index', ['status' => 'approved'] + request()->except('status')) }}" 
                       class="@if($status === 'approved') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0 ml-4 sm:ml-8">
                        <span class="hidden sm:inline">Approved ({{ $statusCounts['approved'] }})</span>
                        <span class="sm:hidden">App ({{ $statusCounts['approved'] }})</span>
                    </a>
                    <a href="{{ route('restaurant.matches.index', ['status' => 'scheduled'] + request()->except('status')) }}" 
                       class="@if($status === 'scheduled') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0 ml-4 sm:ml-8">
                        <span class="hidden sm:inline">Scheduled ({{ $statusCounts['scheduled'] }})</span>
                        <span class="sm:hidden">Sch ({{ $statusCounts['scheduled'] }})</span>
                    </a>
                    <a href="{{ route('restaurant.matches.index', ['status' => 'completed'] + request()->except('status')) }}" 
                       class="@if($status === 'completed') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0 ml-4 sm:ml-8">
                        <span class="hidden sm:inline">Completed ({{ $statusCounts['completed'] }})</span>
                        <span class="sm:hidden">Done ({{ $statusCounts['completed'] }})</span>
                    </a>
                    <a href="{{ route('restaurant.matches.index', ['status' => 'all'] + request()->except('status')) }}" 
                       class="@if($status === 'all') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0 ml-4 sm:ml-8">
                        All ({{ $statusCounts['all'] }})
                    </a>
                </nav>
            </div>
        </div>

        <!-- Matches List -->
        <div class="space-y-4">
            @forelse($matches as $match)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <!-- Match Header -->
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($match->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($match->status === 'approved') bg-green-100 text-green-800
                                    @elseif($match->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($match->status === 'completed') bg-gray-100 text-gray-800
                                    @elseif($match->status === 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($match->status) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $match->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Food Item Column -->
                            <div class="lg:col-span-1">
                                <div class="space-y-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $match->foodListing->food_name }}</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {{ $match->foodListing->quantity }} {{ $match->foodListing->unit }}
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            {{ $match->foodListing->category }}
                                        </div>
                                        @if($match->distance)
                                            <div class="flex items-center text-sm text-blue-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                {{ $match->distance }}km away
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Recipient Info Column -->
                            <div class="lg:col-span-1">
                                <div class="space-y-3">
                                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Recipient</h4>
                                    <div class="space-y-2">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $match->recipient->name }}</div>
                                            @if($match->recipient->organization_name)
                                                <div class="text-sm text-gray-600">{{ $match->recipient->organization_name }}</div>
                                            @endif
                                        </div>
                                        @if($match->recipient->phone)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $match->recipient->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions & Status Column -->
                            <div class="lg:col-span-1">
                                <div class="space-y-4">
                                    @if($match->pickup_scheduled_at)
                                        <div class="p-3 bg-blue-50 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-xs font-medium text-blue-900 uppercase tracking-wide">Scheduled</p>
                                                    <p class="text-sm text-blue-700">
                                                        @if($match->pickup_scheduled_at)
                                                            {{ \Carbon\Carbon::parse($match->pickup_scheduled_at)->format('M j, g:i A') }}
                                                        @else
                                                            Not scheduled
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($match->pickupVerification)
                                        <div class="p-3 bg-green-50 rounded-lg">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 006 0a3 3 0 00-6 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4M12 12l-.01.01"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-xs font-medium text-green-900 uppercase tracking-wide">QR Code Ready</p>
                                                        <p class="text-sm text-green-700 font-mono">{{ $match->pickupVerification->verification_code }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    <button onclick="showQrCode('{{ $match->pickupVerification->verification_code }}', '{{ $match->foodListing->food_name }}')" 
                                                            class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                                        Show QR
                                                    </button>
                                                    <button onclick="printQrCode('{{ $match->pickupVerification->verification_code }}', '{{ $match->foodListing->food_name }}', '{{ $match->recipient->name }}')" 
                                                            class="px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                                                        Print
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($match->completed_at)
                                        <div class="p-3 bg-green-50 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-xs font-medium text-green-900 uppercase tracking-wide">Completed</p>
                                                    <p class="text-sm text-green-700">
                                                        @if($match->completed_at)
                                                            {{ \Carbon\Carbon::parse($match->completed_at)->format('M j, g:i A') }}
                                                        @else
                                                            Not completed
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($match->notes)
                                        <div class="p-3 bg-yellow-50 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-xs font-medium text-yellow-900 uppercase tracking-wide">Notes</p>
                                                    <p class="text-sm text-yellow-700">{{ $match->notes }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="pt-2">
                                        @if($match->status === 'pending')
                                            <div class="space-y-2">
                                                <form method="POST" action="{{ route('restaurant.listings.matches.approve', [$match->foodListing, $match]) }}" class="w-full">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors"
                                                            onclick="return confirm('Are you sure you want to approve this pickup request?')">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Approve Request
                                                    </button>
                                                </form>
                                                <button onclick="openRejectModal({{ $match->id }}, '{{ $match->foodListing->id }}')" 
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Reject
                                                </button>
                                            </div>
                                        @elseif($match->status === 'approved')
                                            <button onclick="openScheduleModal({{ $match->id }}, '{{ $match->foodListing->id }}')" 
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Schedule Pickup
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="bg-white rounded-lg shadow p-6 sm:p-8 text-center">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No matches found</h3>
                    <p class="text-sm text-gray-500">
                        @if($status === 'pending')
                            No pending pickup requests at the moment.
                        @else
                            No {{ $status }} matches found. Try adjusting your filters.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($matches->hasPages())
            <div class="mt-6">
                {{ $matches->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Reject Pickup Request</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection (optional)</label>
                    <textarea name="reason" id="reason" rows="3" 
                              class="focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Schedule Pickup Time</h3>
                <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="scheduleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">Pickup Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeScheduleModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Schedule Pickup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(matchId, listingId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/restaurant/listings/${listingId}/matches/${matchId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('reason').value = '';
}

function openScheduleModal(matchId, listingId) {
    const modal = document.getElementById('scheduleModal');
    const form = document.getElementById('scheduleForm');
    form.action = `/restaurant/listings/${listingId}/matches/${matchId}/schedule`;
    modal.classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduled_at').value = '';
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const rejectModal = document.getElementById('rejectModal');
    const scheduleModal = document.getElementById('scheduleModal');
    
    if (e.target === rejectModal) {
        closeRejectModal();
    }
    if (e.target === scheduleModal) {
        closeScheduleModal();
    }
});

// QR Code functions
function showQrCode(code, foodName) {
    const modal = document.getElementById('qrModal');
    const qrTitle = document.getElementById('qrTitle');
    const qrCode = document.getElementById('qrCodeDisplay');
    const qrText = document.getElementById('qrCodeText');
    
    qrTitle.textContent = `QR Code - ${foodName}`;
    qrCodeText.textContent = code;
    
    // Generate QR code using a simple library or API
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(code)}`;
    qrCode.innerHTML = `<img src="${qrCodeUrl}" alt="QR Code" class="mx-auto">`;
    
    modal.classList.remove('hidden');
}

function closeQrModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

function printQrCode(code, foodName, recipientName) {
    const printWindow = window.open('', '_blank');
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(code)}`;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>QR Code - ${foodName}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    text-align: center; 
                    padding: 20px; 
                }
                .qr-container { 
                    border: 2px solid #000; 
                    padding: 20px; 
                    margin: 20px auto; 
                    max-width: 400px; 
                }
                .code { 
                    font-family: monospace; 
                    font-size: 18px; 
                    font-weight: bold; 
                    margin: 15px 0; 
                }
            </style>
        </head>
        <body>
            <div class="qr-container">
                <h2>Pickup Verification QR Code</h2>
                <h3>${foodName}</h3>
                <p><strong>Recipient:</strong> ${recipientName}</p>
                <img src="${qrCodeUrl}" alt="QR Code" style="max-width: 100%;">
                <div class="code">${code}</div>
                <p><small>Scan this QR code during pickup to verify the transaction</small></p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}
</script>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="qrTitle" class="text-lg font-medium text-gray-900">QR Code</h3>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <div id="qrCodeDisplay" class="mb-4"></div>
                <p class="text-sm font-medium text-gray-700 mb-2">Verification Code:</p>
                <p id="qrCodeText" class="text-lg font-mono bg-gray-100 p-2 rounded"></p>
                <p class="text-xs text-gray-500 mt-3">Show this QR code to the recipient during pickup</p>
            </div>
        </div>
    </div>
</div>

@endsection