@extends('layouts.app')

@section('title', 'Verify Pickup - MyFoodshare')

@push('head')
<style>
.rating-stars {
    display: flex;
    gap: 4px;
}

.rating-star {
    width: 24px;
    height: 24px;
    cursor: pointer;
    fill: #d1d5db;
    transition: fill 0.2s;
}

.rating-star:hover,
.rating-star.active {
    fill: #fbbf24;
}

.photo-preview {
    max-width: 150px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
@endpush

@section('navbar')
@if(auth()->user()->role === 'donor')
    @include('layouts.restaurant')
@elseif(auth()->user()->role === 'recipient')  
    @include('layouts.recipient')
@else
    @include('layouts.admin')
@endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Pickup Verification</h1>
            <p class="text-gray-600 mt-2">
                @if($verification->verification_status === 'verified')
                    Complete your pickup and confirm receipt
                @else
                    Scan successful! Please complete the verification
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Food Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Verification Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Verification Details</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($verification->verification_status === 'verified') bg-green-100 text-green-800
                            @elseif($verification->verification_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($verification->verification_status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Verification Code</p>
                            <p class="font-mono font-medium">{{ $verification->verification_code }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Scanned At</p>
                            <p class="font-medium">
                                {{ $verification->scanned_at ? $verification->scanned_at->format('M d, Y H:i') : 'Not scanned' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Food Item Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Food Item Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $verification->foodListing->food_name }}</h3>
                                <p class="text-gray-600">{{ $verification->foodListing->description }}</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Category</p>
                                    <p class="font-medium">{{ ucfirst($verification->foodListing->category) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Quantity</p>
                                    <p class="font-medium">{{ $verification->foodListing->quantity }} {{ $verification->foodListing->unit }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Best Before</p>
                                    <p class="font-medium">{{ $verification->foodListing->expiry_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Donor</p>
                                    <p class="font-medium">{{ $verification->donor->name }}</p>
                                </div>
                            </div>
                        </div>

                        @if($verification->foodListing->images && count($verification->foodListing->images) > 0)
                        <div>
                            <p class="text-gray-500 mb-2">Food Images</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(array_slice($verification->foodListing->images, 0, 4) as $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="Food image" 
                                     class="w-full h-20 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($verification->foodListing->special_instructions)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-4 w-4 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Special Instructions</p>
                                <p class="text-sm text-yellow-700">{{ $verification->foodListing->special_instructions }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Pickup Completion Form -->
                @if($verification->verification_status === 'verified' && !$verification->pickup_completed_at)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Complete Pickup</h2>
                    
                    <form action="{{ route('pickup.verification.complete', $verification) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Quantity Received -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Received</label>
                                <select name="quantity_received" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select quantity received</option>
                                    <option value="full">Full quantity ({{ $verification->foodListing->quantity }} {{ $verification->foodListing->unit }})</option>
                                    <option value="partial">Partial quantity</option>
                                    <option value="none">No food received</option>
                                </select>
                            </div>

                            <!-- Condition -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Food Condition</label>
                                <select name="condition" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select condition</option>
                                    <option value="excellent">Excellent - Perfect condition</option>
                                    <option value="good">Good - Minor imperfections</option>
                                    <option value="fair">Fair - Some issues but still usable</option>
                                    <option value="poor">Poor - Significant quality issues</option>
                                </select>
                            </div>

                            <!-- Quality Rating -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Overall Quality Rating</label>
                                <div class="rating-stars" data-rating="0">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="rating-star" data-rating="{{ $i }}" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <input type="hidden" name="quality_rating" id="quality_rating" required>
                                <p class="text-sm text-gray-500 mt-1">Click stars to rate (1-5 stars)</p>
                            </div>

                            <!-- Quality Issues -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="has_issues" id="has_issues" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="has_issues" class="ml-2 block text-sm font-medium text-gray-700">Report quality issues</label>
                                </div>
                                <textarea name="quality_issues" 
                                          id="quality_issues" 
                                          rows="3" 
                                          placeholder="Describe any quality issues (optional)"
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                          style="display: none;"></textarea>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                                <textarea name="recipient_notes" 
                                          rows="3" 
                                          placeholder="Any additional comments about the pickup"
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                            </div>

                            <!-- Photo Evidence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Photos (Optional)</label>
                                <input type="file" 
                                       name="photos[]" 
                                       multiple 
                                       accept="image/*"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <p class="text-sm text-gray-500 mt-1">Upload photos of the food received (max 5 photos, 5MB each)</p>
                                <div id="photo-preview" class="mt-2 flex flex-wrap gap-2"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="flex-1 bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                                    Complete Pickup
                                </button>
                                <button type="button" 
                                        onclick="reportIssue()" 
                                        class="bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 transition-colors font-medium">
                                    Report Issue
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Already Completed -->
                @if($verification->pickup_completed_at)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-green-900">Pickup Completed!</h2>
                            <p class="text-green-700">Completed on {{ $verification->pickup_completed_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($verification->pickup_details)
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        @if(isset($verification->pickup_details['quantity_received']))
                        <div>
                            <p class="text-green-600 font-medium">Quantity Received</p>
                            <p class="text-green-900">{{ ucfirst($verification->pickup_details['quantity_received']) }}</p>
                        </div>
                        @endif
                        
                        @if(isset($verification->pickup_details['condition']))
                        <div>
                            <p class="text-green-600 font-medium">Food Condition</p>
                            <p class="text-green-900">{{ ucfirst($verification->pickup_details['condition']) }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('recipient.matches.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            View All Matches
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Location -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pickup Location</h3>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">{{ $verification->foodListing->pickup_location }}</p>
                            @if($verification->foodListing->pickup_address)
                            <p class="text-sm text-gray-500">{{ $verification->foodListing->pickup_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donor Contact</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $verification->donor->name }}</p>
                            @if($verification->donor->restaurant_name)
                            <p class="text-sm text-gray-500">{{ $verification->donor->restaurant_name }}</p>
                            @endif
                        </div>
                        
                        @if($verification->donor->phone)
                        <div class="flex items-center">
                            <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">{{ $verification->donor->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Need Help?</h3>
                    <p class="text-sm text-blue-700 mb-3">
                        If you encounter any issues during pickup, you can report them and our support team will help resolve the situation.
                    </p>
                    <button onclick="reportIssue()" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                        Report Issue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Rating stars functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('quality_rating');
    let currentRating = 0;

    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            currentRating = index + 1;
            ratingInput.value = currentRating;
            updateStars();
        });

        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
    });

    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        highlightStars(currentRating);
    });

    function updateStars() {
        highlightStars(currentRating);
    }

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    // Quality issues toggle
    const hasIssuesCheckbox = document.getElementById('has_issues');
    const qualityIssuesTextarea = document.getElementById('quality_issues');

    if (hasIssuesCheckbox && qualityIssuesTextarea) {
        hasIssuesCheckbox.addEventListener('change', function() {
            if (this.checked) {
                qualityIssuesTextarea.style.display = 'block';
                qualityIssuesTextarea.required = true;
            } else {
                qualityIssuesTextarea.style.display = 'none';
                qualityIssuesTextarea.required = false;
            }
        });
    }

    // Photo preview
    const photoInput = document.querySelector('input[name="photos[]"]');
    const photoPreview = document.getElementById('photo-preview');

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            photoPreview.innerHTML = '';
            
            Array.from(e.target.files).slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'photo-preview';
                    photoPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});

function reportIssue() {
    // This would open a modal or redirect to an issue reporting form
    const issueTypes = [
        'Food quality not as expected',
        'Incorrect quantity',
        'Donor not present',
        'Wrong pickup location',
        'Safety concerns',
        'Other'
    ];
    
    const selectedIssue = prompt('Please select an issue type:\n' + 
        issueTypes.map((type, index) => `${index + 1}. ${type}`).join('\n') + 
        '\n\nEnter the number (1-' + issueTypes.length + '):');
    
    if (selectedIssue && selectedIssue >= 1 && selectedIssue <= issueTypes.length) {
        const description = prompt('Please describe the issue in detail:');
        if (description) {
            // Submit issue report
            reportIssueToServer(issueTypes[selectedIssue - 1], description);
        }
    }
}

function reportIssueToServer(issueType, description) {
    const formData = new FormData();
    formData.append('issue_type', issueType);
    formData.append('description', description);
    formData.append('severity', 'medium');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch(`/pickup/verification/{{ $verification->id }}/report-issue`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert('Issue reported successfully. Our support team will review it shortly.');
    })
    .catch(error => {
        console.error('Error reporting issue:', error);
        alert('Failed to report issue. Please try again.');
    });
}
</script>
@endsection