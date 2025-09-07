<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@myfoodshare.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create Donor (Restaurant Owner) User
        User::create([
            'name' => 'Golden Spoon Restaurant',
            'email' => 'donor@goldenspoon.com',
            'password' => Hash::make('donor123'),
            'role' => 'donor',
            'restaurant_name' => 'Golden Spoon Restaurant',
            'phone' => '+60123456789',
            'address' => '123 Food Street, Kuala Lumpur, Malaysia',
            'business_license' => 'BL2024001',
            'description' => 'Premium dining restaurant serving authentic Malaysian cuisine. We specialize in traditional dishes with a modern twist.',
            'cuisine_type' => 'Malaysian',
            'restaurant_capacity' => 120,
            'status' => 'active',
        ]);

        // Create another Donor
        User::create([
            'name' => 'Pizza Corner',
            'email' => 'donor@pizzacorner.com',
            'password' => Hash::make('pizza123'),
            'role' => 'donor',
            'restaurant_name' => 'Pizza Corner',
            'phone' => '+60123456790',
            'address' => '456 Italian Street, Kuala Lumpur, Malaysia',
            'business_license' => 'BL2024002',
            'description' => 'Family-friendly pizza restaurant with fresh ingredients and traditional recipes.',
            'cuisine_type' => 'Italian',
            'restaurant_capacity' => 80,
            'status' => 'active',
        ]);

        // Create Recipient (NGO) User
        User::create([
            'name' => 'Hope Foundation',
            'email' => 'recipient@hopefoundation.org',
            'password' => Hash::make('hope123'),
            'role' => 'recipient',
            'organization_name' => 'Hope Foundation Malaysia',
            'contact_person' => 'Sarah Ahmad',
            'phone' => '+60123456791',
            'address' => '789 Charity Avenue, Kuala Lumpur, Malaysia',
            'ngo_registration' => 'NGO2024001',
            'description' => 'Non-profit organization dedicated to feeding the underprivileged community.',
            'dietary_requirements' => ['Halal', 'Vegetarian'],
            'recipient_capacity' => 200,
            'needs_preferences' => 'We mainly serve families and elderly in our community center. Prefer ready-to-eat meals and fresh produce.',
            'status' => 'active',
        ]);

        // Create Pending Donor
        User::create([
            'name' => 'New Restaurant',
            'email' => 'pending@newrestaurant.com',
            'password' => Hash::make('pending123'),
            'role' => 'donor',
            'restaurant_name' => 'New Restaurant',
            'phone' => '+60123456792',
            'address' => '321 New Street, Kuala Lumpur, Malaysia',
            'business_license' => 'BL2024003',
            'description' => 'New restaurant awaiting approval.',
            'cuisine_type' => 'Western',
            'restaurant_capacity' => 60,
            'status' => 'pending',
        ]);
    }
}