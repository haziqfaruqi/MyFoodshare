<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "Checking food_listings table...\n";

// Check existing food listings without restaurant_profile_id
$nullListings = DB::table('food_listings')->whereNull('restaurant_profile_id')->count();
echo "Food listings without restaurant_profile_id: {$nullListings}\n";

// Get user's restaurant profile
$userId = 1; // Assuming this is the user being tested
$profile = DB::table('restaurant_profiles')->where('user_id', $userId)->first();

if ($profile) {
    echo "Restaurant profile found for user {$userId}: ID {$profile->id}\n";

    // Update any null records
    $updated = DB::table('food_listings')
        ->where('created_by', $userId)
        ->whereNull('restaurant_profile_id')
        ->update(['restaurant_profile_id' => $profile->id]);

    if ($updated > 0) {
        echo "Updated {$updated} food listings with restaurant_profile_id\n";
    } else {
        echo "No food listings needed updating\n";
    }
} else {
    echo "No restaurant profile found for user {$userId}\n";
}