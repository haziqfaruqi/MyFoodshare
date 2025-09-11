@extends('layouts.admin')

@section('title', 'Admin Dashboard - MyFoodshare')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">System Administration</h1>
                    <p class="text-gray-600 mt-1">Platform Overview & Management</p>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Donors</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_donors'] }}</p>
                        <p class="text-sm text-green-600">Restaurants</p>
                    </div>
                    <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Recipients</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_recipients'] }}</p>
                        <p class="text-sm text-blue-600">NGOs</p>
                    </div>
                    <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Pending Approvals</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_approvals'] }}</p>
                        <p class="text-sm text-yellow-600">Need review</p>
                    </div>
                    <svg class="h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Active Listings</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['active_listings'] }}</p>
                        <p class="text-sm text-purple-600">Available now</p>
                    </div>
                    <svg class="h-12 w-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600">Total Matches</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_matches'] }}</p>
                        <p class="text-sm text-indigo-600">All time</p>
                    </div>
                    <svg class="h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Monthly Trends Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Monthly Trends</h2>
                    <div class="h-64">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity */}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full mr-3 
                                        @if($activity['status'] === 'success') bg-green-500
                                        @elseif($activity['status'] === 'warning') bg-yellow-500
                                        @else bg-blue-500
                                        @endif">
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $activity['user'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $activity['action'] }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">{{ $activity['time'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pending Approvals -->
                @if($pendingUsers->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Pending User Approvals</h2>
                        <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($pendingUsers as $user)
                            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                        @if($user->role === 'donor')
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($user->role) }} - {{ $user->email }}</p>
                                        <p class="text-xs text-gray-500">Applied {{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        Review
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="#" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm font-medium block text-center">
                            Generate Report
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium block text-center">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.analytics.index') }}" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-colors text-sm font-medium block text-center">
                            View Analytics
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Listings',
                    data: monthlyData.map(item => item.listings),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Matches',
                    data: monthlyData.map(item => item.matches),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'New Users',
                    data: monthlyData.map(item => item.users),
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endpush

@endsection