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
    ->group(function () {

        // Get list of people
        Route::get('people', 'PeopleController@index')
            ->name('index')
            ->middleware('can:list,Modules\People\Entities\Person');
        
        // Filter persons
        Route::get('people/filterPersons', 'PeopleController@filterPersons')
            ->name('filterPersons')
            ->middleware('can:list,Modules\People\Entities\Person');

        // Set gender
        Route::patch('people/{person}/gender', 'PeopleController@setGender')
            ->name('setGender')
            ->middleware('can:update,person');

        // Set date of birth
        Route::patch('people/{person}/date_of_birth', 'PeopleController@setDateOfBirth')
            ->name('setDateOfBirth')
            ->middleware('can:update,person');

        // Set nationality
        Route::patch('people/{person}/nationality', 'PeopleController@setNationality')
            ->name('setNationality')
            ->middleware('can:update,person');

        // Register code card
        Route::patch('people/{person}/card', 'PeopleController@registerCard')
            ->name('registerCard')
            ->middleware('can:update,person');
    });
