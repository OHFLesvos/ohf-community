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

Route::group(['middleware' => ['language', 'auth']], function () {
    Route::middleware('can:do-bank-withdrawals')
        ->prefix('bank')
        ->name('api.bank.withdrawal.')
        ->namespace('API')
        ->group(function () {
            Route::get('withdrawal/dailyStats', 'WithdrawalController@dailyStats')
                ->name('dailyStats');
            Route::get('withdrawal/transactions', 'WithdrawalController@transactions')
                ->name('transactions')
                ->middleware('can:list,Modules\People\Entities\Person');
            Route::get('withdrawal/search', 'WithdrawalController@search')
                ->name('search');
            Route::get('withdrawal/persons/{person}', 'WithdrawalController@person')
                ->name('person');
            Route::post('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@handoutCoupon')
                ->name('handoutCoupon');
            Route::delete('person/{person}/couponType/{couponType}/handout', 'WithdrawalController@undoHandoutCoupon')
                ->name('undoHandoutCoupon');
    });
});
