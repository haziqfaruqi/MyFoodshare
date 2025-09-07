@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <p class="text-gray-600 mt-2">View and manage user information</p>
        </div>
        
        <a href="{{ route('admin.users.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
            ← Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-700">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full 
                                    {{ $user->role === 'donor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full
                                    @switch($user->status)
                                        @case('active') bg-green-100 text-green-800 @break
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('suspended') bg-red-100 text-red-800 @break
                                        @case('rejected') bg-gray-100 text-gray-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Registered</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->address }}</p>
                </div>

                <!-- Role-specific Information -->
                @if($user->role === 'donor')
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Restaurant Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->restaurant_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Business License</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->business_license }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cuisine Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->cuisine_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Restaurant Capacity</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->restaurant_capacity ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
                @elseif($user->role === 'recipient')
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Organization Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Organization Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->organization_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->contact_person }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NGO Registration</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->ngo_registration }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recipient Capacity</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->recipient_capacity }}</p>
                        </div>
                    </div>
                    
                    @if($user->dietary_requirements && count($user->dietary_requirements) > 0)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Dietary Requirements</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($user->dietary_requirements as $requirement)
                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                {{ $requirement }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($user->needs_preferences)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Needs & Preferences</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->needs_preferences }}</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($user->description)
                <div class="border-t pt-6">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->description }}</p>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($user->admin_notes)
                <div class="border-t pt-6">
                    <label class="block text-sm font-medium text-gray-700">Admin Notes</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-900">{{ $user->admin_notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Approval Information -->
                @if($user->approved_at)
                <div class="border-t pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Approved Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->approved_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        @if($user->approvedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Approved By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->approvedBy->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                
                @if($user->status === 'pending')
                <div class="space-y-3">
                    <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium"
                                onclick="return confirm('Are you sure you want to approve this user?')">
                            ✓ Approve User
                        </button>
                    </form>
                    
                    <button type="button" onclick="openRejectModal({{ $user->id }})"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                        ✗ Reject User
                    </button>
                </div>
                @else
                <button type="button" onclick="openStatusModal({{ $user->id }})"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    Update Status
                </button>
                @endif
            </div>

            <!-- User Statistics -->
            @if($user->role === 'donor')
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Listings</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['total_listings'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Active Listings</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['active_listings'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Completed Matches</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['completed_matches'] }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h3>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium"
                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject User</h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                <textarea name="admin_notes" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Please provide a reason for rejecting this user..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    Reject User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Update User Status</h3>
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ $user->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (optional)</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Optional notes about this status change...">{{ $user->admin_notes }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeStatusModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(userId) {
    document.getElementById('rejectForm').action = `/admin/users/${userId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

function openStatusModal(userId) {
    document.getElementById('statusForm').action = `/admin/users/${userId}/status`;
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
}

// Close modals when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});

document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});
</script>
@endsection