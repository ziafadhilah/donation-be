<?php

use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\FundRealizationApiController;
use Illuminate\Support\Facades\Route;

Route::post('/donate', [DonationApiController::class, 'store']);
Route::post('/duitku/callback', [DonationApiController::class, 'callback']);
Route::get('/donate/{reference}/status', [DonationApiController::class, 'checkStatus']);

Route::get('/campaigns', [CampaignApiController::class, 'index']);
Route::get('/campaigns-with-fund-realizations', [CampaignApiController::class, 'withFundRealizations']);

Route::get('/fund-realizations-list', [FundRealizationApiController::class, 'index']);
Route::get('/fund-realizations-with-campaign', [FundRealizationApiController::class, 'withCampaign']);
