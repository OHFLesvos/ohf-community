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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::post('/bank/updateGender', 'API\People\PeopleController@updateGender')->name('bank.updateGender');
    Route::post('/bank/updateDateOfBirth', 'API\People\PeopleController@updateDateOfBirth')->name('bank.updateDateOfBirth');
    Route::post('/bank/updateNationality', 'API\People\PeopleController@updateNationality')->name('bank.updateNationality');
});
