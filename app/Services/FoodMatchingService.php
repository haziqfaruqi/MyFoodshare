<?php

namespace App\Services;

use App\Models\FoodListing;
use App\Models\User;
use App\Models\FoodMatch;
use App\Notifications\NewFoodListingNotification;
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
                $recipient->notify(new NewFoodListingNotification($listing, $recipient->distance));

                $matches[] = $match;
            }
        }

        return collect($matches);
    }

    public function getMatchesForRecipient(User $recipient, int $radiusKm = self::DEFAULT_RADIUS_KM)
    {
        $baseQuery = FoodListing::where('status', 'active')
            ->where('approval_status', 'approved')
            ->where('expiry_date', '>=', now()->toDateString())
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['user', 'matches' => function ($query) use ($recipient) {
                $query->where('recipient_id', $recipient->id);
            }]);

        // If recipient has coordinates, filter by distance and calculate proximity
        if ($recipient->latitude && $recipient->longitude) {
            $listings = $baseQuery
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
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

            // Also include listings without coordinates (fallback)
            $listingsWithoutCoords = $baseQuery
                ->where(function ($query) {
                    $query->whereNull('latitude')->orWhereNull('longitude');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Merge both collections
            $allListings = $listings->concat($listingsWithoutCoords);
            return $allListings;
        } else {
            // If recipient doesn't have coordinates, show all approved listings
            return $baseQuery
                ->orderBy('created_at', 'desc')
                ->get();
        }
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