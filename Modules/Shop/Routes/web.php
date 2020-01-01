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

Route::middleware(['auth', 'language'])
    ->prefix('shop')
    ->name('shop.')
    ->group(function () {
        Route::middleware(['can:validate-shop-coupons'])->group(function() {
            Route::view('/', 'shop::index')->name('index');
            Route::view('manageCards', 'shop::manageCards')->name('manageCards');
        });
        Route::middleware(['can:configure-shop'])->group(function() {
            Route::get('settings', 'SettingsController@edit')->name('settings.edit');
            Route::put('settings', 'SettingsController@update')->name('settings.update');
        });
});
