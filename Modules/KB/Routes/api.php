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

Route::group(['middleware' => 'language'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('kb')->name('kb.')->namespace('API')->group(function(){
            Route::resource('images', 'ImageController')->only(['index', 'store', 'destroy']);
        });
    });
});
