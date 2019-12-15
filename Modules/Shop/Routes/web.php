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
            Route::get('/', 'ShopController@index')->name('index');
            Route::patch('/card/{handout}/redeem', 'ShopController@redeem')->name('redeem');
            Route::delete('/card/{handout}', 'ShopController@cancelCard')->name('cancelCard');
        });
        Route::middleware(['can:configure-shop'])->group(function() {
            Route::get('/settings', 'ShopSettingsController@edit')->name('settings.edit');
            Route::put('/settings', 'ShopSettingsController@update')->name('settings.update');
        });
});
