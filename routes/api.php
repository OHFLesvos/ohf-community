<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth']], function () {

    // Tasks
    Route::resource('tasks', 'TasksController');
    Route::put('/tasks/{task}/done', 'TasksController@done');

    // Calendar
    Route::apiResource('calendar/events', 'API\CalendarEventController', ['names' => [
        'index' => 'calendar.events.index',
        'store' => 'calendar.events.store',
        'show' => 'calendar.events.show',
        'update' => 'calendar.events.update',
        'destroy' => 'calendar.events.destroy',
    ]]);
    Route::put('/calendar/events/{event}/date', 'API\CalendarEventController@updateDate')->name('calendar.events.updateDate');
    Route::apiResource('calendar/resources', 'API\CalendarResourceController', ['names' => [
        'index' => 'calendar.resources.index',
        'store' => 'calendar.resources.store',
        'show' => 'calendar.resources.show',
        'update' => 'calendar.resources.update',
        'destroy' => 'calendar.resources.destroy',
    ]]);
   
    //
    // Bank
    //
    Route::group(['middleware' => 'language'], function () {    
        Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
            Route::post('/bank/handoutCoupon', 'API\People\BankController@handoutCoupon')->name('bank.handoutCoupon');
            Route::post('/bank/undoHandoutCoupon', 'API\People\BankController@undoHandoutCoupon')->name('bank.undoHandoutCoupon');
            Route::post('/bank/registerCard', 'API\People\BankController@registerCard')->name('bank.registerCard');
        });
        Route::post('/bank/updateGender', 'API\People\PeopleController@updateGender')->name('bank.updateGender');
        Route::post('/bank/updateDateOfBirth', 'API\People\PeopleController@updateDateOfBirth')->name('bank.updateDateOfBirth');
        Route::post('/bank/updateNationality', 'API\People\PeopleController@updateNationality')->name('bank.updateNationality');
    });
});

// RaiseNow Webhook
Route::namespace('Fundraising')
    ->middleware(['auth.basic', 'can:accept-fundraising-webhooks'])
    ->prefix('fundraising')
    ->name('fundraising.')
    ->group(function () {
        Route::name('donations.raiseNowWebHookListener')->post('donations/raiseNowWebHookListener', 'DonationController@raiseNowWebHookListener');
    });

