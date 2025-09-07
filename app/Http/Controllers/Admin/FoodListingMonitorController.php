<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use Illuminate\Http\Request;

class FoodListingMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodListing::with(['user', 'matches']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter expired listings
        if ($request->filled('show_expired') && $request->show_expired === '1') {
            $query->where(function($q) {
                $q->where('expiry_date', '<', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->where('expiry_date', '=', now()->toDateString())
                         ->where('expiry_time', '<', now()->format('H:i'));
                  });
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('food_name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('restaurant_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $listings = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_listings' => FoodListing::count(),
            'active_listings' => FoodListing::where('status', 'active')->count(),
            'expired_listings' => FoodListing::where('status', 'expired')->count(),
            'total_matches' => FoodMatch::count(),
            'pending_matches' => FoodMatch::where('status', 'pending')->count(),
            'completed_matches' => FoodMatch::where('status', 'completed')->count(),
        ];

        return view('admin.food-listings.index', compact('listings', 'stats'));
    }

    public function show(FoodListing $listing)
    {
        $listing->load(['user', 'matches.recipient']);
        
        return view('admin.food-listings.show', compact('listing'));
    }

    public function markExpired(FoodListing $listing)
    {
        if ($listing->status === 'active') {
            $listing->update(['status' => 'expired']);
            return redirect()->back()->with('success', 'Listing marked as expired.');
        }

        return redirect()->back()->with('error', 'Listing is not active.');
    }

    public function deactivate(FoodListing $listing)
    {
        if ($listing->status === 'active') {
            $listing->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Listing has been deactivated.');
        }

        return redirect()->back()->with('error', 'Listing is not active.');
    }

    public function bulkExpireOld()
    {
        $expiredCount = FoodListing::where('status', 'active')
            ->where(function($query) {
                $query->where('expiry_date', '<', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('expiry_date', '=', now()->toDateString())
                            ->where('expiry_time', '<', now()->format('H:i'));
                      });
            })
            ->update(['status' => 'expired']);

        return redirect()->back()->with('success', "Marked {$expiredCount} listings as expired.");
    }

    public function complianceReport(Request $request)
    {
        // Get listings that might have compliance issues
        $suspiciousListings = FoodListing::with('user')
            ->where('status', 'active')
            ->where(function($query) {
                // Check for listings with very short expiry times (potential expired food)
                $query->where('expiry_date', '<=', now()->addDay())
                      // Or listings without proper location data
                      ->orWhereNull('latitude')
                      ->orWhereNull('longitude')
                      // Or listings without images
                      ->orWhere('images', 'null')
                      ->orWhere('images', '[]');
            })
            ->get();

        return view('admin.food-listings.compliance', compact('suspiciousListings'));
    }
}
