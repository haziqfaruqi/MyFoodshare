<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\Recipient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors' => User::where('role', 'donor')->count(),
            'total_recipients' => User::where('role', 'recipient')->count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'active_listings' => FoodListing::where('status', 'active')->count(),
            'total_matches' => FoodMatch::count(),
        ];

        $pendingUsers = User::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentActivity = [
            ['user' => 'Golden Spoon', 'action' => 'Listed 30 sandwiches', 'time' => '5 min ago', 'status' => 'success'],
            ['user' => 'Pizza Corner', 'action' => 'Pickup completed', 'time' => '15 min ago', 'status' => 'success'],
            ['user' => 'Hope Foundation', 'action' => 'New registration pending', 'time' => '1 hour ago', 'status' => 'warning'],
        ];

        $monthlyData = [
            ['month' => 'Jan', 'listings' => 45, 'matches' => 38, 'users' => 12],
            ['month' => 'Feb', 'listings' => 52, 'matches' => 44, 'users' => 18],
            ['month' => 'Mar', 'listings' => 48, 'matches' => 41, 'users' => 15],
            ['month' => 'Apr', 'listings' => 61, 'matches' => 52, 'users' => 22],
            ['month' => 'May', 'listings' => 58, 'matches' => 49, 'users' => 19],
            ['month' => 'Jun', 'listings' => 67, 'matches' => 58, 'users' => 25],
        ];

        return view('admin.dashboard', compact('stats', 'recentActivity', 'monthlyData', 'pendingUsers'));
    }
}