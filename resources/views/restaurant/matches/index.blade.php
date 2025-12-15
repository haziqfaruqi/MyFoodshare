@extends('layouts.restaurant')

@section('title', 'Manage Matches - Restaurant Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start mb-4">
                <div class="bg-green-100 p-3 rounded-xl mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Manage Food Matches</h1>
                    <p class="text-lg text-gray-600 mt-1">Coordinate food donations and pickups with local organizations</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6 mb-8">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-red-100">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['pending'] }}</p>
                            <p class="text-xs text-gray-500">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['approved'] }}</p>
                            <p class="text-xs text-gray-500">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['scheduled'] }}</p>
                            <p class="text-xs text-gray-500">Scheduled</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['completed'] }}</p>
                            <p class="text-xs text-gray-500">Completed</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-orange-100">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['rejected'] }}</p>
                            <p class="text-xs text-gray-500">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-gray-50 rounded-lg shadow mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('restaurant.matches.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search recipients or food items..."
                                       class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="focus:ring-green-500 focus:border-green-500 block w-full py-2 text-sm border border-gray-300 rounded-md">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="window.location.href='{{ route('restaurant.matches.index') }}'"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Filters
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Pills -->
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700">Quick filters:</span>
                <a href="{{ route('restaurant.matches.index', ['status' => 'pending'] + request()->except('status')) }}"
                   class="@if($status === 'pending') bg-red-100 text-red-800 border border-red-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    Pending {{ $statusCounts['pending'] > 0 ? '('.$statusCounts['pending'].')' : '' }}
                </a>
                <a href="{{ route('restaurant.matches.index', ['status' => 'approved'] + request()->except('status')) }}"
                   class="@if($status === 'approved') bg-green-100 text-green-800 border border-green-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    Approved {{ $statusCounts['approved'] > 0 ? '('.$statusCounts['approved'].')' : '' }}
                </a>
                <a href="{{ route('restaurant.matches.index', ['status' => 'scheduled'] + request()->except('status')) }}"
                   class="@if($status === 'scheduled') bg-blue-100 text-blue-800 border border-blue-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    Scheduled {{ $statusCounts['scheduled'] > 0 ? '('.$statusCounts['scheduled'].')' : '' }}
                </a>
                <a href="{{ route('restaurant.matches.index', ['status' => 'completed'] + request()->except('status')) }}"
                   class="@if($status === 'completed') bg-gray-100 text-gray-800 border border-gray-300 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    Completed {{ $statusCounts['completed'] > 0 ? '('.$statusCounts['completed'].')' : '' }}
                </a>
                <a href="{{ route('restaurant.matches.index', ['status' => 'rejected'] + request()->except('status')) }}"
                   class="@if($status === 'rejected') bg-red-100 text-red-800 border border-red-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    Rejected {{ $statusCounts['rejected'] > 0 ? '('.$statusCounts['rejected'].')' : '' }}
                </a>
                <a href="{{ route('restaurant.matches.index', ['status' => 'all'] + request()->except('status')) }}"
                   class="@if($status === 'all') bg-green-100 text-green-800 border border-green-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif px-3 py-1.5 rounded-full text-xs font-medium border transition-colors">
                    All ({{ $statusCounts['all'] }})
                </a>
            </div>
        </div>

        <!-- Matches List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($matches as $match)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Match Header -->
                    <div class="relative">
                        <!-- Status indicator with gradient -->
                        <div class="absolute top-0 right-0 m-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-md
                                @if($match->status === 'pending') bg-gradient-to-r from-yellow-400 to-orange-400 text-yellow-900
                                @elseif($match->status === 'approved') bg-gradient-to-r from-green-400 to-emerald-400 text-green-900
                                @elseif($match->status === 'scheduled') bg-gradient-to-r from-blue-400 to-indigo-400 text-blue-900
                                @elseif($match->status === 'completed') bg-gradient-to-r from-gray-400 to-slate-400 text-gray-900
                                @elseif($match->status === 'rejected') bg-gradient-to-r from-red-400 to-rose-400 text-red-900
                                @endif">
                                {{ ucfirst($match->status) }}
                            </span>
                        </div>

                        <!-- Decorative pattern background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white to-gray-50 opacity-50"></div>

                        <div class="relative px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $match->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-5">
                            <!-- Food Item Section -->
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $match->foodListing->food_name }}
                                        </h3>
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm text-gray-700">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $match->foodListing->quantity }}</span> {{ $match->foodListing->unit }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-700">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">{{ $match->foodListing->category }}</span>
                                            </div>
                                            @if($match->distance)
                                                <div class="flex items-center text-sm text-blue-600">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $match->distance }}km away</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recipient Info Section -->
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4">
                                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Recipient Information
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <div class="font-semibold text-gray-900 flex items-center">
                                            {{ $match->recipient->name }}
                                            @if($match->recipient->organization_name)
                                                <span class="ml-2 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $match->recipient->organization_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($match->recipient->phone)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $match->recipient->phone }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Indicators -->
                            <div class="space-y-2">
                                @if($match->pickup_scheduled_at)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="bg-blue-500 p-1.5 rounded-full mr-3">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-blue-900 uppercase tracking-wide">Pickup Scheduled</p>
                                                    <p class="text-sm font-semibold text-blue-800">
                                                        {{ \Carbon\Carbon::parse($match->pickup_scheduled_at)->format('M j, g:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($match->pickupVerification)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1">
                                                <div class="bg-green-500 p-1.5 rounded-full mr-3">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 006 0a3 3 0 00-6 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4M12 12l-.01.01"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs font-bold text-green-900 uppercase tracking-wide">QR Code Ready</p>
                                                    <p class="text-sm font-mono text-green-800 bg-green-100 px-2 py-1 rounded">{{ $match->pickupVerification->verification_code }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-1 ml-3">
                                                <button onclick="showQrCode('{{ $match->pickupVerification->verification_code }}', '{{ $match->foodListing->food_name }}')"
                                                        class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Show QR
                                                </button>
                                                <button onclick="printQrCode('{{ $match->pickupVerification->verification_code }}', '{{ $match->foodListing->food_name }}', '{{ $match->recipient->name }}')"
                                                        class="px-3 py-1.5 text-xs bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($match->completed_at)
                                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-3 border border-emerald-200">
                                        <div class="flex items-center">
                                            <div class="bg-emerald-500 p-1.5 rounded-full mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-emerald-900 uppercase tracking-wide">Pickup Completed</p>
                                                <p class="text-sm font-semibold text-emerald-800">
                                                    {{ \Carbon\Carbon::parse($match->completed_at)->format('M j, g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($match->notes)
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-3 border border-yellow-200">
                                        <div class="flex items-start">
                                            <div class="bg-yellow-500 p-1.5 rounded-full mr-3 mt-0.5">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs font-bold text-yellow-900 uppercase tracking-wide">Additional Notes</p>
                                                <p class="text-sm text-yellow-800">{{ $match->notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-4 border-t border-gray-200">
                                @if($match->status === 'pending')
                                    <div class="space-y-2">
                                        <form method="POST" action="{{ route('restaurant.listings.matches.approve', [$match->foodListing, $match]) }}" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve Request
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $match->id }}, '{{ $match->foodListing->id }}')"
                                                class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Reject Request
                                        </button>
                                    </div>
                                @elseif($match->status === 'approved')
                                    <button onclick="openScheduleModal({{ $match->id }}, '{{ $match->foodListing->id }}')"
                                            class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-bold rounded-lg text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
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

            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                        <div class="bg-gray-100 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No matches found</h3>
                        <p class="text-gray-600 mb-6">
                            @if($status === 'pending')
                                No pending pickup requests at the moment.
                            @else
                                No {{ $status }} matches found. Try adjusting your filters.
                            @endif
                        </p>
                        <button onclick="window.location.href='{{ route('restaurant.matches.index', ['status' => 'all']) }}'"
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($matches->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-md p-2">
                    {{ $matches->appends(request()->query())->links(['pagination::tailwind' => 'pagination']) }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-11/12 max-w-md shadow-2xl rounded-2xl bg-white transform transition-all duration-300 scale-95 opacity-0" id="rejectModalContent">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reject Pickup Request</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to reject this pickup request? You can provide an optional reason below.</p>

            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection (optional)</label>
                    <textarea name="reason" id="reason" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-200">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-11/12 max-w-md shadow-2xl rounded-2xl bg-white transform transition-all duration-300 scale-95 opacity-0" id="scheduleModalContent">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Schedule Pickup Time</h3>
            <p class="text-sm text-gray-600 mb-6">Choose a date and time for the recipient to pick up the food donation.</p>

            <form id="scheduleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">Pickup Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="closeScheduleModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
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
    const modalContent = document.getElementById('rejectModalContent');
    const form = document.getElementById('rejectForm');
    form.action = `/restaurant/listings/${listingId}/matches/${matchId}/reject`;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    const modalContent = document.getElementById('rejectModalContent');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('reason').value = '';
    }, 300);
}

function openScheduleModal(matchId, listingId) {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('scheduleModalContent');
    const form = document.getElementById('scheduleForm');
    form.action = `/restaurant/listings/${listingId}/matches/${matchId}/schedule`;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('scheduleModalContent');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('scheduled_at').value = '';
    }, 300);
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

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeScheduleModal();
        closeQrModal();
    }
});

// QR Code functions
function showQrCode(code, foodName) {
    const modal = document.getElementById('qrModal');
    const modalContent = document.getElementById('qrModalContent');
    const qrTitle = document.getElementById('qrTitle');
    const qrCode = document.getElementById('qrCodeDisplay');
    const qrText = document.getElementById('qrCodeText');

    qrTitle.textContent = `QR Code - ${foodName}`;
    qrText.textContent = code;

    // Generate QR code using a simple library or API
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(code)}`;
    qrCode.innerHTML = `<img src="${qrCodeUrl}" alt="QR Code" class="mx-auto mb-4 rounded-lg shadow-md">`;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeQrModal() {
    const modal = document.getElementById('qrModal');
    const modalContent = document.getElementById('qrModalContent');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
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
                    background-color: #f8f9fa;
                }
                .qr-container {
                    border: 2px solid #10b981;
                    padding: 30px;
                    margin: 30px auto;
                    max-width: 500px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
                .qr-title {
                    color: #10b981;
                    margin-bottom: 15px;
                }
                .code {
                    font-family: monospace;
                    font-size: 20px;
                    font-weight: bold;
                    margin: 20px 0;
                    padding: 15px;
                    background: #f3f4f6;
                    border-radius: 8px;
                    color: #1f2937;
                }
                .header {
                    background: linear-gradient(135deg, #10b981, #059669);
                    color: white;
                    padding: 20px;
                    border-radius: 8px 8px 0 0;
                    margin: -30px -30px 20px -30px;
                }
                .logo {
                    width: 50px;
                    height: 50px;
                    background: white;
                    border-radius: 50%;
                    margin: 0 auto 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
            </style>
        </head>
        <body>
            <div class="qr-container">
                <div class="header">
                    <div class="logo">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 006 0a3 3 0 00-6 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4M12 12l-.01.01"></path>
                        </svg>
                    </div>
                    <h1 class="qr-title">FoodShare Pickup Verification</h1>
                    <p class="text-sm opacity-90">Food Donation Platform</p>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">${foodName}</h2>
                <div class="flex items-center justify-between mb-6 text-sm">
                    <div>
                        <p class="font-medium text-gray-700">Recipient:</p>
                        <p class="font-semibold text-gray-900">${recipientName}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Date:</p>
                        <p class="font-semibold text-gray-900">{{ date('M j, Y') }}</p>
                    </div>
                </div>
                <img src="${qrCodeUrl}" alt="QR Code" style="max-width: 200px; margin: 0 auto 25px;">
                <div class="code">${code}</div>
                <p class="text-sm text-gray-600 mt-4">
                    <strong>Instructions:</strong> Scan this QR code during pickup to verify the transaction
                </p>
            </div>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.print();
}

// Add smooth fade-in animation for page elements
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white.rounded-2xl');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-11/12 max-w-md shadow-2xl rounded-2xl bg-white transform transition-all duration-300 scale-95 opacity-0" id="qrModalContent">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 006 0a3 3 0 00-6 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21l4-4 4 4M12 12l-.01.01"></path>
                </svg>
            </div>
            <h3 id="qrTitle" class="text-lg font-semibold text-gray-900 mb-2">QR Code</h3>
            <p class="text-sm text-gray-600 mb-6">Scan this QR code during pickup to verify the transaction</p>
            <div id="qrCodeDisplay"></div>
            <p class="text-sm font-medium text-gray-700 mb-2">Verification Code:</p>
            <p id="qrCodeText" class="text-lg font-mono bg-green-100 p-3 rounded-lg text-green-800 mb-6 font-bold"></p>
            <button onclick="closeQrModal()"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

@endsection