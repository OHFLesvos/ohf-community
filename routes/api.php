<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->prefix('fundraising')
    ->name('api.fundraising.')
    ->namespace('Fundraising\API')
    ->group(function () {
        Route::apiResource('donors', 'DonorController');
    });

// RaiseNow Webhook
Route::middleware(['auth.basic', 'can:accept-fundraising-webhooks'])
    ->prefix('fundraising/donations')
    ->name('fundraising.')
    ->namespace('Fundraising\API')
    ->group(function () {
        Route::name('donations.raiseNowWebHookListener')->post('raiseNowWebHookListener', 'DonationController@raiseNowWebHookListener');
    });
