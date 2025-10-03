<?php

namespace App\Http\Controllers;

use App\Models\FoodListing;
use App\Models\PickupVerification;
use App\Models\FoodMatch;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\QrCodeScanned;
use App\Events\PickupCompleted;

class QrCodeController extends Controller
{
    /**
     * Generate QR code for a food listing
     */
    public function generateQrCode(FoodListing $listing)
    {
        if (!Auth::user()->can('update', $listing)) {
            abort(403, 'Unauthorized to generate QR code for this listing');
        }

        // Find the approved match for this listing
        $match = FoodMatch::where('food_listing_id', $listing->id)
            ->where('status', 'approved')
            ->with('recipient')
            ->first();

        if (!$match) {
            return response()->json(['error' => 'No approved match found for this listing'], 404);
        }

        // Get or create pickup verification
        $verification = $match->pickupVerification ?? PickupVerification::generateForMatch($match);

        // Generate QR code URL
        $qrCodeUrl = route('pickup.verify', ['code' => $verification->verification_code]);

        // Generate QR code image
        $qrCodeImage = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrCodeUrl);

        return response()->json([
            'qr_code_url' => $qrCodeUrl,
            'qr_code_image' => 'data:image/png;base64,' . base64_encode($qrCodeImage),
            'verification_code' => $verification->verification_code,
            'recipient' => [
                'name' => $match->recipient->name,
                'email' => $match->recipient->email,
            ],
            'food_details' => [
                'name' => $listing->food_name,
                'quantity' => $listing->quantity . ' ' . $listing->unit,
                'pickup_location' => $listing->pickup_location,
            ]
        ]);
    }

    /**
     * Display QR code verification page
     */
    public function showVerificationPage($code)
    {
        $verification = PickupVerification::where('verification_code', $code)->first();

        if (!$verification) {
            return view('pickup.invalid-code');
        }

        return view('pickup.verify', [
            'verification' => $verification,
            'foodListing' => $verification->foodListing,
            'recipient' => $verification->recipient,
            'donor' => $verification->donor,
        ]);
    }

    /**
     * Process QR code scan
     */
    public function scanQrCode(Request $request, $code)
    {
        try {
            \Log::info('QR Code scan attempt', ['code' => $code, 'ip' => $request->ip()]);

            $verification = PickupVerification::where('verification_code', $code)->first();

            if (!$verification) {
                \Log::warning('Invalid verification code', ['code' => $code]);
                return response()->json(['error' => 'Invalid verification code'], 404);
            }

            if ($verification->verification_status === 'completed') {
                return response()->json(['error' => 'This verification has already been completed'], 400);
            }

            // Mark as scanned using model method (which handles broadcasting)
            $verification->scanCode($code, [
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'accuracy' => $request->input('accuracy'),
            ]);

            \Log::info('QR Code scanned successfully', ['verification_id' => $verification->id]);

            return response()->json([
                'success' => true,
                'message' => 'QR code scanned successfully',
                'verification' => $verification,
                'food_details' => [
                    'id' => $verification->foodListing->id,
                    'name' => $verification->foodListing->food_name,
                    'quantity' => $verification->foodListing->quantity . ' ' . $verification->foodListing->unit,
                    'pickup_location' => $verification->foodListing->pickup_location,
                    'pickup_address' => $verification->foodListing->pickup_address,
                    'special_instructions' => $verification->foodListing->special_instructions,
                    'dietary_info' => $verification->foodListing->dietary_info,
                ],
                'restaurant' => [
                    'name' => $verification->donor->name,
                    'contact' => $verification->donor->email,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('QR Code scan error', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Network error. Please try again.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Complete pickup process
     */
    public function completePickup(Request $request, $code)
    {
        $verification = PickupVerification::where('verification_code', $code)->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid verification code'], 404);
        }

        if ($verification->verification_status !== 'verified') {
            return response()->json(['error' => 'QR code must be scanned first'], 400);
        }

        $request->validate([
            'quality_rating' => 'required|integer|min:1|max:5',
            'recipient_notes' => 'nullable|string|max:500',
            'quality_confirmed' => 'required|boolean',
        ]);

        // Complete the pickup using model method (which handles broadcasting)
        $verification->update([
            'quality_rating' => $request->quality_rating,
            'quality_confirmed' => $request->quality_confirmed,
            'recipient_notes' => $request->recipient_notes,
        ]);

        $verification->completePickup([
            'completion_time' => now()->toDateTimeString(),
            'location' => $verification->location_data,
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        // Update the food match status
        if ($verification->foodMatch) {
            $verification->foodMatch->completePickup();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pickup completed successfully',
            'verification' => $verification,
        ]);
    }

    /**
     * Get verification status for real-time updates
     */
    public function getVerificationStatus($code)
    {
        $verification = PickupVerification::where('verification_code', $code)
            ->with(['foodListing', 'recipient', 'donor', 'foodMatch'])
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid verification code'], 404);
        }

        return response()->json([
            'verification' => $verification,
            'status' => $verification->verification_status,
            'scanned_at' => $verification->scanned_at,
            'completed_at' => $verification->pickup_completed_at,
        ]);
    }
}