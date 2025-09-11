<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\ActivityLog;
use App\Services\FoodMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        // Get basic statistics
        $stats = [
            'total_matches' => FoodMatch::where('recipient_id', $user->id)->count(),
            'pending_matches' => FoodMatch::where('recipient_id', $user->id)->where('matches.status', 'pending')->count(),
            'approved_matches' => FoodMatch::where('recipient_id', $user->id)->where('matches.status', 'approved')->count(),
            'completed_pickups' => FoodMatch::where('recipient_id', $user->id)->where('matches.status', 'completed')->count(),
            'nearby_listings' => $nearbyListings->count(),
        ];

        // Receipt history and analytics
        $receiptStats = [
            'total_meals_received' => ActivityLog::where('causer_id', $user->id)
                ->where('log_name', 'pickup')
                ->where('event', 'pickup_completed')
                ->get()
                ->sum(function($log) {
                    return $log->properties['estimated_meals'] ?? 1;
                }),
            'total_food_received_kg' => ActivityLog::where('causer_id', $user->id)
                ->where('log_name', 'pickup')
                ->where('event', 'pickup_completed')
                ->get()
                ->sum(function($log) {
                    return $log->properties['estimated_weight_kg'] ?? 0.5;
                }),
            'money_saved_estimate' => 0, // Will calculate below
        ];

        // Estimate money saved (average $3 per meal)
        $receiptStats['money_saved_estimate'] = $receiptStats['total_meals_received'] * 3;

        // Monthly pickup trends (last 6 months)
        $monthlyPickups = ActivityLog::where('causer_id', $user->id)
            ->where('log_name', 'pickup')
            ->where('event', 'pickup_completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as pickups'),
                DB::raw('SUM(JSON_EXTRACT(properties, "$.estimated_meals")) as meals')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Category preferences based on pickup history
        $categoryPreferences = FoodMatch::where('recipient_id', $user->id)
            ->where('matches.status', 'completed')
            ->join('food_listings', 'matches.food_listing_id', '=', 'food_listings.id')
            ->select('food_listings.category', DB::raw('count(*) as count'))
            ->groupBy('food_listings.category')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'category');

        // Recent pickup history with details
        $recentPickups = ActivityLog::where('causer_id', $user->id)
            ->where('log_name', 'pickup')
            ->where('event', 'pickup_completed')
            ->with(['subject.foodListing'])
            ->latest()
            ->take(10)
            ->get();

        return view('recipient.dashboard', compact(
            'nearbyListings', 
            'myMatches', 
            'stats', 
            'receiptStats',
            'monthlyPickups',
            'categoryPreferences',
            'recentPickups'
        ));
    }
}
