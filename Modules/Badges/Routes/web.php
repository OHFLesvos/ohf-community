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
    Route::name('badges.')->prefix('badges')->middleware(['can:create-badges'])->group(function(){
        Route::get('/', 'BadgeMakerController@index')->name('index');
        Route::post('/selection', 'BadgeMakerController@selection')->name('selection');
        Route::post('/make', 'BadgeMakerController@make')->name('make');
    });
});
