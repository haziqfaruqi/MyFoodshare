<?php

namespace App\Http\Controllers;

use App\Models\FoodListing;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrVerificationController extends Controller
{
    public function verify(Request $request, $id, $code)
    {
        $listing = FoodListing::with('user')->findOrFail($id);
        
        if ($listing->getVerificationCode() !== $code) {
            abort(403, 'Invalid verification code');
        }
        
        $qrData = $listing->generateQrCode();
        
        return view('qr-verification.show', compact('listing', 'qrData'));
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
