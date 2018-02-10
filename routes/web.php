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

Route::get('/', 'HomeController@index')->name('home');

// Changelog
Route::get('/changelog', 'ChangelogController@index')->name('changelog');

Route::resource('users', 'UserController');
Route::resource('roles', 'RoleController');

Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')->name('userprofile.updatePassword');
Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');

//
// Bank
//
Route::get('/bank', 'BankController@index')->name('bank.index');

Route::get('/bank/withdrawal', 'BankController@withdrawal')->name('bank.withdrawal');
Route::get('/bank/withdrawal/search', 'BankController@withdrawalSearch')->name('bank.withdrawalSearch');
Route::get('/bank/withdrawal/transactions', 'BankController@withdrawalTransactions')->name('bank.withdrawalTransactions');
Route::post('/bank/storeTransaction', 'BankController@storeTransaction')->name('bank.storeTransaction');
Route::post('/bank/giveBoutiqueCoupon', 'BankController@giveBoutiqueCoupon')->name('bank.giveBoutiqueCoupon');
Route::post('/bank/resetBoutiqueCoupon', 'BankController@resetBoutiqueCoupon')->name('bank.resetBoutiqueCoupon');
Route::post('/bank/giveDiapersCoupon', 'BankController@giveDiapersCoupon')->name('bank.giveDiapersCoupon');
Route::post('/bank/resetDiapersCoupon', 'BankController@resetDiapersCoupon')->name('bank.resetDiapersCoupon');
Route::post('/bank/updateGender', 'BankController@updateGender')->name('bank.updateGender');
Route::post('/bank/updateDateOfBirth', 'BankController@updateDateOfBirth')->name('bank.updateDateOfBirth');
Route::post('/bank/registerCard', 'BankController@registerCard')->name('bank.registerCard');

Route::get('/bank/codeCard', 'BankController@prepareCodeCard')->name('bank.prepareCodeCard');
Route::post('/bank/codeCard', 'BankController@createCodeCard')->name('bank.createCodeCard');

Route::get('/bank/maintenance', 'BankController@maintenance')->name('bank.maintenance');
Route::post('/bank/maintenance', 'BankController@updateMaintenance')->name('bank.updateMaintenance');

Route::get('/bank/deposit', 'BankController@deposit')->name('bank.deposit');
Route::post('/bank/deposit', 'BankController@storeDeposit')->name('bank.storeDeposit');

Route::get('/bank/settings', 'BankController@settings')->name('bank.settings');
Route::post('/bank/settings', 'BankController@updateSettings')->name('bank.updateSettings');

Route::get('/bank/export', 'BankController@export')->name('bank.export');
Route::get('/bank/import', 'BankController@import')->name('bank.import');
Route::post('/bank/doImport', 'BankController@doImport')->name('bank.doImport');

//
// People
//
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
Route::resource('/people', 'PeopleController');

Route::group(['middleware' => ['auth']], function () {

    //
    // Reporting
    //
    Route::group(['middleware' => ['can:view-reports']], function () {
        Route::view('/reporting', 'reporting.index')->name('reporting.index');
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

    // Reporting: Bank
    Route::group(['middleware' => ['can:view-bank-reports']], function () {
        Route::get('/reporting/bank/withdrawals', 'Reporting\\BankReportingController@withdrawals')->name('reporting.bank.withdrawals');
        Route::get('/reporting/bank/withdrawals/chart/numTransactions', 'Reporting\\BankReportingController@numTransactions')->name('reporting.bank.numTransactions');
        Route::get('/reporting/bank/withdrawals/chart/sumTransactions', 'Reporting\\BankReportingController@sumTransactions')->name('reporting.bank.sumTransactions');

        Route::get('/reporting/bank/deposits', 'Reporting\\BankReportingController@deposits')->name('reporting.bank.deposits');
        Route::get('/reporting/bank/deposits/chart/stats', 'Reporting\\BankReportingController@depositStats')->name('reporting.bank.depositStats');
        Route::get('/reporting/bank/deposits/chart/stats/{project}', 'Reporting\\BankReportingController@projectDepositStats')->name('reporting.bank.projectDepositStats');
    });

    // Reporting: Logistic articles
    Route::group(['middleware' => ['can:view-kitchen-reports']], function () {    
        Route::get('/reporting/kitchen', function() {
            return redirect()->route('reporting.articles', ['project' => Config::get('reporting.kitchen_project')]);
        })->name('reporting.kitchen');
    });

    Route::get('/reporting/project/{project}/articles', 'Reporting\\ArticleReportingController@articles')->name('reporting.articles');
    Route::get('/reporting/project/articles/{article}', 'Reporting\\ArticleReportingController@article')->name('reporting.article');
    Route::get('/reporting/articles/chart/{article}/transactionsPerDay', 'Reporting\\ArticleReportingController@transactionsPerDay')->name('reporting.articles.transactionsPerDay');
    Route::get('/reporting/articles/chart/{article}/transactionsPerWeek', 'Reporting\\ArticleReportingController@transactionsPerWeek')->name('reporting.articles.transactionsPerWeek');
    Route::get('/reporting/articles/chart/{article}/transactionsPerMonth', 'Reporting\\ArticleReportingController@transactionsPerMonth')->name('reporting.articles.transactionsPerMonth');
    Route::get('/reporting/articles/chart/{article}/avgTransactionsPerWeekDay', 'Reporting\\ArticleReportingController@avgTransactionsPerWeekDay')->name('reporting.articles.avgTransactionsPerWeekDay');
});

// Logistics
Route::group(['middleware' => 'can:use-logistics'], function () {
    Route::get('/logistics', 'LogisticsController@index')->name('logistics.index');

    Route::get('/logistics/projects/{project}/articles', 'ArticleController@index')->name('logistics.articles.index');
    Route::post('/logistics/projects/{project}/articles', 'ArticleController@store')->name('logistics.articles.store');

    Route::get('/logistics/articles/{article}/edit', 'ArticleController@edit')->name('logistics.articles.edit');
    Route::put('/logistics/articles/{article}', 'ArticleController@update')->name('logistics.articles.update');
    Route::delete('/logistics/articles/{article}', 'ArticleController@destroyArticle')->name('logistics.articles.destroyArticle');
});

// Tasks
Route::group(['middleware' => ['auth']], function () {
    // TODO Add authorization: Auth::user()->can('list', Task::class)
    Route::view('/tasks', 'tasks.tasklist')->name('tasks');
});

// Calendar
Route::group(['middleware' => ['auth']], function () {
    Route::get('/calendar', 'CalendarController@index')->name('calendar');
    Route::get('/calendar/data', 'CalendarController@data')->name('calendar.data');
});

Auth::routes();
