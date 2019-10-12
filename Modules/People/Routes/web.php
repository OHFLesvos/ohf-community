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

    // People
    Route::get('/people/export', 'PeopleController@export')->name('people.export')->middleware('can:export,Modules\People\Entities\Person');
    Route::get('/people/import', 'PeopleController@import')->name('people.import')->middleware('can:create,Modules\People\Entities\Person');
    Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport')->middleware('can:create,Modules\People\Entities\Person');
    Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode')->middleware('can:view,person');
    Route::get('/people/{person}/relations', 'PeopleController@relations')->name('people.relations');
    Route::post('/people/{person}/relations', 'PeopleController@addRelation')->name('people.addRelation');
    Route::delete('/people/{person}/children/{child}', 'PeopleController@removeChild')->name('people.removeChild');
    Route::delete('/people/{person}/partner', 'PeopleController@removePartner')->name('people.removePartner');
    Route::delete('/people/{person}/mother', 'PeopleController@removeMother')->name('people.removeMother');
    Route::delete('/people/{person}/father', 'PeopleController@removeFather')->name('people.removeFather');
    Route::get('/people/duplicates', 'PeopleController@duplicates')->name('people.duplicates');
    Route::post('/people/duplicates', 'PeopleController@applyDuplicates')->name('people.applyDuplicates');
    Route::post('/people/bulkAction', 'PeopleController@bulkAction')->name('people.bulkAction')->middleware('can:manage-people');
    Route::resource('/people', 'PeopleController');

    Route::namespace('Reporting')->prefix('reporting')->group(function(){
        // Reporting: Monthly summary report
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('monthly-summary', 'MonthlySummaryReportingController@index')->name('reporting.monthly-summary');
        });

        // Reporting: People
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('people', 'PeopleReportingController@index')->name('reporting.people');
            Route::get('people/chart/nationalities', 'PeopleReportingController@nationalities')->name('reporting.people.nationalities');
            Route::get('people/chart/genderDistribution', 'PeopleReportingController@genderDistribution')->name('reporting.people.genderDistribution');
            Route::get('people/chart/demographics', 'PeopleReportingController@demographics')->name('reporting.people.demographics');
            Route::get('people/chart/visitorsPerDay', 'PeopleReportingController@visitorsPerDay')->name('reporting.people.visitorsPerDay');
            Route::get('people/chart/visitorsPerWeek', 'PeopleReportingController@visitorsPerWeek')->name('reporting.people.visitorsPerWeek');
            Route::get('people/chart/visitorsPerMonth', 'PeopleReportingController@visitorsPerMonth')->name('reporting.people.visitorsPerMonth');
            Route::get('people/chart/visitorsPerYear', 'PeopleReportingController@visitorsPerYear')->name('reporting.people.visitorsPerYear');
            Route::get('people/chart/avgVisitorsPerDayOfWeek', 'PeopleReportingController@avgVisitorsPerDayOfWeek')->name('reporting.people.avgVisitorsPerDayOfWeek');
            Route::get('people/chart/registrationsPerDay', 'PeopleReportingController@registrationsPerDay')->name('reporting.people.registrationsPerDay');
        });
    });

});
