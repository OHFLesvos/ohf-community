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

Route::prefix('shop')
    ->name('shop.')
    // TODO: Authentication and authorization
    //->middleware(['can:validate-shop-coupons'])
    ->group(function(){
        Route::get('/', 'ShopController@searchCard')->name('searchCard');
        Route::post('/', 'ShopController@redeemCard')->name('redeemCard');
});
