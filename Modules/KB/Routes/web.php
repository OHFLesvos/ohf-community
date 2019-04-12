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
            Route::get('articles/_latest_changes', 'ArticleController@latestChanges')->name('articles.latestChanges');
            Route::resource('articles', 'ArticleController');
            Route::get('articles/tag/{tag}', 'ArticleController@tag')->name('articles.tag');
        });
    });
});
