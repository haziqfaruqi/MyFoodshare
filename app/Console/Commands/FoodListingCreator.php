<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\RestaurantProfile;
use App\Models\FoodListing;

class FoodListingCreator extends Command
{
    protected $signature = 'app:create-food-listing {user_id?}';
    protected $description = 'Create a food listing for testing';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? 3; // Default to user ID 3

        $this->info("Creating food listing for user ID: {$userId}");

        try {
            // Get the user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found");
                return 1;
            }

            $this->info("User: {$user->name}");

            // Get restaurant profile
            $profile = $user->restaurantProfile;
            if (!$profile) {
                $this->error("No restaurant profile found for user {$userId}");
                return 1;
            }

            $this->info("Restaurant Profile ID: {$profile->id}, Name: {$profile->restaurant_name}");

            // Create food listing manually
            $listing = new FoodListing([
                'created_by' => $user->id,
                'restaurant_profile_id' => $profile->id,
                'food_name' => 'Test Food Listing',
                'description' => 'This is a test food listing',
                'category' => 'vegetables',
                'quantity' => 10,
                'unit' => 'kg',
                'expiry_date' => now()->addDays(3)->toDateString(),
                'expiry_time' => '12:00',
                'pickup_location' => 'Test Pickup Location',
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'pickup_address' => $user->address ?? '123 Main Street',
                'special_instructions' => 'Handle with care',
                'dietary_info' => [],
                'images' => [],
                'status' => 'active',
            ]);

            if ($listing->save()) {
                $this->info("SUCCESS: Created food listing ID {$listing->id}");
                return 0;
            } else {
                $this->error("FAILED: Could not save food listing");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("ERROR: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());

            // Print the full stack trace
            $this->error("\nStack trace:");
            $this->line($e->getTraceAsString());

            return 1;
        }
    }
}