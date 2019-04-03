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

Route::group(['middleware' => ['auth', 'language', 'can:operate-library']], function () {
    Route::prefix('library')->name('library.')->group(function(){
        Route::get('lending', 'LendingController@index')->name('lending.index');
        
        Route::get('lending/persons', 'LendingController@persons')->name('lending.persons');
        Route::post('lending/persons/create', 'LendingController@storePerson')->name('lending.storePerson');
        Route::get('lending/person/{person}', 'LendingController@person')->name('lending.person');
        Route::post('lending/person/{person}/lendBook', 'LendingController@lendBookToPerson')->name('lending.lendBookToPerson');
        Route::post('lending/person/{person}/extendBook', 'LendingController@extendBookToPerson')->name('lending.extendBookToPerson');
        Route::post('lending/person/{person}/returnBook', 'LendingController@returnBookFromPerson')->name('lending.returnBookFromPerson');
        Route::get('lending/person/{person}/log', 'LendingController@personLog')->name('lending.personLog');

        Route::get('lending/books', 'LendingController@books')->name('lending.books');
        Route::get('lending/book/{book}', 'LendingController@book')->name('lending.book');
        Route::post('lending/book/{book}/lend', 'LendingController@lendBook')->name('lending.lendBook');
        Route::post('lending/book/{book}/extend', 'LendingController@extendBook')->name('lending.extendBook');
        Route::post('lending/book/{book}/return', 'LendingController@returnBook')->name('lending.returnBook');
        Route::get('lending/book/{book}/log', 'LendingController@bookLog')->name('lending.bookLog');

        Route::get('books/filter', 'BookController@filter')->name('books.filter');
        Route::get('books/findIsbn/{isbn}', 'BookController@findIsbn')->name('books.findIsbn');

        Route::resource('books', 'BookController');

        Route::get('settings', 'LibrarySettingsController@edit')->name('settings.edit')->middleware(['can:configure-library']);
        Route::put('settings', 'LibrarySettingsController@update')->name('settings.update')->middleware(['can:configure-library']);
    });
});