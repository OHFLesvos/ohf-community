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

Route::middleware(['language', 'auth'])->group(function () {
    
    Route::prefix('bank')->name('bank.')->group(function () {

        // Withdrawals
        Route::middleware('can:do-bank-withdrawals')->group(function () {
            Route::get('', function(){
                return redirect()->route('bank.withdrawal');
            })->name('index');

            Route::get('withdrawal', 'WithdrawalController@index')->name('withdrawal');
            Route::get('withdrawal/search', 'WithdrawalController@search')->name('withdrawalSearch');
            Route::get('withdrawal/transactions', 'WithdrawalController@transactions')->name('withdrawalTransactions')
                ->middleware('can:list,Modules\People\Entities\Person');
            
            Route::get('withdrawal/cards/{card}', 'WithdrawalController@showCard')->name('showCard');

            Route::get('codeCard', 'CodeCardController@create')->name('prepareCodeCard');
            Route::post('codeCard', 'CodeCardController@download')->name('createCodeCard');
        });

        // Deposits
        Route::middleware('can:do-bank-deposits')->group(function () {
            Route::get('deposit', 'DepositController@index')->name('deposit');
            Route::post('deposit', 'DepositController@store')->name('storeDeposit');
            Route::get('deposit/transactions', 'DepositController@transactions')->name('depositTransactions');
        });

        // Settings
        Route::middleware('can:configure-bank')->namespace('Settings')->name('settings.')->group(function () {
            Route::get('settings', 'BankSettingsController@edit')->name('edit');
            Route::put('settings', 'BankSettingsController@update')->name('update');
        });

        // Maintenance
        Route::middleware('can:cleanup,Modules\People\Entities\Person')->group(function () {
            Route::get('maintenance', 'MaintenanceController@maintenance')->name('maintenance');
            Route::post('maintenance', 'MaintenanceController@updateMaintenance')->name('updateMaintenance');
        });

        // Export
        Route::middleware('can:export,Modules\People\Entities\Person')->group(function () {
            Route::get('export', 'ImportExportController@export')->name('export');
            Route::post('doExport', 'ImportExportController@doExport')->name('doExport');
        });
    });

    // Coupons
    Route::middleware('can:configure-bank')->prefix('bank')->group(function () {
        Route::resource('coupons', 'CouponTypesController');
    });
    
    // Reporting
    Route::middleware('can:view-bank-reports')->namespace('Reporting')->name('reporting.bank.')->prefix('reporting/bank')->group(function () {
        Route::get('withdrawals', 'BankReportingController@withdrawals')->name('withdrawals');
        Route::get('withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'BankReportingController@couponsHandedOutPerDay')->name('couponsHandedOutPerDay');

        Route::get('deposits', 'BankReportingController@deposits')->name('deposits');
        Route::get('deposits/chart/stats', 'BankReportingController@depositStats')->name('depositStats');
        Route::get('deposits/chart/stats/{project}', 'BankReportingController@projectDepositStats')->name('projectDepositStats');
    });

});