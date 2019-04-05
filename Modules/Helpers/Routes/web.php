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

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::name('people.')->group(function(){
        Route::get('helpers/report', 'HelperListController@report')->name('helpers.report')->middleware('can:list,Modules\Helpers\Entities\Helper');
        Route::get('helpers/report/ages', 'HelperListController@ages')->name('helpers.report.ages');
        Route::get('helpers/report/nationalities', 'HelperListController@nationalities')->name('helpers.report.nationalities');
        Route::get('helpers/report/genderDistribution', 'HelperListController@genderDistribution')->name('helpers.report.genderDistribution');
        Route::get('helpers/export', 'HelperListController@export')->name('helpers.export')->middleware('can:export,Modules\Helpers\Entities\Helper');
        Route::post('helpers/doExport', 'HelperListController@doExport')->name('helpers.doExport')->middleware('can:export,Modules\Helpers\Entities\Helper');
        Route::get('helpers/import', 'HelperListController@import')->name('helpers.import')->middleware('can:import,Modules\Helpers\Entities\Helper');
        Route::post('helpers/doImport', 'HelperListController@doImport')->name('helpers.doImport')->middleware('can:import,Modules\Helpers\Entities\Helper');
        Route::get('helpers/createFrom', 'HelperListController@createFrom')->name('helpers.createFrom')->middleware('can:create,Modules\Helpers\Entities\Helper');
        Route::post('helpers/createFrom', 'HelperListController@storeFrom')->name('helpers.storeFrom')->middleware('can:create,Modules\Helpers\Entities\Helper');
        Route::get('helpers/{helper}/vcard', 'HelperListController@vcard')->name('helpers.vcard');
        Route::get('helpers/filterPersons', 'HelperListController@filterPersons')->name('helpers.filterPersons')->middleware('can:list,Modules\People\Entities\Person');
        Route::resource('helpers', 'HelperListController');
    });
});
