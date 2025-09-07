<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Notifications\NewFoodMatchNotification;
use App\Services\FoodMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodBrowsingController extends Controller
{
    protected $matchingService;

    public function __construct(FoodMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $radius = $request->get('radius', 5);
        
        $listings = $this->matchingService->getMatchesForRecipient($user, $radius);
        
        // Filter by category if requested
        if ($request->filled('category') && $request->category !== 'all') {
            $listings = $listings->where('category', $request->category);
        }
        
        // Filter by search term
        if ($request->filled('search')) {
            $listings = $listings->filter(function ($listing) use ($request) {
                return stripos($listing->food_name, $request->search) !== false ||
                       stripos($listing->description, $request->search) !== false;
            });
        }

        $categories = FoodListing::distinct()->pluck('category')->filter();

        return view('recipient.browse.index', compact('listings', 'categories', 'radius'));
    }

    public function show(FoodListing $listing)
    {
        // Check if listing is approved and visible to recipients
        if (!$listing->isVisibleToRecipients()) {
            abort(404, 'Food listing not available.');
        }
        
        $user = Auth::user();
        
        // Calculate distance if both have coordinates
        $distance = null;
        if ($listing->latitude && $listing->longitude && $user->latitude && $user->longitude) {
            $distance = $this->matchingService->calculateDistance(
                $user->latitude, 
                $user->longitude, 
                $listing->latitude, 
                $listing->longitude
            );
        }

        // Check if user has already expressed interest
        $existingMatch = FoodMatch::where('food_listing_id', $listing->id)
            ->where('recipient_id', $user->id)
            ->first();

        return view('recipient.browse.show', compact('listing', 'distance', 'existingMatch'));
    }

    public function expressInterest(FoodListing $listing)
    {
        $user = Auth::user();
        
        // Check if already interested
        $existingMatch = FoodMatch::where('food_listing_id', $listing->id)
            ->where('recipient_id', $user->id)
            ->first();

        if ($existingMatch) {
            return redirect()->back()->with('info', 'You have already expressed interest in this listing.');
        }

        // Calculate distance
        $distance = null;
        if ($listing->latitude && $listing->longitude && $user->latitude && $user->longitude) {
            $distance = $this->matchingService->calculateDistance(
                $user->latitude, 
                $user->longitude, 
                $listing->latitude, 
                $listing->longitude
            );
        }

        // Create match
        $match = FoodMatch::create([
            'food_listing_id' => $listing->id,
            'recipient_id' => $user->id,
            'distance' => $distance ? round($distance, 2) : null,
            'matched_at' => now(),
            'status' => 'pending',
        ]);

        // Notify the donor about the new interest
        $listing->user->notify(new NewFoodMatchNotification($match, true));

        return redirect()->back()->with('success', 'Interest expressed successfully! The donor will be notified.');
    }

    public function myMatches()
    {
        $user = Auth::user();
        
        $matches = FoodMatch::with(['foodListing.user'])
            ->where('recipient_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('recipient.matches.index', compact('matches'));
    }

    public function mapView(Request $request)
    {
        $user = Auth::user();
        $radius = $request->get('radius', 5);
        
        $listings = $this->matchingService->getMatchesForRecipient($user, $radius);
        
        // Only get listings with valid coordinates
        $listings = $listings->filter(function ($listing) {
            return $listing->latitude && $listing->longitude;
        });

        return view('recipient.browse.map', compact('listings', 'radius'));
    }
}
