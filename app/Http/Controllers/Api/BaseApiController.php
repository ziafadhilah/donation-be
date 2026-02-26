<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected function success($data = null, string $message = '', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ], $code);
    }

    protected function error(string $message = '', $data = null, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'data'    => $data,
            'message' => $message,
        ], $code);
    }
}
