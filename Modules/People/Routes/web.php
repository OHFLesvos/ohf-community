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

Route::middleware(['auth', 'language'])
    ->group(function () {

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

        // Reporting
        Route::namespace('Reporting')
            ->prefix('reporting')
            ->middleware(['can:view-people-reports'])
            ->group(function(){
            
                // Monthly summary report
                Route::get('monthly-summary', 'MonthlySummaryReportingController@index')
                    ->name('reporting.monthly-summary');

                // People report
                Route::get('people', 'PeopleReportingController@index')
                    ->name('reporting.people');
            });
    });
