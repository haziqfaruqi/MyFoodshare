<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\RestaurantProfile;

class RemapRestaurantProfiles extends Command
{
    protected $signature = 'app:remap-restaurant-profiles';
    protected $description = 'Remap restaurant profiles for users';

    public function handle()
    {
        $this->info('Remapping restaurant profiles...');

        // Get all donor users
        $users = User::where('role', 'donor')->get();

        foreach ($users as $user) {
            $this->info("Processing user: {$user->name} (ID: {$user->id})");

            // Check if user has a restaurant profile
            $profile = $user->restaurantProfile;

            if ($profile) {
                $this->line("- Has profile: ID {$profile->id}");
            } else {
                $this->error("- No profile found!");

                // Create a profile for this user
                $profile = RestaurantProfile::create([
                    'user_id' => $user->id,
                    'restaurant_name' => $user->restaurant_name ?? $user->name . "'s Restaurant",
                    'address' => $user->address ?? '',
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'description' => $user->description ?? '',
                    'business_license' => $user->business_license ?? null,
                    'cuisine_type' => $user->cuisine_type ?? 'general',
                    'restaurant_capacity' => $user->restaurant_capacity ?? 50,
                    'status' => $user->status ?? 'active',
                ]);

                $this->info("- Created new profile: ID {$profile->id}");
            }
        }

        $this->info('Restaurant profile remapping completed!');
        return 0;
    }
}