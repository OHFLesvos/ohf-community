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

Route::middleware('auth')->namespace('API')->group(function () {
    Route::apiResource('tasks', 'TasksController');
    Route::patch('tasks/{task}/done', 'TasksController@done')->name('tasks.done');
});
