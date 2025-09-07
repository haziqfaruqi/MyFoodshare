<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Monthly trends for the last 12 months
        $monthlyTrends = $this->getMonthlyTrends();
        
        // Summary statistics
        $stats = [
            'total_users' => User::count(),
            'total_listings' => FoodListing::count(),
            'total_matches' => FoodMatch::count(),
            'success_rate' => $this->calculateSuccessRate(),
        ];

        // Recent activity data
        $recentActivity = $this->getRecentActivity();
        
        // Real geographic distribution data
        $geographicData = $this->getGeographicDistribution();

        return view('admin.analytics.index', compact(
            'monthlyTrends', 
            'stats', 
            'recentActivity',
            'geographicData'
        ));
    }

    private function getMonthlyTrends()
    {
        $trends = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $trends[] = [
                'month' => $month->format('M Y'),
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

    private function calculateSuccessRate()
    {
        $totalListings = FoodListing::count();
        $matchedListings = FoodListing::whereHas('matches')->count();
        
        return $totalListings > 0 ? round(($matchedListings / $totalListings) * 100, 1) : 0;
    }

    private function getRecentActivity()
    {
        $recentListings = FoodListing::with('donor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($listing) {
                return [
                    'user' => $listing->donor->restaurant_name ?? $listing->donor->name,
                    'action' => "Listed {$listing->quantity} {$listing->food_name}",
                    'time' => $listing->created_at->diffForHumans(),
                    'status' => 'success'
                ];
            });

        $recentMatches = FoodMatch::with(['listing.donor', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($match) {
                return [
                    'user' => $match->recipient->name,
                    'action' => "Matched with {$match->listing->food_name}",
                    'time' => $match->created_at->diffForHumans(),
                    'status' => 'info'
                ];
            });

        return $recentListings->concat($recentMatches)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    private function getGeographicDistribution()
    {
        // Get users with coordinates
        $usersWithCoords = User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'active')
            ->get();

        // Group users by regions based on GPS coordinates
        $regions = [];
        
        foreach ($usersWithCoords as $user) {
            $regionName = $this->getRegionFromCoordinates($user->latitude, $user->longitude);
            
            if (!isset($regions[$regionName])) {
                $regions[$regionName] = [
                    'region' => $regionName,
                    'donors' => 0,
                    'recipients' => 0,
                    'listings' => 0
                ];
            }
            
            if ($user->role === 'donor') {
                $regions[$regionName]['donors']++;
                // Count listings for this donor in this region
                $regions[$regionName]['listings'] += FoodListing::where('user_id', $user->id)->count();
            } elseif ($user->role === 'recipient') {
                $regions[$regionName]['recipients']++;
            }
        }

        // Get users without coordinates and group by address
        $usersWithoutCoords = User::whereNull('latitude')
            ->whereNotNull('address')
            ->where('status', 'active')
            ->get();

        foreach ($usersWithoutCoords as $user) {
            $regionName = $this->getRegionFromAddress($user->address);
            
            if (!isset($regions[$regionName])) {
                $regions[$regionName] = [
                    'region' => $regionName,
                    'donors' => 0,
                    'recipients' => 0,
                    'listings' => 0
                ];
            }
            
            if ($user->role === 'donor') {
                $regions[$regionName]['donors']++;
                $regions[$regionName]['listings'] += FoodListing::where('user_id', $user->id)->count();
            } elseif ($user->role === 'recipient') {
                $regions[$regionName]['recipients']++;
            }
        }

        // Sort by total activity (donors + recipients + listings) and return top regions
        return collect($regions)
            ->sortByDesc(function ($region) {
                return $region['donors'] + $region['recipients'] + $region['listings'];
            })
            ->take(6)
            ->values()
            ->toArray();
    }

    private function getRegionFromCoordinates($latitude, $longitude)
    {
        // Simple region mapping based on coordinate ranges
        // You can customize these ranges based on your city's geography
        
        // Convert to approximate grid system for regional grouping
        $latGrid = floor($latitude * 100) / 100; // Group by ~1km squares
        $lngGrid = floor($longitude * 100) / 100;
        
        // Example coordinate-based region naming
        // You can customize this logic based on your actual city regions
        if ($latitude >= 3.200 && $latitude <= 3.250) {
            if ($longitude >= 101.650 && $longitude <= 101.700) {
                return 'City Center';
            } elseif ($longitude >= 101.700 && $longitude <= 101.750) {
                return 'KLCC Area';
            } else {
                return 'Central Zone';
            }
        } elseif ($latitude >= 3.100 && $latitude <= 3.200) {
            if ($longitude >= 101.650 && $longitude <= 101.700) {
                return 'Mid Valley';
            } elseif ($longitude >= 101.700 && $longitude <= 101.750) {
                return 'Bangsar';
            } else {
                return 'Southern Zone';
            }
        } elseif ($latitude >= 3.000 && $latitude <= 3.100) {
            return 'Subang Area';
        } elseif ($latitude >= 2.900 && $latitude <= 3.000) {
            return 'Petaling Jaya';
        } else {
            // Fallback: create region name from coordinate grid
            return "Region " . abs(round($latGrid * 10)) . "-" . abs(round($lngGrid * 10));
        }
    }

    private function getRegionFromAddress($address)
    {
        // Extract region from address string
        $address = strtolower($address);
        
        // Define common area keywords
        $regions = [
            'kuala lumpur' => 'Kuala Lumpur',
            'kl' => 'Kuala Lumpur',
            'klcc' => 'KLCC Area',
            'bukit bintang' => 'Bukit Bintang',
            'bangsar' => 'Bangsar',
            'mid valley' => 'Mid Valley',
            'petaling jaya' => 'Petaling Jaya',
            'pj' => 'Petaling Jaya',
            'subang' => 'Subang Area',
            'shah alam' => 'Shah Alam',
            'damansara' => 'Damansara',
            'mont kiara' => 'Mont Kiara',
            'cheras' => 'Cheras',
            'ampang' => 'Ampang',
            'setapak' => 'Setapak',
            'wangsa maju' => 'Wangsa Maju',
        ];
        
        foreach ($regions as $keyword => $region) {
            if (strpos($address, $keyword) !== false) {
                return $region;
            }
        }
        
        // If no match found, try to extract the first significant word
        $words = explode(',', $address);
        $firstArea = trim($words[0]);
        
        return ucwords($firstArea) ?: 'Other Areas';
    }
}