<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use Illuminate\Http\Request;

class ActiveListingController extends Controller
{
    public function index()
    {
        $activeListings = FoodListing::with(['donor'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_active' => FoodListing::where('status', 'active')->count(),
            'total_matches' => FoodMatch::count(),
            'expiring_today' => FoodListing::where('status', 'active')
                ->where('expiry_date', '<=', now()->addDay())
                ->count(),
            'avg_pickup_time' => '4.2 hours', // Placeholder
        ];

        return view('admin.active-listings.index', compact('activeListings', 'stats'));
    }

    public function show(FoodListing $listing)
    {
        $listing->load(['donor', 'matches.recipient']);
        
        return view('admin.active-listings.show', compact('listing'));
    }

    public function deactivate(Request $request, FoodListing $listing)
    {
        $listing->update([
            'status' => 'inactive',
            'admin_notes' => $request->input('reason'),
        ]);

        return redirect()->back()
            ->with('success', 'Listing has been deactivated successfully.');
    }

    public function markExpired(Request $request, FoodListing $listing)
    {
        $listing->update([
            'status' => 'expired',
            'admin_notes' => $request->input('reason', 'Marked expired by admin'),
        ]);

        return redirect()->back()
            ->with('success', 'Listing has been marked as expired.');
    }
}