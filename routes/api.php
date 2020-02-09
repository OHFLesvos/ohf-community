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

//
// Accounting
//

Route::middleware(['language', 'auth'])
    ->prefix('accounting')
    ->name('accounting.')
    ->namespace('Accounting\API')
    ->group(function () {
        Route::post('transactions/{transaction}/receipt', 'MoneyTransactionsController@updateReceipt')
            ->name('transactions.updateReceipt');
    });

//
// Collaboration
//

Route::middleware('auth')
    ->namespace('Collaboration\API')
    ->prefix('calendar')
    ->name('calendar.')
    ->group(function () {
        Route::apiResource('events', 'CalendarEventController');
        Route::patch('events/{event}/date', 'CalendarEventController@updateDate')
            ->name('events.updateDate');
        Route::apiResource('resources', 'CalendarResourceController');
    });

Route::middleware('auth')
    ->namespace('Collaboration\API')
    ->group(function () {
        Route::apiResource('tasks', 'TasksController');
        Route::patch('tasks/{task}/done', 'TasksController@done')
            ->name('tasks.done');
    });
