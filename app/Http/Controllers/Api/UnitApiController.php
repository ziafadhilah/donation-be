<?php

namespace App\Http\Controllers\Api;

use App\Models\Unit;

class UnitApiController extends BaseApiController
{
    public function index()
    {
        $units = Unit::where('is_active', true)->get();

        return $this->success($units, 'Unit list');
    }
}
