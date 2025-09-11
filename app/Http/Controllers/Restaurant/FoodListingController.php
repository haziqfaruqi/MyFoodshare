<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\ActivityLog;
use App\Services\FoodMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->foodListings();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('food_name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $listings = $query->latest()->paginate(12);

        return view('restaurant.listings.index', compact('listings'));
    }

    public function create()
    {
        return view('restaurant.listings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'expiry_date' => 'required|date|after_or_equal:today',
            'expiry_time' => 'nullable|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'pickup_address' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string',
            'dietary_info' => 'nullable|array',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('food-listings', 'public');
                $imagePaths[] = $path;
            }
        }

        // Use coordinates from request, or fall back to user's coordinates
        $latitude = $request->latitude ?? Auth::user()->latitude;
        $longitude = $request->longitude ?? Auth::user()->longitude;
        
        // If still no coordinates, try to use a default based on pickup address
        if (!$latitude || !$longitude) {
            // Log that coordinates are missing so we can fix this
            \Log::info('Food listing created without coordinates', [
                'user_id' => Auth::id(),
                'user_has_coords' => !is_null(Auth::user()->latitude) && !is_null(Auth::user()->longitude),
                'request_has_coords' => !is_null($request->latitude) && !is_null($request->longitude),
                'pickup_location' => $request->pickup_location,
                'pickup_address' => $request->pickup_address
            ]);
        }

        $listing = Auth::user()->foodListings()->create([
            'food_name' => $request->food_name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiry_date' => $request->expiry_date,
            'expiry_time' => $request->expiry_time,
            'pickup_location' => $request->pickup_location,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'pickup_address' => $request->pickup_address,
            'special_instructions' => $request->special_instructions,
            'dietary_info' => is_array($request->dietary_info) ? $request->dietary_info : [],
            'images' => $imagePaths,
            'status' => 'active',
        ]);
        
        // Generate QR code data after creating the listing
        $listing->generateQrCode();
        
        // Log the donation activity
        try {
            ActivityLog::logFoodDonation('created', $listing, Auth::user(), [
                'estimated_weight_kg' => (float) $this->estimateWeight($listing),
                'estimated_meals' => (int) $this->estimateMeals($listing),
                'category' => (string) $listing->category,
                'quantity' => (int) $listing->quantity,
                'unit' => (string) $listing->unit,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log food donation activity', [
                'error' => $e->getMessage(),
                'listing_id' => $listing->id,
                'user_id' => Auth::id()
            ]);
        }
        
        // Auto-match with nearby recipients
        $matchingService = new FoodMatchingService();
        $matches = $matchingService->autoMatchNewListing($listing);
        
        $message = 'Food listing created successfully!';
        if ($matches->count() > 0) {
            $message .= " Found {$matches->count()} nearby recipients.";
        }

        return redirect()->route('restaurant.listings.index')
            ->with('success', $message);
    }

    public function show(FoodListing $listing)
    {
        $this->authorize('view', $listing);
        
        $matches = $listing->matches()->with('recipient')->get();
        
        return view('restaurant.listings.show', compact('listing', 'matches'));
    }

    public function edit(FoodListing $listing)
    {
        $this->authorize('update', $listing);
        
        return view('restaurant.listings.edit', compact('listing'));
    }

    public function update(Request $request, FoodListing $listing)
    {
        $this->authorize('update', $listing);

        $request->validate([
            'food_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'expiry_date' => 'required|date|after_or_equal:today',
            'expiry_time' => 'nullable|date_format:H:i',
            'pickup_location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'pickup_address' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string',
            'dietary_info' => 'nullable|array',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $updateData = [
            'food_name' => $request->food_name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'expiry_date' => $request->expiry_date,
            'expiry_time' => $request->expiry_time,
            'pickup_location' => $request->pickup_location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pickup_address' => $request->pickup_address,
            'special_instructions' => $request->special_instructions,
            'dietary_info' => is_array($request->dietary_info) ? $request->dietary_info : [],
        ];

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('food-listings', 'public');
                $imagePaths[] = $path;
            }
            $updateData['images'] = $imagePaths;
        }

        $listing->update($updateData);
        
        // Regenerate QR code data after updating
        $listing->generateQrCode();

        return redirect()->route('restaurant.listings.index')
            ->with('success', 'Food listing updated successfully!');
    }

    public function destroy(FoodListing $listing)
    {
        $this->authorize('delete', $listing);
        
        $listing->delete();
        
        return redirect()->route('restaurant.listings.index')
            ->with('success', 'Food listing deleted successfully!');
    }

    public function approveMatch(Request $request, FoodListing $listing, FoodMatch $match)
    {
        $this->authorize('view', $listing);
        
        // Ensure the match belongs to this listing
        if ($match->food_listing_id !== $listing->id) {
            abort(404, 'Match not found for this listing.');
        }

        // Confirm pickup - this will create the PickupVerification and update status
        $match->confirmPickup();

        return redirect()->back()->with('success', 'Pickup approved! The recipient has been notified and a QR verification code has been generated.');
    }

    public function scheduleMatch(Request $request, FoodListing $listing, FoodMatch $match)
    {
        $this->authorize('view', $listing);
        
        // Ensure the match belongs to this listing
        if ($match->food_listing_id !== $listing->id) {
            abort(404, 'Match not found for this listing.');
        }

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $match->schedulePickup(new \DateTime($request->scheduled_at));

        return redirect()->back()->with('success', 'Pickup scheduled successfully!');
    }

    public function rejectMatch(Request $request, FoodListing $listing, FoodMatch $match)
    {
        $this->authorize('view', $listing);
        
        // Ensure the match belongs to this listing
        if ($match->food_listing_id !== $listing->id) {
            abort(404, 'Match not found for this listing.');
        }

        $match->update([
            'status' => 'rejected',
            'notes' => $request->get('reason', 'Rejected by donor'),
        ]);

        return redirect()->back()->with('info', 'Match rejected successfully.');
    }

    private function estimateWeight(FoodListing $listing)
    {
        // Simple weight estimation based on category and quantity
        $categoryWeights = [
            'vegetables' => 0.3, // kg per unit
            'fruits' => 0.2,
            'dairy' => 0.25,
            'meat' => 0.4,
            'bakery' => 0.15,
            'pantry' => 0.5,
            'prepared_food' => 0.3,
        ];

        $baseWeight = $categoryWeights[$listing->category] ?? 0.3;
        return $listing->quantity * $baseWeight;
    }

    private function estimateMeals(FoodListing $listing)
    {
        // Estimate meals based on quantity and category
        $mealMultipliers = [
            'vegetables' => 0.5, // meals per unit
            'fruits' => 0.3,
            'dairy' => 0.2,
            'meat' => 1.5,
            'bakery' => 0.8,
            'pantry' => 2.0,
            'prepared_food' => 1.0,
        ];

        $baseMeals = $mealMultipliers[$listing->category] ?? 0.5;
        return max(1, round($listing->quantity * $baseMeals));
    }

    public function manageMatches(Request $request)
    {
        $user = Auth::user();
        
        // Get all matches for this restaurant's listings
        $query = FoodMatch::whereHas('foodListing', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['foodListing', 'recipient', 'pickupVerification']);

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by search (recipient name or food name)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('recipient', function($recipientQuery) use ($request) {
                    $recipientQuery->where('name', 'like', '%' . $request->search . '%')
                                  ->orWhere('organization_name', 'like', '%' . $request->search . '%');
                })->orWhereHas('foodListing', function($listingQuery) use ($request) {
                    $listingQuery->where('food_name', 'like', '%' . $request->search . '%');
                });
            });
        }

        $matches = $query->latest()->paginate(15);

        // Get status counts for tabs
        $statusCounts = [
            'all' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'pending' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'approved' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'approved')->count(),
            'scheduled' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'scheduled')->count(),
            'completed' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
            'rejected' => FoodMatch::whereHas('foodListing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'rejected')->count(),
        ];

        return view('restaurant.matches.index', compact('matches', 'statusCounts', 'status'));
    }
}