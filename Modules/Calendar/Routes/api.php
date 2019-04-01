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

Route::group(['middleware' => ['auth']], function () {
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
});
