@extends('layouts.admin')

@section('title', 'Pickup Verification Details - Admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pickup Verification Details</h1>
                <p class="text-gray-600 mt-1">Verification ID: {{ $verification->verification_code }}</p>
            </div>
            <a href="{{ route('admin.pickup-verifications.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                ← Back to Verifications
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Verification Overview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Verification Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-1
                                @switch($verification->verification_status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('verified') bg-blue-100 text-blue-800 @break
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('disputed') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst($verification->verification_status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        @if($verification->scanned_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scanned At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->scanned_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        @endif
                        @if($verification->pickup_completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Completed At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->pickup_completed_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Food Listing Details -->
                @if($verification->foodListing)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Food Listing</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Food Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->foodListing->food_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->foodListing->quantity }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $verification->foodListing->expiry_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Listing Status</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($verification->foodListing->status) }}</p>
                        </div>
                    </div>
                    @if($verification->foodListing->description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $verification->foodListing->description }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Participants -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Participants</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($verification->donor)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Donor (Restaurant)</h4>
                            <div class="space-y-2">
                                <p class="text-sm"><strong>Name:</strong> {{ $verification->donor->name }}</p>
                                <p class="text-sm"><strong>Restaurant:</strong> {{ $verification->donor->restaurant_name ?? 'N/A' }}</p>
                                <p class="text-sm"><strong>Email:</strong> {{ $verification->donor->email }}</p>
                                <p class="text-sm"><strong>Phone:</strong> {{ $verification->donor->phone }}</p>
                            </div>
                        </div>
                        @endif

                        @if($verification->recipient)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Recipient (NGO)</h4>
                            <div class="space-y-2">
                                <p class="text-sm"><strong>Name:</strong> {{ $verification->recipient->name }}</p>
                                <p class="text-sm"><strong>Organization:</strong> {{ $verification->recipient->organization_name ?? 'N/A' }}</p>
                                <p class="text-sm"><strong>Email:</strong> {{ $verification->recipient->email }}</p>
                                <p class="text-sm"><strong>Phone:</strong> {{ $verification->recipient->phone }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Pickup Details -->
                @if($verification->pickup_details && is_array($verification->pickup_details) && count($verification->pickup_details) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pickup Details</h3>
                    <div class="space-y-2">
                        @foreach($verification->pickup_details as $key => $value)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-sm text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quality Information -->
                @if($verification->quality_rating || $verification->quality_issues)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quality Assessment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($verification->quality_rating)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quality Rating</label>
                            <div class="mt-1 flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $verification->quality_rating)
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.719c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.719c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ $verification->quality_rating }}/5)</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quality Confirmed</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $verification->quality_confirmed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $verification->quality_confirmed ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    @endif
                    @if($verification->quality_issues)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Quality Issues</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $verification->quality_issues }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Notes -->
                @if($verification->recipient_notes || $verification->donor_notes || $verification->admin_notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                    <div class="space-y-4">
                        @if($verification->recipient_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recipient Notes</label>
                            <div class="mt-1 p-3 bg-blue-50 rounded-md">
                                <p class="text-sm text-gray-900">{{ $verification->recipient_notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($verification->donor_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Donor Notes</label>
                            <div class="mt-1 p-3 bg-green-50 rounded-md">
                                <p class="text-sm text-gray-900">{{ $verification->donor_notes }}</p>
                            </div>
                        </div>
                        @endif

                        @if($verification->admin_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin Notes</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900">{{ $verification->admin_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Location Data -->
                @if($verification->location_data && is_array($verification->location_data) && count($verification->location_data) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>
                    <div class="space-y-2">
                        @foreach($verification->location_data as $key => $value)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-sm text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- QR Code -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">QR Code</h3>
                    <div class="text-center">
                        <div class="bg-gray-100 p-4 rounded-lg inline-block">
                            <div class="text-xs text-gray-600 mb-2">Verification Code</div>
                            <div class="font-mono text-sm font-bold">{{ $verification->verification_code }}</div>
                        </div>
                        @if($verification->qr_code_scanned)
                        <p class="text-xs text-green-600 mt-2">✓ QR Code has been scanned</p>
                        @else
                        <p class="text-xs text-gray-500 mt-2">QR Code not yet scanned</p>
                        @endif
                    </div>
                </div>

                <!-- Photo Evidence -->
                @if($verification->photo_evidence && is_array($verification->photo_evidence) && count($verification->photo_evidence) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Photo Evidence</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($verification->photo_evidence as $photo)
                        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ count($verification->photo_evidence) }} photo(s) uploaded</p>
                </div>
                @endif

                <!-- Admin Actions -->
                @if($verification->verification_status === 'disputed')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Actions</h3>
                    <form action="{{ route('admin.pickup-verifications.resolve', $verification) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                            <select name="resolution" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="approved">Approve Pickup</option>
                                <option value="rejected">Reject Pickup</option>
                                <option value="investigate">Needs Investigation</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                      placeholder="Add resolution notes..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                            Resolve Dispute
                        </button>
                    </form>
                </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-2 w-2 bg-blue-400 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Verification Created</p>
                                <p class="text-xs text-gray-500">{{ $verification->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($verification->scanned_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-2 w-2 bg-yellow-400 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">QR Code Scanned</p>
                                <p class="text-xs text-gray-500">{{ $verification->scanned_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($verification->pickup_completed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-2 w-2 bg-green-400 rounded-full mt-2"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Pickup Completed</p>
                                <p class="text-xs text-gray-500">{{ $verification->pickup_completed_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection