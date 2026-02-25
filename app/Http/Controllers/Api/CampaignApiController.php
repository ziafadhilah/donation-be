<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;

class CampaignApiController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    public function withFundRealizations()
    {
        $campaigns = Campaign::with('fundRealizations')->get();

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }
}
