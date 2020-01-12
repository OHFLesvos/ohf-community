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
            Route::get('withdrawal/dailyStats', 'BankController@dailyStats')
                ->name('dailyStats');
            Route::get('withdrawal/transactions', 'BankController@transactions')
                ->name('transactions');
            Route::get('withdrawal/search', 'BankController@search')
                ->name('search');
            Route::post('person/{person}/couponType/{couponType}/handout', 'BankController@handoutCoupon')
                ->name('handoutCoupon');
            Route::delete('person/{person}/couponType/{couponType}/handout', 'BankController@undoHandoutCoupon')
                ->name('undoHandoutCoupon');
    });
});
