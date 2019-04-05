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

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::patch('people/{person}/gender', 'API\PeopleController@setGender')->name('people.setGender');
    Route::patch('people/{person}/date_of_birth', 'API\PeopleController@setDateOfBirth')->name('people.setDateOfBirth');
    Route::patch('people/{person}/nationality', 'API\PeopleController@setNationality')->name('people.setNationality');
});
