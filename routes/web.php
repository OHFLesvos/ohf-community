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

Route::resource('users', 'UserController');
Route::resource('roles', 'RoleController');

Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')->name('userprofile.updatePassword');
Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');

Route::get('/bank', 'BankController@index')->name('bank.index');
Route::get('/bank/charts', 'BankController@charts')->name('bank.charts');
Route::get('/bank/charts/numTransactions', 'BankController@numTransactions')->name('bank.numTransactions');
Route::get('/bank/charts/sumTransactions', 'BankController@sumTransactions')->name('bank.sumTransactions');

Route::get('/bank/withdrawal', 'BankController@withdrawal')->name('bank.withdrawal');
Route::get('/bank/withdrawal/search', 'BankController@withdrawalSearch')->name('bank.withdrawalSearch');
Route::get('/bank/withdrawal/transactions', 'BankController@withdrawalTransactions')->name('bank.withdrawalTransactions');
Route::post('/bank/storeTransaction', 'BankController@storeTransaction')->name('bank.storeTransaction');
Route::post('/bank/giveBoutiqueCoupon', 'BankController@giveBoutiqueCoupon')->name('bank.giveBoutiqueCoupon');
Route::post('/bank/giveDiapersCoupon', 'BankController@giveDiapersCoupon')->name('bank.giveDiapersCoupon');
Route::post('/bank/updateGender', 'BankController@updateGender')->name('bank.updateGender');
Route::post('/bank/registerCard', 'BankController@registerCard')->name('bank.registerCard');

Route::get('/bank/codeCard', 'BankController@codeCard')->name('bank.codeCard');

Route::get('/bank/maintenance', 'BankController@maintenance')->name('bank.maintenance');
Route::post('/bank/maintenance', 'BankController@updateMaintenance')->name('bank.updateMaintenance');

Route::get('/bank/deposit', 'BankController@deposit')->name('bank.deposit');
Route::post('/bank/deposit', 'BankController@storeDeposit')->name('bank.storeDeposit');

Route::get('/bank/deposit/stats', 'BankController@depositStats')->name('bank.depositStats');
Route::get('/bank/deposit/stats/{project}', 'BankController@projectDepositStats')->name('bank.projectDepositStats');

Route::get('/bank/settings', 'BankController@settings')->name('bank.settings');
Route::post('/bank/settings', 'BankController@updateSettings')->name('bank.updateSettings');

Route::get('/bank/export', 'BankController@export')->name('bank.export');
Route::get('/bank/import', 'BankController@import')->name('bank.import');
Route::post('/bank/doImport', 'BankController@doImport')->name('bank.doImport');

Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
Route::get('/people/export', 'PeopleController@export')->name('people.export');
Route::get('/people/import', 'PeopleController@import')->name('people.import');
Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode');
Route::resource('/people', 'PeopleController');

//
// Reporting
//
Route::view('/reporting', 'reporting.index')->name('reporting.index');

// Reporting: People
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

Route::group(['middleware' => 'can:use-logistics'], function () {
    Route::get('/logistics', 'LogisticsController@index')->name('logistics.index');

    Route::get('/logistics/projects/{project}/articles', 'ArticleController@index')->name('logistics.articles.index');
    Route::post('/logistics/projects/{project}/articles', 'ArticleController@store')->name('logistics.articles.store');

    Route::get('/logistics/articles/{article}', 'ArticleController@show')->name('logistics.articles.show');
    Route::get('/logistics/articles/{article}/edit', 'ArticleController@edit')->name('logistics.articles.edit');
    Route::put('/logistics/articles/{article}', 'ArticleController@update')->name('logistics.articles.update');
    Route::delete('/logistics/articles/{article}', 'ArticleController@destroyArticle')->name('logistics.articles.destroyArticle');
    Route::get('/logistics/articles/{article}/transactionsPerDay', 'ArticleController@transactionsPerDay')->name('logistics.articles.transactionsPerDay');
    Route::get('/logistics/articles/{article}/avgTransactionsPerWeekDay', 'ArticleController@avgTransactionsPerWeekDay')->name('logistics.articles.avgTransactionsPerWeekDay');
});

// Tasks
Route::group(['middleware' => ['auth']], function () {
    // TODO Add authorization: Auth::user()->can('list', Task::class)
    Route::view('/tasks', 'tasks.tasklist')->name('tasks');
});

Auth::routes();
