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
    Route::prefix('school')->name('school.')->group(function () {
        // Route::get('classes.export', 'SchoolClassesController@export')->name('classes.export');
        Route::resource('classes', 'SchoolClassesController')->except('show');
        Route::get('classes/{class}/students', 'SchoolClassStudentsController@index')->name('classes.students.index');
    });
});
