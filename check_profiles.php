<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Check for users without restaurant profiles
$users = \App\Models\User::where('role', 'donor')->doesntHave('restaurantProfile')->get();

echo "Users without restaurant profiles:\n";
echo "Count: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
}

// Check restaurant profiles count
$totalProfiles = \App\Models\RestaurantProfile::count();
echo "\nTotal restaurant profiles: {$totalProfiles}\n";