<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use App\Http\Controllers\Restaurant\FoodListingController;
use App\Http\Controllers\Restaurant\ProfileController as RestaurantProfileController;
use App\Http\Controllers\Recipient\FoodBrowsingController;
use App\Http\Controllers\Recipient\DashboardController as RecipientDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FoodListingMonitorController;
use App\Http\Controllers\Admin\PendingApprovalController;
use App\Http\Controllers\Admin\ActiveListingController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ListingApprovalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QrVerificationController;

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/register/donor', [RegisterController::class, 'showDonorForm'])->name('register.donor');
Route::post('/register/donor', [RegisterController::class, 'storeDonor'])->name('register.donor.store');
Route::get('/register/recipient', [RegisterController::class, 'showRecipientForm'])->name('register.recipient');
Route::post('/register/recipient', [RegisterController::class, 'storeRecipient'])->name('register.recipient.store');

// QR Code Verification Routes (Public)
Route::get('/food-listing/{id}/verify/{code}', [QrVerificationController::class, 'verify'])->name('food-listing.verify');
Route::get('/food-listing/{listing}/qr', [QrVerificationController::class, 'generateQr'])->name('food-listing.qr');
Route::post('/food-listing/{id}/verify/{code}/complete', [QrVerificationController::class, 'completePickup'])->name('food-listing.complete-pickup');

// Pickup Verification Routes
Route::get('/pickup/scanner', [App\Http\Controllers\PickupVerificationController::class, 'showScanner'])->name('pickup.scanner');
Route::get('/pickup/verify/{code}', [App\Http\Controllers\PickupVerificationController::class, 'verify'])->name('pickup.verify');
Route::post('/pickup/scan/{code}', [App\Http\Controllers\PickupVerificationController::class, 'scan'])->name('pickup.scan');
Route::get('/pickup/verification/{verification}/details', [App\Http\Controllers\PickupVerificationController::class, 'getVerificationDetails'])->name('pickup.verification.details');

// Debug route (remove after testing)
Route::get('/debug/listings', function() {
    $listings = \App\Models\FoodListing::with('user')
        ->where('approval_status', 'approved')
        ->where('status', 'active')
        ->where('expiry_date', '>=', now()->toDateString())
        ->get();
    
    $recipients = \App\Models\User::where('role', 'recipient')->get();
    
    return [
        'total_approved_listings' => $listings->count(),
        'total_recipients' => $recipients->count(),
        'listings' => $listings->map(function($listing) {
            return [
                'id' => $listing->id,
                'food_name' => $listing->food_name,
                'status' => $listing->status,
                'approval_status' => $listing->approval_status,
                'expiry_date' => $listing->expiry_date,
                'has_coordinates' => !is_null($listing->latitude) && !is_null($listing->longitude),
                'donor_status' => $listing->user->status ?? 'unknown',
                'created_at' => $listing->created_at
            ];
        }),
        'recipients' => $recipients->map(function($recipient) {
            return [
                'id' => $recipient->id,
                'name' => $recipient->name,
                'status' => $recipient->status,
                'has_coordinates' => !is_null($recipient->latitude) && !is_null($recipient->longitude)
            ];
        })
    ];
})->name('debug.listings');

// Notification Routes (Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/fcm-token', [NotificationController::class, 'updateFcmToken'])->name('notifications.update-fcm-token');
    Route::post('/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.update-preferences');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Pickup verification routes (authenticated)
    Route::post('/pickup/verification/{verification}/complete', [App\Http\Controllers\PickupVerificationController::class, 'completePickup'])->name('pickup.verification.complete');
    Route::post('/pickup/verification/{verification}/report-issue', [App\Http\Controllers\PickupVerificationController::class, 'reportIssue'])->name('pickup.verification.report-issue');
});


