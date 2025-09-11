@extends('layouts.app')

@section('title', 'QR Code Scanner - MyFoodshare')

@push('head')
<style>
#qr-reader {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    min-height: 300px;
}

#qr-reader video {
    width: 100% !important;
    height: auto !important;
    object-fit: cover;
}

.scanner-container {
    position: relative;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 10;
}

.scan-region {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 250px;
    height: 250px;
    margin-left: -125px;
    margin-top: -125px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 8px;
}

.scan-corners {
    position: absolute;
    width: 30px;
    height: 30px;
}

.scan-corners::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 4px;
    background: #10b981;
    border-radius: 2px;
}

.scan-corners::after {
    content: '';
    position: absolute;
    width: 4px;
    height: 20px;
    background: #10b981;
    border-radius: 2px;
}

.scan-corners.top-left {
    top: -15px;
    left: -15px;
}

.scan-corners.top-left::before {
    top: 11px;
    left: 11px;
}

.scan-corners.top-left::after {
    top: 11px;
    left: 11px;
}

.scan-corners.top-right {
    top: -15px;
    right: -15px;
    transform: rotate(90deg);
}

.scan-corners.bottom-left {
    bottom: -15px;
    left: -15px;
    transform: rotate(-90deg);
}

.scan-corners.bottom-right {
    bottom: -15px;
    right: -15px;
    transform: rotate(180deg);
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
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Scan Pickup QR Code</h1>
            <p class="text-gray-600 mt-2">Point your camera at the QR code to verify pickup</p>
        </div>

        <!-- Scanner Interface -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <!-- Camera Permission Status -->
                <div id="permission-status" class="mb-4 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">Requesting camera permission...</p>
                    </div>
                </div>

                <!-- Scanner Container -->
                <div class="scanner-container mb-4" id="scanner-container" style="display: none;">
                    <div id="qr-reader"></div>
                    <div class="scanner-overlay">
                        <div class="scan-region">
                            <div class="scan-corners top-left"></div>
                            <div class="scan-corners top-right"></div>
                            <div class="scan-corners bottom-left"></div>
                            <div class="scan-corners bottom-right"></div>
                        </div>
                    </div>
                </div>

                <!-- Manual Code Entry -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Or Enter Code Manually</h3>
                    <div class="flex space-x-3">
                        <input type="text" 
                               id="manual-code" 
                               placeholder="VRF-XXXXXXXX or 8-digit code"
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <button onclick="processManualCode()" 
                                class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors font-medium">
                            Verify
                        </button>
                    </div>
                </div>

                <!-- Status Messages -->
                <div id="scan-status" class="mt-4" style="display: none;"></div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-3">How to scan:</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                <li>Allow camera access when prompted</li>
                <li>Point your camera at the QR code shown by the donor</li>
                <li>Keep the QR code within the scanning frame</li>
                <li>The app will automatically detect and process the code</li>
                <li>Follow the prompts to complete the pickup verification</li>
            </ol>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner;
let isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    initializeScanner();
});

function initializeScanner() {
    // Check for HTTPS
    if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
        showStatus('Camera access requires HTTPS. Please use the HTTPS ngrok URL.', 'error');
        return;
    }

    // Check for camera support
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showStatus('Camera not supported on this device. Please use manual code entry.', 'error');
        return;
    }

    // First request camera permission explicitly
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            // Stop the test stream
            stream.getTracks().forEach(track => track.stop());
            
            // Now get available cameras
            return Html5Qrcode.getCameras();
        })
        .then(devices => {
            if (devices && devices.length) {
                // Prefer back camera for mobile
                let cameraId = devices[0].id;
                const backCamera = devices.find(device => 
                    device.label.toLowerCase().includes('back') || 
                    device.label.toLowerCase().includes('rear') ||
                    device.label.toLowerCase().includes('environment')
                );
                if (backCamera) {
                    cameraId = backCamera.id;
                }
                startScanner(cameraId);
            } else {
                showStatus('No cameras found. Please use manual code entry.', 'error');
            }
        })
        .catch(err => {
            console.error('Error getting camera access:', err);
            let message = 'Camera access denied. ';
            if (err.name === 'NotAllowedError') {
                message += 'Please allow camera access in your browser settings and refresh the page.';
            } else if (err.name === 'NotFoundError') {
                message += 'No camera found on this device.';
            } else {
                message += 'Please use manual code entry.';
            }
            showStatus(message, 'error');
        });
}

function startScanner(cameraId) {
    const config = {
        fps: 10,
        qrbox: function(viewfinderWidth, viewfinderHeight) {
            // Make QR box responsive
            let minEdgePercentage = 0.7;
            let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
            let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
            return {
                width: qrboxSize,
                height: qrboxSize
            };
        },
        aspectRatio: 1.0,
        // Use back camera on mobile
        videoConstraints: {
            facingMode: 'environment'
        }
    };

    html5QrcodeScanner = new Html5Qrcode("qr-reader");
    
    html5QrcodeScanner.start(cameraId, config, onScanSuccess, onScanFailure)
        .then(() => {
            isScanning = true;
            document.getElementById('permission-status').style.display = 'none';
            document.getElementById('scanner-container').style.display = 'block';
            showStatus('Scanner ready. Point camera at QR code.', 'info');
        })
        .catch(err => {
            console.error('Error starting scanner:', err);
            let message = 'Failed to start camera. ';
            if (err.toString().includes('NotAllowedError')) {
                message += 'Camera permission denied. Please allow camera access and refresh.';
            } else if (err.toString().includes('NotFoundError')) {
                message += 'Camera not found or not accessible.';
            } else {
                message += 'Please use manual code entry.';
            }
            showStatus(message, 'error');
        });
}

