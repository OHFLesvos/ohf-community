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
        Route::middleware(['can:validate-shop-coupons'])
            ->prefix('cards')
            ->name('cards.')
            ->group(function() {
                Route::get('listRedeemedToday', 'CardsController@listRedeemedToday')->name('listRedeemedToday');
                Route::get('searchByCode', 'CardsController@searchByCode')->name('searchByCode');
                Route::patch('redeem/{handout}', 'CardsController@redeem')->name('redeem');
                Route::delete('cancel/{handout}', 'CardsController@cancel')->name('cancel');
                Route::get('listNonRedeemedByDay', 'CardsController@listNonRedeemedByDay')->name('listNonRedeemedByDay');
                Route::post('deleteNonRedeemedByDay', 'CardsController@deleteNonRedeemedByDay')->name('deleteNonRedeemedByDay');
            });
    });
