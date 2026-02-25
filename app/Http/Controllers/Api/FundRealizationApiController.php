<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FundRealization;
use Illuminate\Http\Request;

class FundRealizationApiController extends Controller
{
    public function index()
    {
        $fundRealizations = FundRealization::all();

        return response()->json([
            'success' => true,
            'data' => $fundRealizations
        ]);
    }

    public function withCampaign()
    {
        $fundRealizations = FundRealization::with('campaign')->get();

        return response()->json([
            'success' => true,
            'data' => $fundRealizations
        ]);
    }
}
