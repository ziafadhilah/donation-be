<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaigns.index', [
            'campaigns' => $campaigns
        ]);
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'current_amount' => $request->current_amount ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully');
    }

    public function edit($id)
    {
        $campaigns = Campaign::findOrFail($id);
        return view('campaigns.edit', [
            'campaigns' => $campaigns
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:1000',
        ]);

        if ($request->goal_amount < $campaign->current_amount) {
            return back()
                ->withErrors('Goal tidak boleh lebih kecil dari dana terkumpul.')
                ->withInput();
        }

        $campaign->update([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
        ]);

        if ($campaign->current_amount >= $campaign->goal_amount) {
            $campaign->update(['status' => 'completed']);
        } else {
            $campaign->update(['status' => 'active']);
        }

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }
}
