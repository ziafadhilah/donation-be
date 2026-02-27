<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;

class CampaignApiController extends BaseApiController
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'goal_amount' => $campaign->goal_amount,
                    'current_amount' => $campaign->current_amount,
                    'progress_percentage' => $campaign->progress_percentage,
                    'start_date' => $campaign->start_date,
                    'end_date' => $campaign->end_date,
                    'status' => $campaign->status,
                ];
            });

        return $this->success($campaigns, 'Campaign list');
    }

    public function withFundRealizations()
    {
        $campaigns = Campaign::with('fundRealizations')->get();

        return $this->success($campaigns, 'Campaign with realizations');
    }
}
