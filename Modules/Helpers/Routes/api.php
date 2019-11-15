<?php

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

Route::middleware(['auth', 'language'])
    ->name('people.helpers.')
    ->prefix('helpers')
    ->namespace('API')
    ->group(function(){
        // Age distribution
        Route::get('report/ages', 'HelperReportController@ages')
            ->name('report.ages')
            ->middleware('can:list,Modules\Helpers\Entities\Helper');
        // Nationality distribution
        Route::get('report/nationalities', 'HelperReportController@nationalities')
            ->name('report.nationalities')
            ->middleware('can:list,Modules\Helpers\Entities\Helper');
        // Gender distribution
        Route::get('report/genders', 'HelperReportController@genders')
            ->name('report.genders')
            ->middleware('can:list,Modules\Helpers\Entities\Helper');
    });