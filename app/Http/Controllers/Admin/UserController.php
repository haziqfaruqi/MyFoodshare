<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('restaurant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('organization_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);
        $pendingCount = User::where('status', 'pending')->count();

        return view('admin.users.index', compact('users', 'pendingCount'));
    }

    public function show(User $user)
    {
        $stats = [
            'total_listings' => $user->foodListings()->count(),
            'active_listings' => $user->foodListings()->where('status', 'active')->count(),
            'completed_matches' => \App\Models\FoodMatch::whereHas('foodListing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'approved_at' => $request->status === 'active' ? now() : null,
            'approved_by' => $request->status === 'active' ? auth()->id() : null,
        ]);

        $message = match($request->status) {
            'active' => 'User approved successfully!',
            'rejected' => 'User rejected successfully!',
            'suspended' => 'User suspended successfully!',
            default => 'User status updated successfully!'
        };

        return back()->with('success', $message);
    }

    public function approve(User $user)
    {
        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // TODO: Send approval email notification

        return back()->with('success', "User '{$user->name}' has been approved successfully!");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $user->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => auth()->id(),
        ]);

        // TODO: Send rejection email notification

        return back()->with('success', "User '{$user->name}' has been rejected.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}