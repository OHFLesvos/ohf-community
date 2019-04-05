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
    Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
    Route::get('/people/export', 'PeopleController@export')->name('people.export');
    Route::get('/people/import', 'PeopleController@import')->name('people.import');
    Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
    Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode');
    Route::get('/people/{person}/relations', 'PeopleController@relations')->name('people.relations');
    Route::get('/people/filterPersons', 'PeopleController@filterPersons')->name('people.filterPersons');
    Route::post('/people/{person}/relations', 'PeopleController@addRelation')->name('people.addRelation');
    Route::delete('/people/{person}/children/{child}', 'PeopleController@removeChild')->name('people.removeChild');
    Route::delete('/people/{person}/partner', 'PeopleController@removePartner')->name('people.removePartner');
    Route::delete('/people/{person}/mother', 'PeopleController@removeMother')->name('people.removeMother');
    Route::delete('/people/{person}/father', 'PeopleController@removeFather')->name('people.removeFather');
    Route::get('/people/duplicates', 'PeopleController@duplicates')->name('people.duplicates');
    Route::post('/people/duplicates', 'PeopleController@applyDuplicates')->name('people.applyDuplicates');
    Route::post('/people/bulkAction', 'PeopleController@bulkAction')->name('people.bulkAction');
    Route::resource('/people', 'PeopleController');

    // Reporting: Monthly summary report
    Route::group(['middleware' => ['can:view-people-reports']], function () {
        Route::get('/reporting/monthly-summary', 'Reporting\\MonthlySummaryReportingController@index')->name('reporting.monthly-summary');
    });

    // Reporting: People
    Route::group(['middleware' => ['can:view-people-reports']], function () {
        Route::get('/reporting/people', 'Reporting\\PeopleReportingController@index')->name('reporting.people');
        Route::get('/reporting/people/chart/nationalities', 'Reporting\\PeopleReportingController@nationalities')->name('reporting.people.nationalities');
        Route::get('/reporting/people/chart/genderDistribution', 'Reporting\\PeopleReportingController@genderDistribution')->name('reporting.people.genderDistribution');
        Route::get('/reporting/people/chart/demographics', 'Reporting\\PeopleReportingController@demographics')->name('reporting.people.demographics');
        Route::get('/reporting/people/chart/numberTypes', 'Reporting\\PeopleReportingController@numberTypes')->name('reporting.people.numberTypes');
        Route::get('/reporting/people/chart/visitorsPerDay', 'Reporting\\PeopleReportingController@visitorsPerDay')->name('reporting.people.visitorsPerDay');
        Route::get('/reporting/people/chart/visitorsPerWeek', 'Reporting\\PeopleReportingController@visitorsPerWeek')->name('reporting.people.visitorsPerWeek');
        Route::get('/reporting/people/chart/visitorsPerMonth', 'Reporting\\PeopleReportingController@visitorsPerMonth')->name('reporting.people.visitorsPerMonth');
        Route::get('/reporting/people/chart/visitorsPerYear', 'Reporting\\PeopleReportingController@visitorsPerYear')->name('reporting.people.visitorsPerYear');
        Route::get('/reporting/people/chart/avgVisitorsPerDayOfWeek', 'Reporting\\PeopleReportingController@avgVisitorsPerDayOfWeek')->name('reporting.people.avgVisitorsPerDayOfWeek');
        Route::get('/reporting/people/chart/registrationsPerDay', 'Reporting\\PeopleReportingController@registrationsPerDay')->name('reporting.people.registrationsPerDay');
    });
});
