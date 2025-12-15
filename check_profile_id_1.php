<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Check if restaurant profile with ID 1 exists
$profile = DB::table('restaurant_profiles')->where('id', 1)->first();

if ($profile) {
    echo "Restaurant profile ID 1 exists:\n";
    echo "ID: {$profile->id}\n";
    echo "User ID: {$profile->user_id}\n";
    echo "Name: {$profile->restaurant_name}\n";
    echo "Status: {$profile->status}\n";
} else {
    echo "Restaurant profile ID 1 does NOT exist\n";
}

// Check all restaurant profiles
echo "\nAll restaurant profiles:\n";
$profiles = DB::table('restaurant_profiles')->get();
foreach ($profiles as $p) {
    echo "ID: {$p->id}, User ID: {$p->user_id}, Name: {$p->restaurant_name}\n";
}

// Check user with ID 3
echo "\nUser with ID 3:\n";
$user = DB::table('users')->where('id', 3)->first();
if ($user) {
    echo "Found: {$user->name} (ID: {$user->id}, Role: {$user->role})\n";
} else {
    echo "User not found\n";
}