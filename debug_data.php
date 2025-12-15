<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Check restaurant profiles table
echo "=== Restaurant Profiles ===\n";
$profiles = DB::table('restaurant_profiles')->get();
foreach ($profiles as $profile) {
    echo "ID: {$profile->id}, User ID: {$profile->user_id}, Name: {$profile->restaurant_name}\n";
}

// Check users table for donor users
echo "\n=== Donor Users ===\n";
$users = DB::table('users')->where('role', 'donor')->get();
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
}

// Check user ID 3 specifically
echo "\n=== User ID 3 Details ===\n";
$user3 = DB::table('users')->where('id', 3)->first();
if ($user3) {
    echo "Found: {$user3->name} (ID: {$user3->id})\n";
} else {
    echo "User not found\n";
}

// Check if profile with user_id=3 exists
echo "\n=== Profile for User 3 ===\n";
$profileForUser3 = DB::table('restaurant_profiles')->where('user_id', 3)->first();
if ($profileForUser3) {
    echo "Profile ID: {$profileForUser3->id}, Restaurant: {$profileForUser3->restaurant_name}\n";
} else {
    echo "No profile found for user 3\n";
}