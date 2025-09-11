<?php

namespace App\Http\Controllers;

use App\Models\FoodListing;
use App\Models\FoodMatch;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrVerificationController extends Controller
{
    public function verify(Request $request, $id, $code)
    {
        $listing = FoodListing::with(['user', 'matches.recipient'])->findOrFail($id);
        
        if ($listing->getVerificationCode() !== $code) {
            abort(403, 'Invalid verification code');
        }
        
        // Get current user's match for this listing (if any)
        $userMatch = null;
        if (auth()->check()) {
            $userMatch = $listing->matches()
                ->where('recipient_id', auth()->id())
                ->whereIn('status', ['approved', 'scheduled'])
                ->first();
        }
        
        $qrData = $listing->generateQrCode();
        
        return view('qr-verification.show', compact('listing', 'qrData', 'userMatch'));
    }
    
    public function completePickup(Request $request, $id, $code)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to complete pickup');
        }
        
        $listing = FoodListing::with('matches')->findOrFail($id);
        
        if ($listing->getVerificationCode() !== $code) {
            abort(403, 'Invalid verification code');
        }
        
        // Find the user's approved match for this listing
        $match = $listing->matches()
            ->where('recipient_id', auth()->id())
            ->whereIn('status', ['approved', 'scheduled'])
            ->first();
            
        if (!$match) {
            return back()->with('error', 'You do not have an approved pickup for this item or pickup has already been completed.');
        }
        
        // Complete the pickup
        $match->completePickup();
        
        // Log the activity
        activity('pickup')
            ->causedBy(auth()->user())
            ->performedOn($listing)
            ->withProperties([
                'match_id' => $match->id,
                'estimated_meals' => $listing->estimated_meals ?? 1,
                'estimated_weight_kg' => $listing->estimated_weight_kg ?? 0.5,
                'pickup_location' => $listing->pickup_location,
            ])
            ->log('pickup_completed');
        
        return redirect()->route('recipient.matches.index')
            ->with('success', 'Pickup completed successfully! Thank you for reducing food waste.');
    }
    
    public function generateQr(FoodListing $listing)
    {
        $url = $listing->getQrCodeUrl();
        
        return QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->generate($url);
    }
}
