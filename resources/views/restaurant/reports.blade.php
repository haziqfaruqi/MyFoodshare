@extends('layouts.restaurant')

@section('title', 'Reports & Analytics - Restaurant Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Track your donation impact and performance</p>
        </div>

        <!-- Impact Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $impactStats['total_meals_provided'] ?? 0 }}</h3>
                        <p class="text-sm text-gray-600">Meals Provided</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ number_format($impactStats['total_weight_saved'] ?? 0, 1) }} kg</h3>
                        <p class="text-sm text-gray-600">Food Waste Reduced</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $detailedStats['completed_donations'] ?? 0 }}</h3>
                        <p class="text-sm text-gray-600">Completed Donations</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $detailedStats['total_listings'] ?? 0 }}</h3>
                        <p class="text-sm text-gray-600">Total Listings</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Trends Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Donation Trends (Last 12 Months)</h3>
                <div class="relative h-80">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Food Category Breakdown</h3>
                <div class="space-y-4">
                    @forelse($categoryStats as $category => $count)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#F97316'][$loop->index % 6] }};"></div>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ $category }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-4">
                            No category data available
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Detailed Performance Metrics</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $detailedStats['total_listings'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Food Listings</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $detailedStats['active_listings'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">Currently Active</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $detailedStats['total_matches'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Matches</div>
                    </div>
                </div>
                
                @if($detailedStats['total_listings'] > 0)
                    <div class="mt-6 text-center">
                        <div class="text-lg text-gray-700">
                            Success Rate: 
                            <span class="font-semibold text-green-600">
                                {{ round(($detailedStats['completed_donations'] / $detailedStats['total_listings']) * 100, 1) }}%
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            ({{ $detailedStats['completed_donations'] }} completed out of {{ $detailedStats['total_listings'] }} listings)
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyData = @json($monthlyTrends);
    
    // Process the data for Chart.js
    const chartData = {
        labels: monthlyData.map(item => {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return months[item.month - 1] + ' ' + item.year;
        }),
        datasets: [
            {
                label: 'Donations',
                data: monthlyData.map(item => item.count || 0),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
                fill: true
            },
            {
                label: 'Estimated Meals',
                data: monthlyData.map(item => item.estimated_meals || 0),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.1,
                fill: true
            },
            {
                label: 'Weight (kg)',
                data: monthlyData.map(item => item.estimated_weight || 0),
                borderColor: 'rgb(168, 85, 247)',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                tension: 0.1,
                fill: true
            }
        ]
    };

    new Chart(ctx, {
        type: 'line',
        data: chartData,
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
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                point: {
                    radius: 3,
                    hoverRadius: 6
                }
            }
        }
    });
});
</script>
@endpush
@endsection