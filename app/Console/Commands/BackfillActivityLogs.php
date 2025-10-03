<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\ActivityLog;

class BackfillActivityLogs extends Command
{
    protected $signature = 'activity:backfill';
    protected $description = 'Backfill missing activity logs for existing food listings and matches';

    public function handle()
    {
        $this->info('Starting activity log backfill...');

        // Backfill food listing creation logs
        $this->info('Backfilling food listing logs...');
        $listings = FoodListing::all();
        $listingsAdded = 0;

        foreach ($listings as $listing) {
            // Check if log already exists
            $exists = ActivityLog::where('subject_type', FoodListing::class)
                ->where('subject_id', $listing->id)
                ->where('log_name', 'donation')
                ->exists();

            if (!$exists && $listing->user) {
                // Estimate meals and weight
                $estimatedMeals = $this->estimateMeals($listing);
                $estimatedWeight = $this->estimateWeight($listing);

                ActivityLog::logFoodDonation('created', $listing, $listing->user, [
                    'estimated_weight_kg' => $estimatedWeight,
                    'estimated_meals' => $estimatedMeals,
                    'category' => $listing->category,
                    'quantity' => $listing->quantity,
                    'unit' => $listing->unit,
                ]);

                $listingsAdded++;
            }
        }

        $this->info("Added {$listingsAdded} food listing logs");

        // Backfill completed pickup logs
        $this->info('Backfilling completed pickup logs...');
        $completedMatches = FoodMatch::where('status', 'completed')
            ->with(['foodListing', 'recipient'])
            ->get();
        $pickupsAdded = 0;

        foreach ($completedMatches as $match) {
            // Check if log already exists
            $exists = ActivityLog::where('subject_type', FoodMatch::class)
                ->where('subject_id', $match->id)
                ->where('log_name', 'pickup')
                ->where('description', 'like', '%completed pickup%')
                ->exists();

            if (!$exists && $match->recipient && $match->foodListing) {
                ActivityLog::logPickupActivity('pickup_completed', $match, $match->recipient, [
                    'completed_at' => $match->completed_at ?? $match->updated_at,
                ]);

                $pickupsAdded++;
            }
        }

        $this->info("Added {$pickupsAdded} completed pickup logs");
        $this->info('Backfill completed successfully!');

        return 0;
    }

    private function estimateWeight(FoodListing $listing)
    {
        $categoryWeights = [
            'Vegetables' => 0.3,
            'Fruits' => 0.2,
            'Dairy & Eggs' => 0.25,
            'Meat & Poultry' => 0.4,
            'Seafood' => 0.35,
            'Bread & Bakery' => 0.15,
            'Pantry Items' => 0.5,
            'Prepared Meals' => 0.3,
            'Beverages' => 0.4,
            'Desserts' => 0.2,
        ];

        $baseWeight = $categoryWeights[$listing->category] ?? 0.3;
        return round($listing->quantity * $baseWeight, 2);
    }

    private function estimateMeals(FoodListing $listing)
    {
        $mealMultipliers = [
            'Vegetables' => 0.5,
            'Fruits' => 0.3,
            'Dairy & Eggs' => 0.2,
            'Meat & Poultry' => 1.5,
            'Seafood' => 1.2,
            'Bread & Bakery' => 0.8,
            'Pantry Items' => 2.0,
            'Prepared Meals' => 1.0,
            'Beverages' => 0.1,
            'Desserts' => 0.5,
        ];

        $baseMeals = $mealMultipliers[$listing->category] ?? 0.5;
        return max(1, round($listing->quantity * $baseMeals));
    }
}
