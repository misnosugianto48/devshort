<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the overview dashboard for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get total clicks across all user's links
        $totalClicks = (int) $user->links()->sum('clicks_count');

        // Get total number of links created
        $totalLinks = $user->links()->count();

        // Get 5 most recent links for the quick-view table
        $recentLinks = $user->links()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('totalClicks', 'totalLinks', 'recentLinks'));
    }
}
