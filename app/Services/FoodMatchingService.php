<?php

namespace App\Services;

use App\Models\FoodListing;
use App\Models\User;
use App\Models\FoodMatch;
use App\Notifications\NewFoodMatchNotification;
use Illuminate\Support\Facades\DB;

class FoodMatchingService
{
    const DEFAULT_RADIUS_KM = 5;
    
    public function findNearbyRecipients(FoodListing $listing, int $radiusKm = self::DEFAULT_RADIUS_KM)
    {
        if (!$listing->latitude || !$listing->longitude) {
            return collect();
        }

        // Use the Haversine formula to calculate distance
        $recipients = User::where('role', 'recipient')
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('*')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance
            ", [$listing->latitude, $listing->longitude, $listing->latitude])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();

        return $recipients;
    }

    public function createMatches(FoodListing $listing, int $radiusKm = self::DEFAULT_RADIUS_KM)
    {
        $recipients = $this->findNearbyRecipients($listing, $radiusKm);
        $matches = [];

        foreach ($recipients as $recipient) {
            // Check if match already exists
            $existingMatch = FoodMatch::where('food_listing_id', $listing->id)
                ->where('recipient_id', $recipient->id)
                ->first();

            if (!$existingMatch) {
                $match = FoodMatch::create([
                    'food_listing_id' => $listing->id,
                    'recipient_id' => $recipient->id,
                    'distance' => round($recipient->distance, 2),
                    'matched_at' => now(),
                    'status' => 'pending',
                ]);

                // Send notification to recipient about new food available
                $recipient->notify(new NewFoodMatchNotification($match, false));

                $matches[] = $match;
            }
        }

        return collect($matches);
    }

    public function getMatchesForRecipient(User $recipient, int $radiusKm = self::DEFAULT_RADIUS_KM)
    {
        if (!$recipient->latitude || !$recipient->longitude) {
            return collect();
        }

        $listings = FoodListing::where('status', 'active')
            ->where('approval_status', 'approved')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['user', 'matches' => function ($query) use ($recipient) {
                $query->where('recipient_id', $recipient->id);
            }])
            ->select('food_listings.*')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance
            ", [$recipient->latitude, $recipient->longitude, $recipient->latitude])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();

        return $listings;
    }

    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function autoMatchNewListing(FoodListing $listing)
    {
        return $this->createMatches($listing);
    }
}