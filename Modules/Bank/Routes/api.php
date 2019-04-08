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
    Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
        Route::post('/bank/handoutCoupon', 'API\BankController@handoutCoupon')->name('bank.handoutCoupon');
        Route::post('/bank/undoHandoutCoupon', 'API\BankController@undoHandoutCoupon')->name('bank.undoHandoutCoupon');
        Route::post('/bank/registerCard', 'API\BankController@registerCard')->name('bank.registerCard');
    });
});
