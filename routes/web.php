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
Route::post('/bank/filter', 'BankController@filter')->name('bank.filter');
Route::post('/bank/resetFilter', 'BankController@resetFilter')->name('bank.resetFilter');
Route::get('/bank/person/{person}', 'BankController@person')->name('bank.person');
Route::get('/bank/maintenance', 'BankController@maintenance')->name('bank.maintenance');
Route::post('/bank/maintenance', 'BankController@updateMaintenance')->name('bank.updateMaintenance');
Route::get('/bank/deposit', 'BankController@deposit')->name('bank.deposit');
Route::post('/bank/deposit', 'BankController@storeDeposit')->name('bank.storeDeposit');
Route::get('/bank/project/{project}', 'BankController@project')->name('bank.project');

Route::get('/bank/stats/numberOfPersonsServedToday', 'BankController@getNumberOfPersonsServedToday')->name('bank.numberOfPersonsServedToday');
Route::get('/bank/stats/transactionValueToday', 'BankController@getTransactionValueToday')->name('bank.transactionValueToday');

Route::get('/bank/settings', 'BankController@settings')->name('bank.settings');
Route::post('/bank/settings', 'BankController@updateSettings')->name('bank.updateSettings');

Route::post('/bank/storeTransaction', 'BankController@storeTransaction')->name('bank.storeTransaction');
Route::post('/bank/giveBoutiqueCoupon', 'BankController@giveBoutiqueCoupon')->name('bank.giveBoutiqueCoupon');

Route::get('/bank/export', 'BankController@export')->name('bank.export');
Route::get('/bank/import', 'BankController@import')->name('bank.import');
Route::post('/bank/doImport', 'BankController@doImport')->name('bank.doImport');

Route::get('/people/charts', 'PeopleController@charts')->name('people.charts');
Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
Route::get('/people/export', 'PeopleController@export')->name('people.export');
Route::get('/people/import', 'PeopleController@import')->name('people.import');
Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
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

Route::get('/tasks', 'TasksController@index')->name('tasks.index');
Route::post('/tasks', 'TasksController@store')->name('tasks.store');
Route::get('/tasks/setDone/{task}', 'TasksController@setDone')->name('tasks.setDone');
Route::get('/tasks/setUndone/{task}', 'TasksController@setUndone')->name('tasks.setUndone');
Route::post('/tasks/{task}/update', 'TasksController@update')->name('tasks.update');
Route::get('/tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
Route::delete('/tasks/{task}/destroy', 'TasksController@destroy')->name('tasks.destroy');

Auth::routes();
