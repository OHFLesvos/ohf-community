<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['language', 'auth']], function () {
    Route::prefix('school')->name('school.')->namespace('API')->group(function () {
        Route::post('classes/{class}/students', 'SchoolClassStudentsController@addStudentToClass')->name('classes.students.add');
    });
});
