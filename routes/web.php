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

Route::middleware('language')->group(function () {
    
    Route::middleware('auth')->group(function () {

        // Home (Dashboard)
        Route::get('/', 'HomeController@index')->name('home');

        // Reporting
        Route::view('reporting', 'reporting.index')->name('reporting.index')->middleware('can:view-reports');

    });

    // Authentication
    Auth::routes();

    Route::get('login/{driver}/redirect', 'Auth\LoginController@redirectToProvider')
        ->name('login.provider')
        ->where('driver', implode('|', config('auth.socialite.drivers')));
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback')
        ->name('login.callback')
        ->where('driver', implode('|', config('auth.socialite.drivers')));

    // Privacy policy
    Route::get('userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});
