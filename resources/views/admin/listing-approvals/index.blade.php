@extends('layouts.admin')

@section('title', 'Listing Approvals - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Food Listing Approvals</h1>
            <p class="text-gray-600 mt-1">Review and approve new food listings from donors</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_pending'] }}</h3>
                        <p class="text-sm text-gray-600">Pending Approval</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $stats['approved_today'] }}</h3>
                        <p class="text-sm text-gray-600">Approved Today</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $stats['rejected_today'] }}</h3>
                        <p class="text-sm text-gray-600">Rejected Today</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $stats['avg_approval_time'] }}</h3>
                        <p class="text-sm text-gray-600">Avg. Response Time</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($pendingListings->count() > 0)
            <div class="mb-6">
                <form method="POST" action="{{ route('admin.listing-approvals.bulk-approve') }}" id="bulkForm">
                    @csrf
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                        <button type="button" onclick="selectNone()" class="text-sm text-gray-600 hover:text-gray-800">Select None</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium disabled:opacity-50" disabled id="bulkApproveBtn">
                            Bulk Approve Selected
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Pending Listings Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pending Food Listings</h3>
            </div>
            
            @if($pendingListings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingListings as $listing)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="listing_ids[]" value="{{ $listing->id }}" class="listing-checkbox rounded border-gray-300" form="bulkForm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $listing->food_name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($listing->description, 30) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $listing->donor->name }}</div>
                                        @if($listing->donor->restaurant_name)
                                            <div class="text-sm text-gray-500">{{ $listing->donor->restaurant_name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $listing->quantity }} {{ $listing->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $listing->expiry_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $listing->expiry_date->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $listing->created_at->format('M d, Y') }}
                                        <div class="text-xs">{{ $listing->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.listing-approvals.show', $listing) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        
                                        <form method="POST" action="{{ route('admin.listing-approvals.approve', $listing) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('Are you sure you want to approve this listing?')">
                                                Approve
                                            </button>
                                        </form>
                                        
                                        <button onclick="showRejectModal({{ $listing->id }}, '{{ $listing->food_name }}')" 
                                                class="text-red-600 hover:text-red-900">
                                            Reject
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pendingListings->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Pending Listings</h3>
                    <p class="mt-2 text-gray-500">All food listings have been processed.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mt-3 text-center">
                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Reject Food Listing</h3>
                <p class="text-sm text-gray-500 mt-2">You are about to reject "<span id="listingName"></span>"</p>
                
                <div class="mt-4 text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection *</label>
                    <textarea name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
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
function selectAll() {
    const checkboxes = document.querySelectorAll('.listing-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    checkboxes.forEach(cb => cb.checked = true);
    selectAllCheckbox.checked = true;
    updateBulkButton();
}

function selectNone() {
    const checkboxes = document.querySelectorAll('.listing-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    checkboxes.forEach(cb => cb.checked = false);
    selectAllCheckbox.checked = false;
    updateBulkButton();
}

function updateBulkButton() {
    const checkedBoxes = document.querySelectorAll('.listing-checkbox:checked');
    const bulkBtn = document.getElementById('bulkApproveBtn');
    bulkBtn.disabled = checkedBoxes.length === 0;
}

function showRejectModal(listingId, listingName) {
    document.getElementById('listingName').textContent = listingName;
    document.getElementById('rejectForm').action = `/admin/listing-approvals/${listingId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Event listeners
document.getElementById('selectAllCheckbox').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.listing-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkButton();
});

document.querySelectorAll('.listing-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkButton);
});

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endsection