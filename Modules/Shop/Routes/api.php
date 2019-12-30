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

Route::middleware(['auth', 'language'])
    ->prefix('shop')
    ->name('shop.')
    ->namespace('API')
    ->group(function(){
        Route::middleware(['can:validate-shop-coupons'])->group(function() {
            Route::get('/', 'ShopController@listCards')->name('listCards');
            Route::get('card', 'ShopController@getCard')->name('getCard');
            Route::patch('card/{handout}/redeem', 'ShopController@redeemCard')->name('redeemCard');
            Route::delete('card/{handout}', 'ShopController@cancelCard')->name('cancelCard');
            Route::get('/summary', 'ShopController@summary')->name('summary');
            Route::post('/deleteNonRedeemed', 'ShopController@deleteNonRedeemed')->name('deleteNonRedeemed');
        });
    });
