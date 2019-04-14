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

Route::group(['middleware' => 'language'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('kb')->name('kb.')->group(function(){
            Route::get('', 'SearchController@index')->name('index');
            Route::get('latest_changes', 'SearchController@latestChanges')->name('latestChanges');

            Route::get('tags', 'TagController@tags')->name('tags');
            Route::get('tags/{tag}', 'TagController@tag')->name('tag');

            Route::resource('articles', 'ArticleController');
        });
    });
});
