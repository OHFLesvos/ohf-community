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
    Route::middleware(['auth'])->namespace('API')->prefix('logistics')->name('logistics.')->group(function () {
        Route::get('products/filter', 'ProductController@filter')->name('products.filter');
        Route::post('products', 'ProductController@store')->name('products.apiStore');
    });
});