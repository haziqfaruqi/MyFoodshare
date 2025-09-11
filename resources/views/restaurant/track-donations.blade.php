@extends('layouts.restaurant')

@section('title', 'Track Donations - Restaurant Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Track Your Donations</h1>
            <p class="text-gray-600 mt-1">Monitor the progress of your food donations from listing to pickup</p>
        </div>

        <!-- Progress Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $progressStats['total_donations'] }}</h3>
                        <p class="text-sm text-gray-600">Total Donations</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $progressStats['active_donations'] }}</h3>
                        <p class="text-sm text-gray-600">Active Listings</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $progressStats['expired_donations'] }}</h3>
                        <p class="text-sm text-gray-600">Expired</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $progressStats['completion_rate'] }}%</h3>
                        <p class="text-sm text-gray-600">Success Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Progress Tabs -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('active')" id="tab-active" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Active ({{ $progressData['active']->count() }})
                    </button>
                    <button onclick="showTab('completed')" id="tab-completed" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Completed ({{ $progressData['completed']->count() }})
                    </button>
                    <button onclick="showTab('expired')" id="tab-expired" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Expired ({{ $progressData['expired']->count() }})
                    </button>
                </nav>
            </div>

            <!-- Active Donations -->
            <div id="content-active" class="tab-content p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Active Donations - All In-Progress Items</h3>
                @forelse($progressData['active'] as $donation)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $donation->food_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $donation->quantity }} {{ $donation->unit }} • {{ $donation->category }}</p>
                                        <p class="text-sm text-gray-500">Expires: {{ $donation->expiry_date->format('M j, Y g:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Listed 
                                            @if(method_exists($donation->created_at, 'diffForHumans'))
                                                {{ $donation->created_at->diffForHumans() }}
                                            @else
                                                {{ \Carbon\Carbon::parse($donation->created_at)->diffForHumans() }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                @if($donation->matches->count() > 0)
                                    @foreach($donation->matches as $match)
                                        <div class="bg-gray-50 rounded-lg p-3 mb-2">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $match->recipient->organization_name ?? $match->recipient->name }}</p>
                                                    <p class="text-xs text-gray-600">Matched 
                                                        @if(method_exists($match->created_at, 'diffForHumans'))
                                                            {{ $match->created_at->diffForHumans() }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($match->created_at)->diffForHumans() }}
                                                        @endif
                                                    </p>
                                                    @if($match->pickup_scheduled_at)
                                                        <p class="text-xs text-green-600">
                                                            Scheduled for 
                                                            @if(method_exists($match->pickup_scheduled_at, 'format'))
                                                                {{ $match->pickup_scheduled_at->format('M j, Y g:i A') }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($match->pickup_scheduled_at)->format('M j, Y g:i A') }}
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        @if($match->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($match->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($match->status === 'scheduled') bg-blue-100 text-blue-800
                                                        @elseif($match->status === 'in_progress') bg-purple-100 text-purple-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $match->status)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                
                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    @php
                                        $hasMatches = $donation->matches->count() > 0;
                                        $hasApproved = $donation->matches->where('status', 'approved')->count() > 0;
                                        $hasScheduled = $donation->matches->where('status', 'scheduled')->count() > 0;
                                        $hasCompleted = $donation->matches->where('status', 'completed')->count() > 0;
                                        
                                        $progressPercent = 25; // Listed (default)
                                        $progressColor = 'bg-blue-600';
                                        
                                        if ($hasCompleted) {
                                            $progressPercent = 100;
                                            $progressColor = 'bg-green-600';
                                        } elseif ($hasScheduled) {
                                            $progressPercent = 75;
                                            $progressColor = 'bg-yellow-600';
                                        } elseif ($hasApproved) {
                                            $progressPercent = 50;
                                            $progressColor = 'bg-orange-600';
                                        } elseif ($hasMatches) {
                                            $progressPercent = 35;
                                            $progressColor = 'bg-purple-600';
                                        }
                                    @endphp
                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $progressPercent }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $progressColor }} h-2 rounded-full" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span class="{{ $progressPercent >= 25 ? 'text-blue-600 font-medium' : '' }}">Listed</span>
                                        <span class="{{ $progressPercent >= 35 ? 'text-purple-600 font-medium' : '' }}">Interest</span>
                                        <span class="{{ $progressPercent >= 50 ? 'text-orange-600 font-medium' : '' }}">Approved</span>
                                        <span class="{{ $progressPercent >= 75 ? 'text-yellow-600 font-medium' : '' }}">Scheduled</span>
                                        <span class="{{ $progressPercent >= 100 ? 'text-green-600 font-medium' : '' }}">Completed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p>No active donations. <a href="{{ route('restaurant.listings.create') }}" class="text-green-600 hover:text-green-700">Create a new listing</a> to get started!</p>
                    </div>
                @endforelse
            </div>


            <!-- Completed Donations -->
            <div id="content-completed" class="tab-content p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Completed Donations</h3>
                @forelse($progressData['completed'] as $donation)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $donation->food_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $donation->quantity }} {{ $donation->unit }} • {{ $donation->category }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    </span>
                                </div>

                                @if($donation->matches->count() > 0)
                                    @foreach($donation->matches->where('status', 'completed') as $match)
                                        <div class="bg-green-50 rounded-lg p-3 mb-2">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $match->recipient->organization_name ?? $match->recipient->name }}</p>
                                                    <p class="text-xs text-gray-600">Completed 
                                                        @if($match->completed_at)
                                                            @if(method_exists($match->completed_at, 'diffForHumans'))
                                                                {{ $match->completed_at->diffForHumans() }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($match->completed_at)->diffForHumans() }}
                                                            @endif
                                                        @else
                                                            recently
                                                        @endif
                                                    </p>
                                                    @if($match->pickupVerification)
                                                        <p class="text-xs text-green-600">✓ Pickup verified</p>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-green-600 font-medium">
                                                    Successfully delivered
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                
                                <!-- Completed Progress Bar -->
                                <div class="mt-3">
                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>100%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span class="text-blue-600 font-medium">Listed</span>
                                        <span class="text-purple-600 font-medium">Interest</span>
                                        <span class="text-orange-600 font-medium">Approved</span>
                                        <span class="text-yellow-600 font-medium">Scheduled</span>
                                        <span class="text-green-600 font-medium">Completed ✓</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No completed donations yet</p>
                    </div>
                @endforelse
            </div>

            <!-- Expired Donations -->
            <div id="content-expired" class="tab-content p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Expired Donations</h3>
                @forelse($progressData['expired'] as $donation)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow opacity-75">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $donation->food_name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $donation->quantity }} {{ $donation->unit }} • {{ $donation->category }}</p>
                                        <p class="text-sm text-red-600">Expired 
                                            @if(method_exists($donation->expiry_date, 'diffForHumans'))
                                                {{ $donation->expiry_date->diffForHumans() }}
                                            @else
                                                {{ \Carbon\Carbon::parse($donation->expiry_date)->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No expired donations</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));
    
    // Remove active classes from all tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-green-500', 'text-green-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`tab-${tabName}`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}

// Initialize with active tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('active');
});
</script>
@endsection