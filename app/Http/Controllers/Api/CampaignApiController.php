<?php

namespace App\Http\Controllers\Api;

use App\Models\Campaign;

class CampaignApiController extends BaseApiController
{
    public function index()
    {
        $campaigns = Campaign::with('donations')->get();

        return $this->success($campaigns, 'Campaign list');
    }

    public function withFundRealizations()
    {
        $campaigns = Campaign::with('fundRealizations')->get();

        return $this->success($campaigns, 'Campaign with realizations');
    }
}
