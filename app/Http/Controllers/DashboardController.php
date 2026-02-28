<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCampaigns = Campaign::count();

        $totalAmount = Donation::where('status', 'paid')
            ->sum('amount');

        $totalRealized = Campaign::all()
            ->sum('total_realized');

        $totalRemaining = Campaign::sum(DB::raw('current_amount - 0'));

        $todayAmount = Donation::where('status', 'paid')
            ->whereDate('created_at', now())
            ->sum('amount');

        $recentDonations = Donation::with('campaign')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalCampaigns' => $totalCampaigns,
            'totalAmount' => $totalAmount,
            'totalRealized' => $totalRealized,
            'totalRemaining' => $totalRemaining,
            'todayAmount' => $todayAmount,
            'recentDonations' => $recentDonations
        ]);
    }
}
