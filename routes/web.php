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

Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');

Route::get('/bank', 'BankController@index')->name('bank.index');
Route::get('/bank/charts', 'BankController@charts')->name('bank.charts');
Route::post('/bank/filter', 'BankController@filter')->name('bank.filter');
Route::get('/bank/person/{person}', 'BankController@person')->name('bank.person');

Route::get('/bank/settings', 'BankController@settings')->name('bank.settings');
Route::post('/bank/settings', 'BankController@updateSettings')->name('bank.updateSettings');

Route::get('/bank/register', 'BankController@register')->name('bank.register');
Route::post('/bank/person', 'BankController@store')->name('bank.store');

Route::get('/bank/editPerson/{person}', 'BankController@editPerson')->name('bank.editPerson');
Route::post('/bank/updatePerson/{person}', 'BankController@updatePerson')->name('bank.updatePerson');

Route::get('/bank/transactions/{person}', 'BankController@transactions')->name('bank.transactions');
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
Route::resource('/people', 'PeopleController', ['except' => 'show']);

Route::get('/tasks', 'TasksController@index')->name('tasks.index');
Route::post('/tasks', 'TasksController@store')->name('tasks.store');
Route::get('/tasks/setDone/{task}', 'TasksController@setDone')->name('tasks.setDone');
Route::get('/tasks/setUndone/{task}', 'TasksController@setUndone')->name('tasks.setUndone');
Route::post('/tasks/{task}/update', 'TasksController@update')->name('tasks.update');
Route::get('/tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
Route::get('/tasks/{task}/delete', 'TasksController@delete')->name('tasks.delete');

Auth::routes();
