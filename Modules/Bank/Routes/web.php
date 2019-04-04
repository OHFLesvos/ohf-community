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

Route::group(['middleware' => ['language', 'auth']], function () {

    Route::get('/bank', function(){
        return redirect()->route('bank.withdrawal');
    })->name('bank.index');

    // Withdrawals
    Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
        Route::get('/bank/withdrawal', 'WithdrawalController@index')->name('bank.withdrawal');
        Route::get('/bank/withdrawal/search', 'WithdrawalController@search')->name('bank.withdrawalSearch');
        Route::get('/bank/withdrawal/transactions', 'WithdrawalController@transactions')->name('bank.withdrawalTransactions');
        
        Route::get('/bank/withdrawal/cards/{card}', 'WithdrawalController@showCard')->name('bank.showCard');

        Route::get('/bank/codeCard', 'CodeCardController@create')->name('bank.prepareCodeCard');
        Route::post('/bank/codeCard', 'CodeCardController@download')->name('bank.createCodeCard');
    });

    // Deposits
    Route::group(['middleware' => ['can:do-bank-deposits']], function () {
        Route::get('/bank/deposit', 'DepositController@index')->name('bank.deposit');
        Route::post('/bank/deposit', 'DepositController@store')->name('bank.storeDeposit');
        Route::get('/bank/deposit/transactions', 'DepositController@transactions')->name('bank.depositTransactions');
    });

    // Settings
    Route::group(['middleware' => ['can:configure-bank']], function () {
        Route::get('/bank/settings', 'BankSettingsController@edit')->name('bank.settings.edit');
        Route::put('/bank/settings', 'BankSettingsController@update')->name('bank.settings.update');
        Route::resource('/bank/coupons', 'CouponTypesController');
    });

    // Maintenance
    Route::group(['middleware' => ['can:cleanup,App\Person']], function () {
        Route::get('/bank/maintenance', 'MaintenanceController@maintenance')->name('bank.maintenance');
        Route::post('/bank/maintenance', 'MaintenanceController@updateMaintenance')->name('bank.updateMaintenance');
    });

    // Export
    Route::group(['middleware' => ['can:export,App\Person']], function () {
        Route::get('/bank/export', 'ImportExportController@export')->name('bank.export');
        Route::post('/bank/doExport', 'ImportExportController@doExport')->name('bank.doExport');
    });

});