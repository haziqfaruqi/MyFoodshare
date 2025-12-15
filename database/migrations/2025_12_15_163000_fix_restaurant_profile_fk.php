<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if restaurant_profile_id foreign key exists and is properly set
        if (Schema::hasTable('food_listings')) {
            // First, drop any existing foreign key constraint with the same name
            try {
                Schema::table('food_listings', function (Blueprint $table) {
                    $table->dropForeign(['restaurant_profile_id']);
                });
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }

            // Recreate the foreign key constraint
            Schema::table('food_listings', function (Blueprint $table) {
                $table->foreignId('restaurant_profile_id')
                    ->nullable()
                    ->constrained('restaurant_profiles')
                    ->onDelete('set null');
            });

            // Update any null restaurant_profile_id to point to valid profiles
            $this->updateRestaurantProfileIds();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('food_listings')) {
            Schema::table('food_listings', function (Blueprint $table) {
                $table->dropForeign(['restaurant_profile_id']);
                $table->dropColumn('restaurant_profile_id');
            });
        }
    }

    private function updateRestaurantProfileIds()
    {
        // Get all food listings without restaurant_profile_id
        $listings = \DB::table('food_listings')->whereNull('restaurant_profile_id')->get();

        foreach ($listings as $listing) {
            if ($listing->created_by) {
                // Find restaurant profile for this user
                $profile = \DB::table('restaurant_profiles')
                    ->where('user_id', $listing->created_by)
                    ->first();

                if ($profile) {
                    \DB::table('food_listings')
                        ->where('id', $listing->id)
                        ->update(['restaurant_profile_id' => $profile->id]);
                }
            }
        }
    }
};