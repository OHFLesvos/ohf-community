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

Route::group(['middleware' => ['auth', 'language']], function () {
    // User management
    Route::prefix('admin')->group(function(){
        // Users
        Route::put('users/{user}/disable2FA', 'UserController@disable2FA')->name('users.disable2FA');
        Route::resource('users', 'UserController');

        // Roles
        Route::get('roles/{role}/members', 'RoleController@manageMembers')->name('roles.manageMembers');
        Route::put('roles/{role}/members', 'RoleController@updateMembers')->name('roles.updateMembers');
        Route::resource('roles', 'RoleController');

        // Reporting
        Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {    
            Route::get('reporting/users/permissions', 'UserController@permissions')->name('users.permissions');
            Route::get('reporting/users/sensitiveData', 'UserController@sensitiveDataReport')->name('reporting.privacy');
            Route::get('reporting/roles/permissions', 'RoleController@permissions')->name('roles.permissions');
        });
    });
    
    // User profile
    Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
    Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
    Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')->name('userprofile.updatePassword');
    Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');
    Route::get('/userprofile/2FA', 'UserProfileController@view2FA')->name('userprofile.view2FA');
    Route::post('/userprofile/2FA', 'UserProfileController@store2FA')->name('userprofile.store2FA');
    Route::delete('/userprofile/2FA', 'UserProfileController@disable2FA')->name('userprofile.disable2FA');
});
