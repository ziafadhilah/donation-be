<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Campaign
        $totalCampaigns = Campaign::count();

        // Total Dana Masuk (Paid)
        $totalAmount = Donation::where('status', 'paid')
            ->sum('amount');

        // Total Realisasi (Done)
        $totalRealized = Campaign::all()
            ->sum('total_realized');

        // Total Sisa Dana Global
        $totalRemaining = Campaign::all()
            ->sum('remaining_balance');

        // Donation Hari Ini
        $todayAmount = Donation::where('status', 'paid')
            ->whereDate('created_at', now())
            ->sum('amount');

        // Recent Donations
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
