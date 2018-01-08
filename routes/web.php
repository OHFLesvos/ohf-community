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
Route::post('/bank/filter', 'BankController@filter')->name('bank.filter');
Route::post('/bank/resetFilter', 'BankController@resetFilter')->name('bank.resetFilter');
Route::get('/bank/person/{person}', 'BankController@person')->name('bank.person');
Route::get('/bank/todayStats', 'BankController@todayStats')->name('bank.todayStats');

Route::get('/bank/maintenance', 'BankController@maintenance')->name('bank.maintenance');
Route::post('/bank/maintenance', 'BankController@updateMaintenance')->name('bank.updateMaintenance');

Route::get('/bank/deposit', 'BankController@deposit')->name('bank.deposit');
Route::post('/bank/deposit', 'BankController@storeDeposit')->name('bank.storeDeposit');

Route::get('/bank/deposit/stats', 'BankController@depositStats')->name('bank.depositStats');
Route::get('/bank/deposit/stats/{project}', 'BankController@projectDepositStats')->name('bank.projectDepositStats');


Route::get('/bank/settings', 'BankController@settings')->name('bank.settings');
Route::post('/bank/settings', 'BankController@updateSettings')->name('bank.updateSettings');

Route::post('/bank/storeTransaction', 'BankController@storeTransaction')->name('bank.storeTransaction');
Route::post('/bank/giveBoutiqueCoupon', 'BankController@giveBoutiqueCoupon')->name('bank.giveBoutiqueCoupon');
Route::post('/bank/giveDiapersCoupon', 'BankController@giveDiapersCoupon')->name('bank.giveDiapersCoupon');
Route::post('/bank/updateGender', 'BankController@updateGender')->name('bank.updateGender');

Route::get('/bank/export', 'BankController@export')->name('bank.export');
Route::get('/bank/import', 'BankController@import')->name('bank.import');
Route::post('/bank/doImport', 'BankController@doImport')->name('bank.doImport');

Route::get('/people/charts', 'PeopleController@charts')->name('people.charts');
Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
Route::get('/people/export', 'PeopleController@export')->name('people.export');
Route::get('/people/import', 'PeopleController@import')->name('people.import');
Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode');
Route::resource('/people', 'PeopleController');

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
