<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\Recipient;
use App\Models\PickupVerification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors' => User::where('role', 'donor')->count(),
            'total_recipients' => User::where('role', 'recipient')->count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'active_listings' => FoodListing::where('status', 'active')->count(),
            'total_matches' => FoodMatch::count(),
        ];

        $pendingUsers = User::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent activity from actual database records
        $recentActivity = $this->getRecentActivity();

        // Real monthly trends for the last 6 months
        $monthlyData = $this->getMonthlyTrends();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'monthlyData', 'pendingUsers'));
    }

    private function getMonthlyTrends()
    {
        $trends = [];
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $trends[] = [
                'month' => $month->format('M'),
                'listings' => FoodListing::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'matches' => FoodMatch::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }
        
        return $trends;
    }

    private function getRecentActivity()
    {
        $activity = [];

        // Get recent food listings
        $recentListings = FoodListing::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentListings as $listing) {
            $activity[] = [
                'user' => $listing->user->name,
                'action' => "Listed {$listing->food_name}",
                'time' => $listing->created_at->diffForHumans(),
                'timestamp' => $listing->created_at->timestamp,
                'status' => 'success'
            ];
        }

        // Get recent matches
        $recentMatches = FoodMatch::with(['foodListing.user', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentMatches as $match) {
            $activity[] = [
                'user' => $match->foodListing->user->name,
                'action' => "Match with {$match->recipient->organization_name}",
                'time' => $match->created_at->diffForHumans(),
                'timestamp' => $match->created_at->timestamp,
                'status' => 'info'
            ];
        }

        // Get recent pickup verifications
        $recentPickups = PickupVerification::with(['donor', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentPickups as $pickup) {
            $activity[] = [
                'user' => $pickup->recipient->name,
                'action' => "Completed pickup verification from {$pickup->donor->name}",
                'time' => $pickup->created_at->diffForHumans(),
                'timestamp' => $pickup->created_at->timestamp,
                'status' => 'success'
            ];
        }

        // Get recent user registrations
        $recentUsers = User::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentUsers as $user) {
            $activity[] = [
                'user' => $user->name,
                'action' => "New registration pending",
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at->timestamp,
                'status' => 'warning'
            ];
        }

        // Sort all activities by actual timestamp (newest first)
        usort($activity, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        // Remove timestamp from final array (used only for sorting)
        foreach ($activity as &$item) {
            unset($item['timestamp']);
        }

        return $activity;
    }
}