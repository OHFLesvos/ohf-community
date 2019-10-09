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
    ->group(function () {
        Route::post('people/filter', 'PeopleController@filter')
            ->name('people.filter')
            ->middleware('can:list,Modules\People\Entities\Person');
        Route::get('people/filterPersons', 'PeopleController@filterPersons')
            ->name('people.filterPersons')
            ->middleware('can:list,Modules\People\Entities\Person');
        Route::patch('people/{person}/gender', 'PeopleController@setGender')
            ->name('people.setGender')
            ->middleware('can:update,person');
        Route::patch('people/{person}/date_of_birth', 'PeopleController@setDateOfBirth')
            ->name('people.setDateOfBirth')
            ->middleware('can:update,person');
        Route::patch('people/{person}/nationality', 'PeopleController@setNationality')
            ->name('people.setNationality')
            ->middleware('can:update,person');
        Route::patch('people/{person}/card', 'PeopleController@registerCard')
            ->name('people.registerCard')
            ->middleware('can:update,person');
    });
