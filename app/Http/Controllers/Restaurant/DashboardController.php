<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Basic stats
        $stats = [
            'total_listings' => $user->foodListings()->count(),
            'active_listings' => $user->foodListings()->where('status', 'active')->count(),
            'total_matches' => FoodMatch::whereHas('foodListing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'completed_donations' => FoodMatch::whereHas('foodListing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];

        // Impact metrics from activity logs
        $impactStats = ActivityLog::getImpactStats($user->id);
        
        // Monthly donation trends (last 6 months)
        $monthlyTrends = ActivityLog::where('causer_id', $user->id)
            ->where('log_name', 'donation')
            ->where('event', 'created')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(JSON_EXTRACT(properties, "$.estimated_meals")) as estimated_meals'),
                DB::raw('SUM(JSON_EXTRACT(properties, "$.estimated_weight_kg")) as estimated_weight')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Recent activity timeline
        $recentActivities = ActivityLog::where('causer_id', $user->id)
            ->whereIn('log_name', ['donation', 'pickup'])
            ->with(['subject'])
            ->latest()
            ->take(10)
            ->get();

        $recentListings = $user->foodListings()
            ->latest()
            ->take(5)
            ->get();

        $pendingMatches = FoodMatch::whereHas('foodListing', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->with(['recipient', 'foodListing'])->get();

        // Category breakdown
        $categoryStats = $user->foodListings()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        return view('restaurant.dashboard', compact(
            'stats', 
            'impactStats', 
            'monthlyTrends', 
            'recentActivities', 
            'recentListings', 
            'pendingMatches', 
            'categoryStats'
        ));
    }

    public function reports()
    {
        $user = Auth::user();
        
        // Extended impact statistics
        $impactStats = ActivityLog::getImpactStats($user->id);
        
        // Monthly trends for the last 12 months
        $monthlyTrends = ActivityLog::where('causer_id', $user->id)
            ->where('log_name', 'donation')
            ->where('event', 'created')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(JSON_EXTRACT(properties, "$.estimated_meals")) as estimated_meals'),
                DB::raw('SUM(JSON_EXTRACT(properties, "$.estimated_weight_kg")) as estimated_weight')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Detailed statistics
        $detailedStats = [
            'total_listings' => $user->foodListings()->count(),
            'active_listings' => $user->foodListings()->where('status', 'active')->count(),
            'completed_donations' => FoodMatch::whereHas('foodListing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
            'total_matches' => FoodMatch::whereHas('foodListing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
        ];

        // Category breakdown
        $categoryStats = $user->foodListings()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        return view('restaurant.reports', compact(
            'impactStats',
            'monthlyTrends', 
            'detailedStats',
            'categoryStats'
        ));
    }

    public function trackDonations()
    {
        $user = Auth::user();
        
        // Get all food listings with their matches and progress
        $donations = $user->foodListings()
            ->with(['matches.recipient', 'matches.pickupVerification'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group donations by status and match completion
        $progressData = [
            'active' => collect(),
            'completed' => collect(),
            'expired' => $donations->where('status', 'expired'),
        ];
        
        // Categorize donations based on their match status
        foreach ($donations->whereNotIn('status', ['expired']) as $donation) {
            $hasCompletedMatches = $donation->matches->where('status', 'completed')->count() > 0;
            
            if ($donation->status === 'completed' || $hasCompletedMatches) {
                $progressData['completed']->push($donation);
            } else {
                $progressData['active']->push($donation);
            }
        }

        // Calculate overall progress statistics
        $progressStats = [
            'total_donations' => $donations->count(),
            'active_donations' => $progressData['active']->count(),
            'completed_donations' => $progressData['completed']->count(),
            'expired_donations' => $progressData['expired']->count(),
            'completion_rate' => $donations->count() > 0 ? round(($progressData['completed']->count() / $donations->count()) * 100, 1) : 0,
        ];

        return view('restaurant.track-donations', compact(
            'donations',
            'progressData',
            'progressStats'
        ));
    }
}