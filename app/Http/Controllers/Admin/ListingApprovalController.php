<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Services\FoodMatchingService;
use Illuminate\Http\Request;

class ListingApprovalController extends Controller
{
    protected $matchingService;

    public function __construct(FoodMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index()
    {
        $pendingListings = FoodListing::with(['donor'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_pending' => FoodListing::where('approval_status', 'pending')->count(),
            'approved_today' => FoodListing::where('approval_status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'rejected_today' => FoodListing::where('approval_status', 'rejected')
                ->whereDate('updated_at', today())->count(),
            'avg_approval_time' => '1.2 hours', // Placeholder
        ];

        return view('admin.listing-approvals.index', compact('pendingListings', 'stats'));
    }

    public function show(FoodListing $listing)
    {
        $listing->load(['donor', 'matches']);
        
        return view('admin.listing-approvals.show', compact('listing'));
    }

    public function approve(Request $request, FoodListing $listing)
    {
        if ($listing->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This listing has already been processed.');
        }

        $listing->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'admin_notes' => $request->input('notes'),
        ]);

        // Auto-create matches for approved listings
        if ($listing->isActive() && $listing->latitude && $listing->longitude) {
            $this->matchingService->autoMatchNewListing($listing);
        }

        return redirect()->route('admin.listing-approvals.index')
            ->with('success', "Food listing '{$listing->food_name}' has been approved and is now visible to recipients.");
    }

    public function reject(Request $request, FoodListing $listing)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($listing->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This listing has already been processed.');
        }

        $listing->update([
            'approval_status' => 'rejected',
            'admin_notes' => $request->input('reason'),
        ]);

        return redirect()->route('admin.listing-approvals.index')
            ->with('success', "Food listing '{$listing->food_name}' has been rejected.");
    }

    public function bulkApprove(Request $request)
    {
        $listingIds = $request->input('listing_ids', []);
        
        if (empty($listingIds)) {
            return redirect()->back()->with('error', 'No listings selected.');
        }

        $count = FoodListing::whereIn('id', $listingIds)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'admin_notes' => 'Bulk approved',
            ]);

        // Auto-match approved listings
        FoodListing::whereIn('id', $listingIds)
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->each(function ($listing) {
                $this->matchingService->autoMatchNewListing($listing);
            });

        return redirect()->back()->with('success', "{$count} listings approved successfully.");
    }
}