// Restaurant Owner Routes
Route::middleware(['auth', 'restaurant_owner'])->prefix('restaurant')->name('restaurant.')->group(function () {
    Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('listings', FoodListingController::class);
    
    // Match management routes for donors
    Route::get('/matches', [FoodListingController::class, 'manageMatches'])->name('matches.index');
    Route::patch('/listings/{listing}/matches/{match}/approve', [FoodListingController::class, 'approveMatch'])->name('listings.matches.approve');
    Route::patch('/listings/{listing}/matches/{match}/schedule', [FoodListingController::class, 'scheduleMatch'])->name('listings.matches.schedule');
    Route::patch('/listings/{listing}/matches/{match}/reject', [FoodListingController::class, 'rejectMatch'])->name('listings.matches.reject');
    
    // Reports route
    Route::get('/reports', [RestaurantDashboardController::class, 'reports'])->name('reports');
    
    // Donation tracking route
    Route::get('/track-donations', [RestaurantDashboardController::class, 'trackDonations'])->name('track-donations');
    
    // Profile routes
    Route::get('/profile', [RestaurantProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [RestaurantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [RestaurantProfileController::class, 'update'])->name('profile.update');
});

// Recipient Routes  
Route::middleware(['auth'])->prefix('recipient')->name('recipient.')->group(function () {
    Route::get('/dashboard', [RecipientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/browse', [FoodBrowsingController::class, 'index'])->name('browse.index');
    Route::get('/browse/map', [FoodBrowsingController::class, 'mapView'])->name('browse.map');
    Route::get('/browse/{listing}', [FoodBrowsingController::class, 'show'])->name('browse.show');
    Route::post('/browse/{listing}/interest', [FoodBrowsingController::class, 'expressInterest'])->name('browse.express-interest');
    Route::get('/matches', [FoodBrowsingController::class, 'myMatches'])->name('matches.index');
    
    // Match status tracking routes
    Route::patch('/matches/{match}/confirm', [FoodBrowsingController::class, 'confirmPickup'])->name('matches.confirm');
    Route::patch('/matches/{match}/schedule', [FoodBrowsingController::class, 'schedulePickup'])->name('matches.schedule');
    Route::patch('/matches/{match}/complete', [FoodBrowsingController::class, 'completePickup'])->name('matches.complete');
    Route::patch('/matches/{match}/cancel', [FoodBrowsingController::class, 'cancelMatch'])->name('matches.cancel');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
    Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    
    // Pending Approvals
    Route::get('/pending-approvals', [PendingApprovalController::class, 'index'])->name('pending-approvals.index');
    Route::get('/pending-approvals/{user}', [PendingApprovalController::class, 'show'])->name('pending-approvals.show');
    Route::patch('/pending-approvals/{user}/approve', [PendingApprovalController::class, 'approve'])->name('pending-approvals.approve');
    Route::patch('/pending-approvals/{user}/reject', [PendingApprovalController::class, 'reject'])->name('pending-approvals.reject');
    
    // Active Listings Management
    Route::get('/active-listings', [ActiveListingController::class, 'index'])->name('active-listings.index');
    Route::get('/active-listings/{listing}', [ActiveListingController::class, 'show'])->name('active-listings.show');
    Route::patch('/active-listings/{listing}/deactivate', [ActiveListingController::class, 'deactivate'])->name('active-listings.deactivate');
    Route::patch('/active-listings/{listing}/expire', [ActiveListingController::class, 'markExpired'])->name('active-listings.expire');
    
    // Analytics & Trends
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Listing Approvals
    Route::get('/listing-approvals', [ListingApprovalController::class, 'index'])->name('listing-approvals.index');
    Route::get('/listing-approvals/{listing}', [ListingApprovalController::class, 'show'])->name('listing-approvals.show');
    Route::patch('/listing-approvals/{listing}/approve', [ListingApprovalController::class, 'approve'])->name('listing-approvals.approve');
    Route::patch('/listing-approvals/{listing}/reject', [ListingApprovalController::class, 'reject'])->name('listing-approvals.reject');
    Route::post('/listing-approvals/bulk-approve', [ListingApprovalController::class, 'bulkApprove'])->name('listing-approvals.bulk-approve');
    
    // Food Listing Monitoring (Legacy)
    Route::get('/food-listings', [FoodListingMonitorController::class, 'index'])->name('food-listings.index');
    Route::get('/food-listings/{listing}', [FoodListingMonitorController::class, 'show'])->name('food-listings.show');
    Route::patch('/food-listings/{listing}/expire', [FoodListingMonitorController::class, 'markExpired'])->name('food-listings.expire');
    Route::patch('/food-listings/{listing}/deactivate', [FoodListingMonitorController::class, 'deactivate'])->name('food-listings.deactivate');
    Route::post('/food-listings/bulk-expire', [FoodListingMonitorController::class, 'bulkExpireOld'])->name('food-listings.bulk-expire');
    Route::get('/food-listings/compliance/report', [FoodListingMonitorController::class, 'complianceReport'])->name('food-listings.compliance');
    
    // Pickup Verification Management
    Route::get('/pickup-verifications', [App\Http\Controllers\PickupVerificationController::class, 'adminIndex'])->name('pickup-verifications.index');
    Route::get('/pickup-verifications/{verification}', [App\Http\Controllers\PickupVerificationController::class, 'adminReview'])->name('pickup-verifications.show');
    Route::post('/pickup-verifications/{verification}/resolve', [App\Http\Controllers\PickupVerificationController::class, 'adminResolve'])->name('pickup-verifications.resolve');
});