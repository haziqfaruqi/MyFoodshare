<?php

namespace App\Http\Controllers;

use App\Models\PickupVerification;
use App\Models\FoodMatch;
use App\Notifications\PickupVerifiedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PickupVerificationController extends Controller
{
    public function showScanner()
    {
        return view('pickup.scanner');
    }

    public function verify($code)
    {
        $verification = PickupVerification::where('verification_code', $code)
            ->with(['foodMatch', 'foodListing', 'recipient', 'donor'])
            ->first();

        if (!$verification) {
            return view('pickup.invalid-code', compact('code'));
        }

        // Check if verification is still valid
        if ($verification->verification_status === 'completed') {
            return view('pickup.already-completed', compact('verification'));
        }

        return view('pickup.verify', compact('verification'));
    }

    public function scan(Request $request, $code)
    {
        $verification = PickupVerification::where('verification_code', $code)->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid verification code'], 404);
        }

        // Ensure only the recipient can scan
        if (Auth::id() !== $verification->recipient_id) {
            return response()->json(['error' => 'Unauthorized to scan this code'], 403);
        }

        $locationData = null;
        if ($request->has(['latitude', 'longitude'])) {
            $locationData = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy' => $request->accuracy,
                'timestamp' => now(),
            ];
        }

        $success = $verification->scanCode($code, $locationData);

        if ($success) {
            // Notify donor that pickup was verified
            $verification->donor->notify(new PickupVerifiedNotification($verification));
            
            return response()->json([
                'success' => true,
                'message' => 'Pickup verified successfully!',
                'verification_id' => $verification->id,
            ]);
        }

        return response()->json(['error' => 'Invalid verification code'], 400);
    }

    public function completePickup(Request $request, PickupVerification $verification)
    {
        // Ensure only the recipient can complete pickup
        if (Auth::id() !== $verification->recipient_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity_received' => 'required|string',
            'condition' => 'required|string',
            'quality_rating' => 'required|integer|min:1|max:5',
            'recipient_notes' => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120', // 5MB max per photo
            'has_issues' => 'boolean',
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('pickup-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $details = [
            'quantity_received' => $request->quantity_received,
            'condition' => $request->condition,
            'completed_by' => Auth::user()->name,
            'completed_at' => now()->toISOString(),
        ];

        $verification->completePickup($details, $photoPaths);

        if ($request->quality_rating >= 4 && !$request->has_issues) {
            $verification->confirmQuality($request->quality_rating, $request->recipient_notes);
        } else {
            $verification->reportQualityIssue(
                $request->quality_rating,
                $request->quality_issues ?? 'Quality concerns reported',
                $request->recipient_notes
            );
        }

        // Update the food match status
        $verification->foodMatch->completePickup();

        return redirect()->route('recipient.matches.index')
            ->with('success', 'Pickup completed successfully! Thank you for reducing food waste.');
    }

    public function getVerificationDetails(PickupVerification $verification)
    {
        // Only allow recipient or donor to view details
        if (!in_array(Auth::id(), [$verification->recipient_id, $verification->donor_id])) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'verification' => $verification->load(['foodMatch', 'foodListing', 'recipient', 'donor']),
        ]);
    }

    public function reportIssue(Request $request, PickupVerification $verification)
    {
        // Allow both recipient and donor to report issues
        if (!in_array(Auth::id(), [$verification->recipient_id, $verification->donor_id])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'issue_type' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('issue-reports', 'public');
                $photoPaths[] = $path;
            }
        }

        $currentPhotos = $verification->photo_evidence ?? [];
        $updatedPhotos = array_merge($currentPhotos, $photoPaths);

        $issueData = [
            'type' => $request->issue_type,
            'description' => $request->description,
            'severity' => $request->severity,
            'reported_by' => Auth::id(),
            'reported_at' => now(),
            'photos' => $photoPaths,
        ];

        // Add to existing issues or create new array
        $existingIssues = is_array($verification->quality_issues) ? $verification->quality_issues : [];
        $existingIssues[] = $issueData;

        $verification->update([
            'verification_status' => 'disputed',
            'quality_issues' => $issueData['description'],
            'photo_evidence' => $updatedPhotos,
            Auth::id() === $verification->recipient_id ? 'recipient_notes' : 'donor_notes' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Issue reported successfully. An admin will review it shortly.');
    }

    public function adminIndex(Request $request)
    {
        // Only admins can access this
        $this->middleware('admin');
        
        $query = PickupVerification::with(['foodListing', 'recipient', 'donor']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by verification code
        if ($request->filled('search')) {
            $query->where('verification_code', 'like', '%' . $request->search . '%');
        }
        
        $verifications = $query->latest()->paginate(20);
        
        $stats = [
            'total' => PickupVerification::count(),
            'pending' => PickupVerification::where('verification_status', 'pending')->count(),
            'verified' => PickupVerification::where('verification_status', 'verified')->count(),
            'completed' => PickupVerification::where('verification_status', 'completed')->count(),
            'disputed' => PickupVerification::where('verification_status', 'disputed')->count(),
        ];
        
        return view('admin.pickup-verifications.index', compact('verifications', 'stats'));
    }

    public function adminReview(PickupVerification $verification)
    {
        // Only admins can access this
        $this->middleware('admin');
        
        $verification->load(['foodListing', 'recipient', 'donor', 'foodMatch']);
        
        return view('admin.pickup-verifications.show', compact('verification'));
    }

    public function adminResolve(Request $request, PickupVerification $verification)
    {
        // Only admins can resolve disputes
        $this->middleware('admin');
        
        $request->validate([
            'resolution' => 'required|string',
            'admin_notes' => 'required|string',
            'resolved_status' => 'required|in:completed,disputed',
        ]);

        $verification->update([
            'verification_status' => $request->resolved_status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Verification resolved successfully.');
    }
}