function onScanSuccess(decodedText, decodedResult) {
    if (!isScanning) return;
    
    console.log('QR Code detected:', decodedText);
    
    // Stop the scanner
    isScanning = false;
    html5QrcodeScanner.stop().then(() => {
        // Process the scanned code
        processQrCode(decodedText);
    });
}

function onScanFailure(error) {
    // Ignore scanning failures - they're normal during scanning
}

function processQrCode(qrData) {
    showStatus('Processing QR code...', 'info');
    
    console.log('QR Data received:', qrData);
    
    // Extract verification code from QR data
    let verificationCode;
    let isPickupVerification = false;
    
    // Handle PickupVerification QR codes (VRF-XXXXXXXX format)
    if (qrData.includes('/pickup/verify/')) {
        const parts = qrData.split('/');
        verificationCode = parts[parts.length - 1];
        isPickupVerification = true;
    } else if (qrData.match(/^VRF-[A-Z0-9]{8}$/)) {
        verificationCode = qrData;
        isPickupVerification = true;
    }
    // Handle FoodListing QR codes (old format)
    else if (qrData.includes('/food-listing/verify/')) {
        // Extract listing ID and code from URL: /food-listing/verify/{id}/{code}
        const urlParts = qrData.split('/');
        const listingId = urlParts[urlParts.length - 2];
        const listingCode = urlParts[urlParts.length - 1];
        
        // Convert to pickup verification by creating one
        createPickupVerificationFromListing(listingId, listingCode);
        return;
    }
    // Handle direct 8-character codes (FoodListing format)
    else if (qrData.match(/^[A-Z0-9]{8}$/)) {
        // This might be a FoodListing verification code
        // We need to find which listing this belongs to
        findListingByCode(qrData);
        return;
    }
    else {
        showStatus('Invalid QR code format. Please try again or use manual entry.', 'error');
        restartScanner();
        return;
    }

    if (isPickupVerification) {
        // Handle pickup verification codes
        handlePickupVerification(verificationCode);
    }
}

function createPickupVerificationFromListing(listingId, listingCode) {
    showStatus('Creating pickup verification...', 'info');
    
    fetch('/api/create-pickup-verification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            listing_id: listingId,
            listing_code: listingCode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            handlePickupVerification(data.verification_code);
        } else {
            showStatus(data.error || 'Failed to create pickup verification.', 'error');
            restartScanner();
        }
    })
    .catch(error => {
        console.error('Error creating verification:', error);
        showStatus('Network error. Please try again.', 'error');
        restartScanner();
    });
}

function findListingByCode(code) {
    showStatus('Looking up QR code...', 'info');
    
    fetch('/api/find-listing-by-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            createPickupVerificationFromListing(data.listing_id, code);
        } else {
            showStatus(data.error || 'QR code not found or invalid.', 'error');
            restartScanner();
        }
    })
    .catch(error => {
        console.error('Error finding listing:', error);
        showStatus('Network error. Please try again.', 'error');
        restartScanner();
    });
}

function handlePickupVerification(verificationCode) {
    // Get location data
    getCurrentLocation().then(locationData => {
        // Send scan request
        fetch(`/pickup/scan/${verificationCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(locationData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('QR code verified successfully! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = `/pickup/verify/${verificationCode}`;
                }, 1500);
            } else {
                showStatus(data.error || 'Verification failed. Please try again.', 'error');
                restartScanner();
            }
        })
        .catch(error => {
            console.error('Scan error:', error);
            showStatus('Network error. Please try again.', 'error');
            restartScanner();
        });
    });
}

function processManualCode() {
    const code = document.getElementById('manual-code').value.trim().toUpperCase();
    
    // Accept both VRF-XXXXXXXX and 8-character codes
    if (!code.match(/^VRF-[A-Z0-9]{8}$/) && !code.match(/^[A-Z0-9]{8}$/)) {
        showStatus('Invalid code format. Code should be VRF-XXXXXXXX or 8-character code', 'error');
        return;
    }
    
    processQrCode(code);
}

function getCurrentLocation() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) {
            resolve({});
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
                });
            },
            (error) => {
                console.log('Location access denied or failed:', error);
                resolve({});
            },
            { timeout: 10000, maximumAge: 60000 }
        );
    });
}

function restartScanner() {
    setTimeout(() => {
        if (html5QrcodeScanner) {
            initializeScanner();
        }
    }, 3000);
}

function showStatus(message, type) {
    const statusDiv = document.getElementById('scan-status');
    const colorClasses = {
        info: 'bg-blue-50 border-blue-200 text-blue-800',
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
    };
    
    statusDiv.className = `p-4 rounded-lg border ${colorClasses[type] || colorClasses.info}`;
    statusDiv.textContent = message;
    statusDiv.style.display = 'block';
}

// Handle manual code input
document.getElementById('manual-code').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        processManualCode();
    }
});
</script>
@endsection