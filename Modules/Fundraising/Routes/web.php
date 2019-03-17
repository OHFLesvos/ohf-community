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
    Route::prefix('fundraising')->name('fundraising.')->group(function () {
        // Donors
        Route::name('donors.export')->get('donors/export', 'DonorController@export');
        Route::name('donors.vcard')->get('donors/{donor}/vcard', 'DonorController@vcard');
        Route::resource('donors', 'DonorController');

        // Donations
        Route::name('donations.index')->get('donations', 'DonationController@index');
        Route::name('donations.import')->get('donations/import', 'DonationController@import');
        Route::name('donations.doImport')->post('donations/import', 'DonationController@doImport');
        Route::prefix('donors/{donor}')->group(function () {
            Route::name('donations.export')->get('export', 'DonationController@export');
            Route::resource('donations', 'DonationController')->except('show', 'index');
        });
    });
});