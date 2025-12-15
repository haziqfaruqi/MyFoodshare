<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\FoodListing;

// Check food listings without restaurant_profile_id
$listings = FoodListing::whereNull('restaurant_profile_id')->get();

echo "Food listings without restaurant_profile_id: " . $listings->count() . "\n";

foreach ($listings as $listing) {
    echo "ID: {$listing->id}, Created by: {$listing->created_by}\n";
}

// Check total food listings
$total = FoodListing::count();
echo "\nTotal food listings: {$total}\n";

// Check restaurant profiles
$profiles = \App\Models\RestaurantProfile::count();
echo "Total restaurant profiles: {$profiles}\n";