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

    Route::view('calendar', 'collaboration::calendar')
        ->name('calendar')
        ->middleware('can:view-calendar');

    Route::view('tasks', 'collaboration::tasklist')
        ->name('tasks')
        ->middleware('can:list,Modules\Collaboration\Entities\Task');

});

Route::group(['middleware' => 'language'], function () {
    Route::prefix('kb')->name('kb.')->group(function(){
        Route::group(['middleware' => ['auth']], function () {
            Route::get('', 'SearchController@index')->name('index');
            Route::get('latest_changes', 'SearchController@latestChanges')->name('latestChanges');

            Route::get('tags', 'TagController@tags')->name('tags');
            Route::get('tags/{tag}/pdf', 'TagController@pdf')->name('tags.pdf');

            Route::get('articles/{article}/pdf', 'ArticleController@pdf')->name('articles.pdf');
            Route::resource('articles', 'ArticleController')->except('show');
        });
        Route::get('tags/{tag}', 'TagController@tag')->name('tag');
        Route::resource('articles', 'ArticleController')->only('show');
    });
});