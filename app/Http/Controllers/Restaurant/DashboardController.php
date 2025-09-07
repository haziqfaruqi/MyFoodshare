<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
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

        $recentListings = $user->foodListings()
            ->latest()
            ->take(5)
            ->get();

        $pendingMatches = FoodMatch::whereHas('foodListing', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->with(['recipient', 'foodListing'])->get();

        return view('restaurant.dashboard', compact('stats', 'recentListings', 'pendingMatches'));
    }
}