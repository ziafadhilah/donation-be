<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FundRealizationController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::prefix('/')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/donations')->name('donations.')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('index');
        Route::patch('/{donation}/toggle-visibility', [DonationController::class, 'toggleVisibility'])->name('toggleVisibility');
        Route::patch('/{donation}/force-success', [DonationController::class, 'forceSuccess'])->name('forceSuccess');
    });

    Route::prefix('/fund-realizations')->name('fund-realizations.')->group(function () {
        Route::get('/', [FundRealizationController::class, 'index'])->name('index');
        Route::get('/create', [FundRealizationController::class, 'create'])->name('create');
        Route::post('/', [FundRealizationController::class, 'store'])->name('store');
        Route::get('/{fundRealization}/edit', [FundRealizationController::class, 'edit'])->name('edit');
        Route::put('/{fundRealization}', [FundRealizationController::class, 'update'])->name('update');
        Route::delete('/{fundRealization}', [FundRealizationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('/create', [UnitController::class, 'create'])->name('create');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
    });
});
