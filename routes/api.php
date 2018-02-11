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
    Route::apiResource('calendar/events', 'Api\CalendarEventController', ['names' => [
        'index' => 'calendar.events.index',
        'store' => 'calendar.events.store',
        'show' => 'calendar.events.show',
        'update' => 'calendar.events.update',
        'destroy' => 'calendar.events.destroy',
    ]]);
    Route::put('/calendar/events/{event}/date', 'Api\CalendarEventController@updateDate')->name('calendar.events.updateDate');
   
});
