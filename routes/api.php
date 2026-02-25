<?php

use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\FundRealizationApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/donate')->group(function () {
    Route::get('/list', [DonationApiController::class, 'index']);
    Route::post('/', [DonationApiController::class, 'store']);
    Route::get('/{reference}/status', [DonationApiController::class, 'checkStatus']);
});

Route::post('/duitku/callback', [DonationApiController::class, 'callback']);

Route::prefix('/fund-realizations')->group(function () {
    Route::get('/', [FundRealizationApiController::class, 'index']);
    Route::get('/with-campaign', [FundRealizationApiController::class, 'withCampaign']);
});

Route::prefix('/campaigns')->group(function () {
    Route::get('/', [CampaignApiController::class, 'index']);
    Route::get('/with-fund-realizations', [CampaignApiController::class, 'withFundRealizations']);
});
