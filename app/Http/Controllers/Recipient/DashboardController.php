<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Services\FoodMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $matchingService;

    public function __construct(FoodMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get nearby food listings
        $nearbyListings = $this->matchingService->getMatchesForRecipient($user, 10)
            ->take(6);
        
        // Get user's matches
        $myMatches = FoodMatch::with(['foodListing.user'])
            ->where('recipient_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Get statistics
        $stats = [
            'total_matches' => FoodMatch::where('recipient_id', $user->id)->count(),
            'pending_matches' => FoodMatch::where('recipient_id', $user->id)->where('status', 'pending')->count(),
            'approved_matches' => FoodMatch::where('recipient_id', $user->id)->where('status', 'approved')->count(),
            'completed_pickups' => FoodMatch::where('recipient_id', $user->id)->where('status', 'completed')->count(),
            'nearby_listings' => $nearbyListings->count(),
        ];

        return view('recipient.dashboard', compact('nearbyListings', 'myMatches', 'stats'));
    }
}
