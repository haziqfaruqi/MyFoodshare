<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RestaurantProfile;
use App\Models\Recipient;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@myfoodshare.com'
        ], [
            'name' => 'System Administrator',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Restaurant Owner - Golden Spoon
        $goldenSpoonUser = User::firstOrCreate([
            'email' => 'owner@goldenspoon.com'
        ], [
            'name' => 'Golden Spoon Restaurant',
            'password' => Hash::make('owner123'),
            'role' => 'donor',
            'status' => 'active',
            'email_verified_at' => now(),
            'restaurant_name' => 'Golden Spoon Restaurant',
            'cuisine_type' => 'International',
            'restaurant_capacity' => 125,
            'business_license' => 'BS-2024-001',
            'address' => '123 Main Street, Shah Alam',
            'latitude' => 3.0738,
            'longitude' => 101.5183,
            'description' => 'Fine dining restaurant specializing in international cuisine',
        ]);

        // Restaurant Profile for Golden Spoon
        RestaurantProfile::create([
            'user_id' => $goldenSpoonUser->id,
            'restaurant_name' => 'Golden Spoon Restaurant',
            'cuisine_type' => 'International',
            'restaurant_capacity' => 125,
            'business_license' => 'BS-2024-001',
            'address' => '123 Main Street, Shah Alam',
            'latitude' => 3.0738,
            'longitude' => 101.5183,
            'description' => 'Fine dining restaurant specializing in international cuisine',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        // Restaurant Owner - Pizza Corner
        $pizzaCornerUser = User::firstOrCreate([
            'email' => 'owner@pizzacorner.com'
        ], [
            'name' => 'Pizza Corner',
            'password' => Hash::make('pizza123'),
            'role' => 'donor',
            'status' => 'active',
            'email_verified_at' => now(),
            'restaurant_name' => 'Pizza Corner',
            'cuisine_type' => 'Italian/Pizza',
            'restaurant_capacity' => 65,
            'business_license' => 'BS-2024-002',
            'address' => '456 Pizza Avenue, Shah Alam',
            'latitude' => 3.0756,
            'longitude' => 101.5200,
            'description' => 'Family-friendly pizza restaurant with authentic Italian recipes',
        ]);

        // Restaurant Profile for Pizza Corner
        RestaurantProfile::create([
            'user_id' => $pizzaCornerUser->id,
            'restaurant_name' => 'Pizza Corner',
            'cuisine_type' => 'Italian/Pizza',
            'restaurant_capacity' => 65,
            'business_license' => 'BS-2024-002',
            'address' => '456 Pizza Avenue, Shah Alam',
            'latitude' => 3.0756,
            'longitude' => 101.5200,
            'description' => 'Family-friendly pizza restaurant with authentic Italian recipes',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        // Recipient - Hope Foundation
        $hopeFoundationUser = User::firstOrCreate([
            'email' => 'recipient@hopefoundation.org'
        ], [
            'name' => 'Hope Foundation',
            'password' => Hash::make('hope123'),
            'role' => 'recipient',
            'status' => 'active',
            'email_verified_at' => now(),
            'organization_name' => 'Hope Foundation',
            'contact_person' => 'Sarah Johnson',
            'recipient_capacity' => 200,
            'address' => '789 Hope Street, Shah Alam',
            'latitude' => 3.0780,
            'longitude' => 101.5220,
            'dietary_requirements' => json_encode(['Vegetarian options needed', 'Halal food']),
            'needs_preferences' => json_encode(['Fresh produce', 'Dairy products', 'Whole grains']),
        ]);

        // Recipient Profile for Hope Foundation
        Recipient::firstOrCreate([
            'email' => 'recipient@hopefoundation.org'
        ], [
            'organization_name' => 'Hope Foundation',
            'contact_person' => 'Sarah Johnson',
            'address' => '789 Hope Street, Shah Alam',
            'capacity' => 200,
            'dietary_requirements' => json_encode(['Vegetarian options needed', 'Halal food']),
            'rating' => 4.5,
            'status' => 'active',
            'phone' => '012-345-6789',
        ]);

        // Create some sample food listings for the restaurants
        $this->createSampleFoodListings($goldenSpoonUser, $pizzaCornerUser);
    }

    private function createSampleFoodListings($goldenSpoonUser, $pizzaCornerUser)
    {
        // Get the restaurant profiles
        $goldenSpoonProfile = RestaurantProfile::where('user_id', $goldenSpoonUser->id)->first();
        $pizzaCornerProfile = RestaurantProfile::where('user_id', $pizzaCornerUser->id)->first();

        // Sample food listings for Golden Spoon
        $foodListings = [
            [
                'food_name' => 'Grilled Salmon with Herbs',
                'description' => 'Fresh Atlantic salmon grilled with Mediterranean herbs',
                'category' => 'Main Course',
                'quantity' => 20,
                'unit' => 'portions',
                'expiry_date' => now()->addDay(),
                'expiry_time' => '14:00',
                'pickup_location' => 'Golden Spoon Restaurant Kitchen',
                'pickup_address' => '123 Main Street, Shah Alam',
                'special_instructions' => 'Keep refrigerated, consume within 24 hours',
                'dietary_info' => json_encode(['Seafood', 'High Protein']),
                'status' => 'active',
                'approval_status' => 'approved',
                'created_by' => $goldenSpoonUser->id,
            ],
            [
                'food_name' => 'Vegetable Stir Fry',
                'description' => 'Mixed seasonal vegetables with tofu in teriyaki sauce',
                'category' => 'Vegetarian',
                'quantity' => 15,
                'unit' => 'portions',
                'expiry_date' => now()->addDay(),
                'expiry_time' => '13:00',
                'pickup_location' => 'Restaurant Side Entrance',
                'pickup_address' => '123 Main Street, Shah Alam',
                'special_instructions' => 'Vegetarian, contains nuts',
                'dietary_info' => json_encode(['Vegetarian', 'Contains Nuts']),
                'status' => 'active',
                'approval_status' => 'approved',
                'created_by' => $goldenSpoonUser->id,
            ],
        ];

        foreach ($foodListings as $listing) {
            $foodListing = \App\Models\FoodListing::create(array_merge($listing, [
                'restaurant_profile_id' => $goldenSpoonProfile->id,
                'latitude' => $goldenSpoonProfile->latitude,
                'longitude' => $goldenSpoonProfile->longitude,
            ]));
        }

        // Sample food listings for Pizza Corner
        $pizzaListings = [
            [
                'food_name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil',
                'category' => 'Pizza',
                'quantity' => 8,
                'unit' => 'pizzas',
                'expiry_date' => now()->addDay(),
                'expiry_time' => '12:00',
                'pickup_location' => 'Pizza Corner Counter',
                'pickup_address' => '456 Pizza Avenue, Shah Alam',
                'special_instructions' => 'Keep warm, best served fresh',
                'dietary_info' => json_encode(['Vegetarian']),
                'status' => 'active',
                'approval_status' => 'approved',
                'created_by' => $pizzaCornerUser->id,
            ],
            [
                'food_name' => 'Garlic Bread',
                'description' => 'Freshly baked bread with garlic butter and herbs',
                'category' => 'Appetizer',
                'quantity' => 12,
                'unit' => 'pieces',
                'expiry_date' => now()->addDay(),
                'expiry_time' => '11:00',
                'pickup_location' => 'Pizza Corner Takeaway Counter',
                'pickup_address' => '456 Pizza Avenue, Shah Alam',
                'special_instructions' => 'Best when served warm',
                'dietary_info' => json_encode(['Vegetarian', 'Contains Gluten']),
                'status' => 'active',
                'approval_status' => 'approved',
                'created_by' => $pizzaCornerUser->id,
            ],
        ];

        foreach ($pizzaListings as $listing) {
            $foodListing = \App\Models\FoodListing::create(array_merge($listing, [
                'restaurant_profile_id' => $pizzaCornerProfile->id,
                'latitude' => $pizzaCornerProfile->latitude,
                'longitude' => $pizzaCornerProfile->longitude,
            ]));
        }
    }
}