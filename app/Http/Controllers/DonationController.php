<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['campaign', 'unit']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $donations = $query->latest()->paginate(10)->withQueryString();
        $campaigns = Campaign::all();

        return view('donations.index', compact('donations', 'campaigns'));
    }

    public function toggleVisibility(Donation $donation)
    {
        $donation->update([
            'is_visible' => !$donation->is_visible
        ]);

        return back()->with('success', 'Visibility updated');
    }

    public function forceSuccess(Donation $donation)
    {
        if ($donation->status === 'paid') {
            return back()->with('info', 'Sudah paid');
        }

        if ($donation->status === 'failed') {
            return back()->with('error', 'Tidak bisa force dari status failed.');
        }

        DB::transaction(function () use ($donation) {

            $donation->update([
                'status'  => 'paid',
                'paid_at' => now()
            ]);

            $campaign = $donation->campaign()->lockForUpdate()->first();
            $campaign->increment('current_amount', $donation->amount);

            if ($campaign->current_amount >= $campaign->goal_amount) {
                $campaign->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Donation berhasil di force menjadi paid');
    }
}
