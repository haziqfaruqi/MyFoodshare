@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Food Pickup Verification</h1>
            <p class="text-gray-600 mt-2">Scan QR code to verify pickup</p>
        </div>

        <!-- Food Details Card -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">{{ $foodListing->food_name }}</h2>
            <div class="text-sm text-blue-700 space-y-1">
                <p><strong>Quantity:</strong> {{ $foodListing->quantity }} {{ $foodListing->unit }}</p>
                <p><strong>Restaurant:</strong> {{ $donor->name }}</p>
                <p><strong>Pickup Location:</strong> {{ $foodListing->pickup_location }}</p>
                @if($foodListing->pickup_address)
                    <p><strong>Address:</strong> {{ $foodListing->pickup_address }}</p>
                @endif
                @if($foodListing->special_instructions)
                    <p><strong>Instructions:</strong> {{ $foodListing->special_instructions }}</p>
                @endif
            </div>
        </div>

        <!-- Status Display -->
        <div id="status-display" class="mb-6">
            <div id="pending-status" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-yellow-500 mr-3"></div>
                    <span class="text-yellow-800">Ready for QR code scanning</span>
                </div>
            </div>

            <div id="scanned-status" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-800">QR Code Scanned Successfully!</span>
                </div>
            </div>

            <div id="completed-status" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-blue-800">Pickup Completed!</span>
                </div>
            </div>
        </div>

        <!-- QR Scanner Button -->
        <div id="scanner-section" class="mb-6 space-y-3">
            <button id="scan-qr-btn" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                Scan QR Code
            </button>
            <div class="text-center text-gray-500">or</div>
            <button id="manual-verify-btn" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-200">
                Verify Without Scanning
            </button>
        </div>

        <!-- Completion Form -->
        <div id="completion-form" class="hidden">
            <form id="pickup-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quality Rating</label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400" data-rating="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" id="quality_rating" name="quality_rating" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" id="quality_confirmed" name="quality_confirmed" class="mr-2" required>
                        I confirm the food quality is acceptable
                    </label>
                </div>

                <div>
                    <label for="recipient_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="recipient_notes" name="recipient_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes..."></textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-200">
                    Complete Pickup
                </button>
            </form>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="hidden text-center">
            <div class="text-green-600 text-lg font-semibold mb-2">✅ Pickup Completed Successfully!</div>
            <p class="text-gray-600">Thank you for using MyFoodshare. The restaurant has been notified.</p>
        </div>
    </div>
</div>

<!-- QR Code Scanner Modal -->
<div id="qr-scanner-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Scan QR Code</h3>
                <button id="close-scanner" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="scanner-container" class="relative">
                <video id="qr-scanner-video" class="w-full rounded-lg"></video>
                <div class="absolute inset-0 border-2 border-blue-500 rounded-lg pointer-events-none">
                    <div class="absolute top-4 left-4 w-8 h-8 border-t-4 border-l-4 border-blue-500"></div>
                    <div class="absolute top-4 right-4 w-8 h-8 border-t-4 border-r-4 border-blue-500"></div>
                    <div class="absolute bottom-4 left-4 w-8 h-8 border-b-4 border-l-4 border-blue-500"></div>
                    <div class="absolute bottom-4 right-4 w-8 h-8 border-b-4 border-r-4 border-blue-500"></div>
                </div>
            </div>
            <p class="text-center text-sm text-gray-600 mt-2">Point your camera at the QR code</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
const verificationCode = '{{ $verification->verification_code }}';

// QR Scanner functionality
let video, canvas, context, isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    video = document.getElementById('qr-scanner-video');
    canvas = document.createElement('canvas');
    context = canvas.getContext('2d');

    // Bind events
    document.getElementById('scan-qr-btn').addEventListener('click', startScanning);
    document.getElementById('manual-verify-btn').addEventListener('click', manualVerify);
    document.getElementById('close-scanner').addEventListener('click', stopScanning);
    document.getElementById('pickup-form').addEventListener('submit', handlePickupSubmit);

    // Rating stars
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', handleRatingClick);
    });
});

async function manualVerify() {
    // Skip QR scanning and directly verify using the verification code from the page
    try {
        const location = await getCurrentLocation();

        const response = await fetch(`/api/pickup/scan/${verificationCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                latitude: location?.latitude,
                longitude: location?.longitude,
                accuracy: location?.accuracy
            })
        });

        const data = await response.json();

        if (data.success) {
            showScannedStatus();
        } else {
            alert(data.error || 'Failed to verify pickup');
        }
    } catch (error) {
        console.error('Verification error:', error);
        alert('Error verifying pickup: ' + error.message);
    }
}

async function startScanning() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });
        video.srcObject = stream;
        video.play();

        document.getElementById('qr-scanner-modal').classList.remove('hidden');
        isScanning = true;
        scanQRCode();
    } catch (error) {
        alert('Camera access denied or not available. Please use "Verify Without Scanning" button instead.');
    }
}

function stopScanning() {
    isScanning = false;
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    document.getElementById('qr-scanner-modal').classList.add('hidden');
}

function scanQRCode() {
    if (!isScanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            processQRCode(code.data);
            return;
        }
    }

    requestAnimationFrame(scanQRCode);
}

async function processQRCode(qrData) {
    stopScanning();

    // Extract verification code from URL or use directly
    const urlMatch = qrData.match(/\/pickup\/verify\/([^\/\?]+)/);
    const scannedCode = urlMatch ? urlMatch[1] : qrData;

    if (scannedCode !== verificationCode) {
        alert('This QR code is not valid for this pickup.');
        return;
    }

    try {
        // Get location if available
        const location = await getCurrentLocation();

        const response = await fetch(`/api/pickup/scan/${verificationCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                latitude: location?.latitude,
                longitude: location?.longitude,
                accuracy: location?.accuracy
            })
        });

        const data = await response.json();

        if (data.success) {
            showScannedStatus();
        } else {
            alert(data.error || 'Failed to scan QR code');
        }
    } catch (error) {
        alert('Error processing QR code: ' + error.message);
    }
}

async function getCurrentLocation() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) {
            resolve(null);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => resolve({
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            }),
            () => resolve(null),
            { timeout: 10000, maximumAge: 300000 }
        );
    });
}

function showScannedStatus() {
    document.getElementById('pending-status').classList.add('hidden');
    document.getElementById('scanned-status').classList.remove('hidden');
    document.getElementById('scanner-section').classList.add('hidden');
    document.getElementById('completion-form').classList.remove('hidden');
}

function handleRatingClick(e) {
    const rating = parseInt(e.target.dataset.rating);
    document.getElementById('quality_rating').value = rating;

    document.querySelectorAll('.rating-star').forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < rating);
        star.classList.toggle('text-gray-300', index >= rating);
    });
}

async function handlePickupSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    data.quality_confirmed = document.getElementById('quality_confirmed').checked;

    try {
        const response = await fetch(`/api/pickup/complete/${verificationCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showCompletedStatus();
        } else {
            alert(result.error || 'Failed to complete pickup');
        }
    } catch (error) {
        alert('Error completing pickup: ' + error.message);
    }
}

function showCompletedStatus() {
    document.getElementById('scanned-status').classList.add('hidden');
    document.getElementById('completion-form').classList.add('hidden');
    document.getElementById('success-message').classList.remove('hidden');
}
</script>
@endsection