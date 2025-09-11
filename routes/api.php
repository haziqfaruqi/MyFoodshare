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