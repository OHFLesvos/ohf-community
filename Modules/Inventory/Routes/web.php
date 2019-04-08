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

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::prefix('inventory')->name('inventory.')->group(function(){
        Route::resource('storages', 'StorageController');

        Route::get('transactions/{storage}', 'ItemTransactionController@changes')->name('transactions.changes');

        Route::get('transactions/{storage}/ingress', 'ItemTransactionController@ingress')->name('transactions.ingress');
        Route::post('transactions/{storage}/ingress', 'ItemTransactionController@storeIngress')->name('transactions.storeIngress');

        Route::get('transactions/{storage}/egress', 'ItemTransactionController@egress')->name('transactions.egress');
        Route::post('transactions/{storage}/egress', 'ItemTransactionController@storeEgress')->name('transactions.storeEgress');

        Route::delete('transactions/{storage}', 'ItemTransactionController@destroy')->name('transactions.destroy');
    });
});
