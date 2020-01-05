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
    ->namespace('API')
    ->name('api.people.')
    ->prefix('people')
    ->group(function () {

        // Get list of people
        Route::get('', 'PeopleController@index')
            ->name('index')
            ->middleware('can:list,Modules\People\Entities\Person');

        // Filter persons
        Route::get('filterPersons', 'PeopleController@filterPersons')
            ->name('filterPersons')
            ->middleware('can:list,Modules\People\Entities\Person');

        // Set gender
        Route::patch('{person}/gender', 'PeopleController@setGender')
            ->name('setGender')
            ->middleware('can:update,person');

        // Set date of birth
        Route::patch('{person}/date_of_birth', 'PeopleController@setDateOfBirth')
            ->name('setDateOfBirth')
            ->middleware('can:update,person');

        // Set nationality
        Route::patch('{person}/nationality', 'PeopleController@setNationality')
            ->name('setNationality')
            ->middleware('can:update,person');

        // Update remarks
        Route::patch('{person}/remarks', 'PeopleController@updateRemarks')
            ->name('updateRemarks')
            ->middleware('can:update,person');

        // Register code card
        Route::patch('{person}/card', 'PeopleController@registerCard')
            ->name('registerCard')
            ->middleware('can:update,person');

        // Reporting
        Route::prefix('reporting')
            ->name('reporting.')
            ->middleware(['can:view-people-reports'])
            ->group(function(){
                Route::get('nationalities', 'ReportingController@nationalities')
                    ->name('nationalities');
                Route::get('genderDistribution', 'ReportingController@genderDistribution')
                    ->name('genderDistribution');
                Route::get('ageDistribution', 'ReportingController@ageDistribution')
                    ->name('ageDistribution');
                Route::get('registrationsPerDay', 'ReportingController@registrationsPerDay')
                    ->name('registrationsPerDay');
            });
    });
