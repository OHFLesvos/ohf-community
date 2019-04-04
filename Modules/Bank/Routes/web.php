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

    Route::prefix('bank')->group(function () {

        Route::get('', function(){
            return redirect()->route('bank.withdrawal');
        })->name('bank.index');

        // Withdrawals
        Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
            Route::get('withdrawal', 'WithdrawalController@index')->name('bank.withdrawal');
            Route::get('withdrawal/search', 'WithdrawalController@search')->name('bank.withdrawalSearch');
            Route::get('withdrawal/transactions', 'WithdrawalController@transactions')->name('bank.withdrawalTransactions');
            
            Route::get('withdrawal/cards/{card}', 'WithdrawalController@showCard')->name('bank.showCard');

            Route::get('codeCard', 'CodeCardController@create')->name('bank.prepareCodeCard');
            Route::post('codeCard', 'CodeCardController@download')->name('bank.createCodeCard');
        });

        // Deposits
        Route::group(['middleware' => ['can:do-bank-deposits']], function () {
            Route::get('deposit', 'DepositController@index')->name('bank.deposit');
            Route::post('deposit', 'DepositController@store')->name('bank.storeDeposit');
            Route::get('deposit/transactions', 'DepositController@transactions')->name('bank.depositTransactions');
        });

        // Settings
        Route::group(['middleware' => ['can:configure-bank']], function () {
            Route::get('settings', 'BankSettingsController@edit')->name('bank.settings.edit');
            Route::put('settings', 'BankSettingsController@update')->name('bank.settings.update');
            Route::resource('coupons', 'CouponTypesController');
        });

        // Maintenance
        Route::group(['middleware' => ['can:cleanup,App\Person']], function () {
            Route::get('maintenance', 'MaintenanceController@maintenance')->name('bank.maintenance');
            Route::post('maintenance', 'MaintenanceController@updateMaintenance')->name('bank.updateMaintenance');
        });

        // Export
        Route::group(['middleware' => ['can:export,App\Person']], function () {
            Route::get('export', 'ImportExportController@export')->name('bank.export');
            Route::post('doExport', 'ImportExportController@doExport')->name('bank.doExport');
        });
    });
    
    // Reporting
    Route::group(['middleware' => ['can:view-bank-reports']], function () {
        Route::get('reporting/bank/withdrawals', 'BankReportingController@withdrawals')->name('reporting.bank.withdrawals');
        Route::get('reporting/bank/withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'BankReportingController@couponsHandedOutPerDay')->name('reporting.bank.couponsHandedOutPerDay');

        Route::get('reporting/bank/deposits', 'BankReportingController@deposits')->name('reporting.bank.deposits');
        Route::get('reporting/bank/deposits/chart/stats', 'BankReportingController@depositStats')->name('reporting.bank.depositStats');
        Route::get('reporting/bank/deposits/chart/stats/{project}', 'BankReportingController@projectDepositStats')->name('reporting.bank.projectDepositStats');
    });

});