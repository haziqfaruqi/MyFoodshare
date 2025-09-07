<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
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

        $listing = Auth::user()->foodListings()->create([
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
            'dietary_info' => $request->dietary_info ?? [],
            'images' => $imagePaths,
            'status' => 'active',
        ]);
        
        // Generate QR code data after creating the listing
        $listing->generateQrCode();
        
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
            'dietary_info' => $request->dietary_info ?? [],
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
}