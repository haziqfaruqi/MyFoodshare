<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FoodListing;
use App\Models\FoodMatch;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Real monthly trends for the last 6 months
        $monthlyData = $this->getMonthlyTrends();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'monthlyData', 'pendingUsers'));
    }

    private function getMonthlyTrends()
    {
        $trends = [];
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $trends[] = [
                'month' => $month->format('M'),
                'listings' => FoodListing::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'matches' => FoodMatch::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }
        
        return $trends;
    }
}