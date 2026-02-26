<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\FundRealization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundRealizationController extends Controller
{
    public function index()
    {
        $realizations = FundRealization::with('campaign')->latest()->get();
        return view('realization.index', [
            'realizations' => $realizations
        ]);
    }

    public function create()
    {
        $campaigns = Campaign::all();
        return view('realization.create', [
            'campaigns' => $campaigns
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'amount' => str_replace('.', '', $request->amount)
        ]);

        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'status' => 'required|in:in_progress,done'
        ]);

        DB::transaction(function () use ($request) {

            $campaign = Campaign::lockForUpdate()->findOrFail($request->campaign_id);

            $totalRealized = $campaign->fundRealizations()
                ->where('status', 'done')
                ->sum('amount');

            if (($totalRealized + $request->amount) > $campaign->current_amount) {
                return back()->withErrors('Dana tidak mencukupi');
            }

            $campaign->fundRealizations()->create($request->all());
        });

        return redirect()->route('fund-realizations.index')
            ->with('success', 'Realisasi berhasil ditambahkan');
    }

    public function edit(FundRealization $fundRealization)
    {
        $campaigns = Campaign::all();
        return view('realization.edit', [
            'fundRealization' => $fundRealization,
            'campaigns' => $campaigns
        ]);
    }

    public function update(Request $request, FundRealization $fundRealization)
    {
        $request->merge([
            'amount' => str_replace('.', '', $request->amount)
        ]);

        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'status' => 'required|in:in_progress,done'
        ]);

        $fundRealization->update($request->all());

        return redirect()->route('fund-realizations.index')
            ->with('success', 'Realisasi berhasil diupdate');
    }

    public function destroy(FundRealization $fundRealization)
    {
        $fundRealization->delete();

        return back()->with('success', 'Realisasi dihapus');
    }
}
