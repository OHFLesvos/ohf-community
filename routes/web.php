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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/bank', 'BankController@index')->name('bank.index');
Route::get('/bank/register', 'BankController@register')->name('bank.register');
Route::post('/bank/person', 'BankController@store')->name('bank.store');
Route::get('/bank/export', 'BankController@export')->name('bank.export');
Route::get('/bank/person/{person}', 'BankController@person')->name('bank.person');
Route::get('/bank/editPerson/{person}', 'BankController@editPerson')->name('bank.editPerson');
Route::post('/bank/updatePerson/{person}', 'BankController@updatePerson')->name('bank.updatePerson');
Route::get('/bank/transactions/{person}', 'BankController@transactions')->name('bank.transactions');
Route::post('/bank/filter', 'BankController@filter')->name('bank.filter');
Route::post('/bank/storeTransaction', 'BankController@storeTransaction')->name('bank.storeTransaction');

// temporary
Route::get('/bank/filter', 'BankController@filter');

