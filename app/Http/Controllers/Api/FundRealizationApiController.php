<?php

namespace App\Http\Controllers\Api;

use App\Models\FundRealization;

class FundRealizationApiController extends BaseApiController
{
    public function index()
    {
        $data = FundRealization::all();

        return $this->success($data, 'Fund realizations list');
    }

    public function withCampaign()
    {
        $data = FundRealization::with('campaign')->get();

        return $this->success($data, 'Fund realizations with campaign');
    }
}
