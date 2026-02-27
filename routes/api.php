<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\FundRealizationApiController;
use App\Http\Controllers\Api\UnitApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// ================================
// CAMPAIGNS
// ================================
Route::get('/campaigns', [CampaignApiController::class, 'index']);
Route::get('/campaigns/with-realizations', [CampaignApiController::class, 'withFundRealizations']);


// ================================
// DONATIONS (Multi Step Flow)
// ================================

// Step 1 - Create donation (without payment gateway)
Route::post('/donations', [DonationApiController::class, 'store']);

// Step 2 - Choose payment method & request gateway
Route::post('/donations/{reference}/pay', [DonationApiController::class, 'pay']);

// Check donation status
Route::get('/donations/{reference}/status', [DonationApiController::class, 'checkStatus']);


// ================================
// PAYMENT CALLBACK (Gateway)
// ================================

Route::post('/duitku/callback', [DonationApiController::class, 'callback']);


// ================================
// FUND REALIZATIONS
// ================================

Route::get('/fund-realizations', [FundRealizationApiController::class, 'index']);
Route::get('/fund-realizations/with-campaign', [FundRealizationApiController::class, 'withCampaign']);

// ================================
// Units
// ================================

Route::get('/units', [UnitApiController::class, 'index']);
