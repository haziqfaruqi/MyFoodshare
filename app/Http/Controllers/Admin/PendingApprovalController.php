<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_pending' => User::where('status', 'pending')->count(),
            'pending_donors' => User::where('status', 'pending')->where('role', 'donor')->count(),
            'pending_recipients' => User::where('status', 'pending')->where('role', 'recipient')->count(),
            'avg_approval_time' => '2.3 days', // Placeholder - could calculate from database
        ];

        return view('admin.pending-approvals.index', compact('pendingUsers', 'stats'));
    }

    public function show(User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->route('admin.pending-approvals.index')
                ->with('error', 'User is not pending approval.');
        }

        return view('admin.pending-approvals.show', compact('user'));
    }

    public function approve(Request $request, User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'User is not pending approval.');
        }

        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'admin_notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.pending-approvals.index')
            ->with('success', "User {$user->name} has been approved successfully.");
    }

    public function reject(Request $request, User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->back()->with('error', 'User is not pending approval.');
        }

        $user->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.pending-approvals.index')
            ->with('success', "User {$user->name} has been rejected.");
    }
}