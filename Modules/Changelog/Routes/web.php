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

Route::group(['middleware' => ['language', 'auth', 'can:view-changelogs']], function () {
    Route::get('changelog', 'ChangelogController@index')->name('changelog');
});
