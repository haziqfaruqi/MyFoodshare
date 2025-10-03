<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// QR Code conversion endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-pickup-verification', [App\Http\Controllers\PickupVerificationController::class, 'createFromListing']);
    Route::post('/find-listing-by-code', [App\Http\Controllers\PickupVerificationController::class, 'findListingByCode']);
});

// QR Code Scanning API Routes (Public for recipients)
Route::prefix('pickup')->name('api.pickup.')->group(function () {
    Route::post('/scan/{code}', [App\Http\Controllers\QrCodeController::class, 'scanQrCode'])->name('scan');
    Route::post('/complete/{code}', [App\Http\Controllers\QrCodeController::class, 'completePickup'])->name('complete');
    Route::get('/status/{code}', [App\Http\Controllers\QrCodeController::class, 'getVerificationStatus'])->name('status');
});

// Restaurant QR Code Generation API
Route::middleware(['auth:sanctum'])->prefix('restaurant')->name('api.restaurant.')->group(function () {
    Route::post('/listings/{listing}/generate-qr', [App\Http\Controllers\QrCodeController::class, 'generateQrCode'])->name('generate-qr');
});