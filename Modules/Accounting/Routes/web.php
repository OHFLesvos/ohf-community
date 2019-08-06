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
Route::group(['middleware' => 'language'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('accounting')->name('accounting.')->group(function(){

            // Transactions
            Route::get('transactions/export', 'MoneyTransactionsController@export')->name('transactions.export');
            Route::post('transactions/doExport', 'MoneyTransactionsController@doExport')->name('transactions.doExport');
            Route::get('transactions/summary', 'MoneyTransactionsController@summary')->name('transactions.summary');
            Route::get('transactions/{transaction}/snippet', 'MoneyTransactionsController@snippet')->name('transactions.snippet');
            Route::get('transactions/{transaction}/receipt', 'MoneyTransactionsController@editReceipt')->name('transactions.editReceipt');
            Route::post('transactions/{transaction}/receipt', 'MoneyTransactionsController@updateReceipt')->name('transactions.updateReceipt');
            Route::delete('transactions/{transaction}/receipt', 'MoneyTransactionsController@deleteReceipt')->name('transactions.deleteReceipt');
            Route::resource('transactions', 'MoneyTransactionsController');

            // Webling
            Route::get('webling', 'WeblingApiController@index')->name('webling.index');
            Route::post('webling', 'WeblingApiController@store')->name('webling.store');

        });
    });
});