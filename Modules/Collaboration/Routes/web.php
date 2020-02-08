<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['language', 'auth']], function () {

    Route::view('calendar', 'collaboration::calendar')
        ->name('calendar')
        ->middleware('can:view-calendar');

    Route::view('tasks', 'collaboration::tasklist')
        ->name('tasks')
        ->middleware('can:list,Modules\Collaboration\Entities\Task');

});
