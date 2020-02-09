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

Route::middleware('language')->group(function () {

    Route::middleware('auth')->group(function () {

        // Home (Dashboard)
        Route::get('/', 'HomeController@index')->name('home');

        // Reporting
        Route::view('reporting', 'reporting.index')->name('reporting.index')->middleware('can:view-reports');

    });

    // Authentication
    Auth::routes();

    $socialite_drivers = config('auth.socialite.drivers');
    Route::get('login/{driver}/redirect', 'Auth\LoginController@redirectToProvider')
        ->name('login.provider')
        ->where('driver', implode('|', $socialite_drivers));
    Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback')
        ->name('login.callback')
        ->where('driver', implode('|', $socialite_drivers));

    // Privacy policy
    Route::get('userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});

//
// User management
//
Route::middleware(['auth', 'language'])
    ->namespace('UserManagement')
    ->group(function () {

        // User management
        Route::prefix('admin')->group(function(){
            // Users
            Route::put('users/{user}/disable2FA', 'UserController@disable2FA')
                ->name('users.disable2FA');
            Route::put('users/{user}/disableOAuth', 'UserController@disableOAuth')
                ->name('users.disableOAuth');
            Route::resource('users', 'UserController');

            // Roles
            Route::get('roles/{role}/members', 'RoleController@manageMembers')
                ->name('roles.manageMembers');
            Route::put('roles/{role}/members', 'RoleController@updateMembers')
                ->name('roles.updateMembers');
            Route::resource('roles', 'RoleController');

            // Reporting
            Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {
                Route::get('reporting/users/permissions', 'UserController@permissions')
                    ->name('users.permissions');
                Route::get('reporting/users/sensitiveData', 'UserController@sensitiveDataReport')
                    ->name('reporting.privacy');
                Route::get('reporting/roles/permissions', 'RoleController@permissions')
                    ->name('roles.permissions');
            });
        });

        // User profile
        Route::get('/userprofile', 'UserProfileController@index')
            ->name('userprofile');
        Route::post('/userprofile', 'UserProfileController@update')
            ->name('userprofile.update');
        Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')
            ->name('userprofile.updatePassword');
        Route::delete('/userprofile', 'UserProfileController@delete')
            ->name('userprofile.delete');
        Route::get('/userprofile/2FA', 'UserProfileController@view2FA')
            ->name('userprofile.view2FA');
        Route::post('/userprofile/2FA', 'UserProfileController@store2FA')
            ->name('userprofile.store2FA');
        Route::delete('/userprofile/2FA', 'UserProfileController@disable2FA')
            ->name('userprofile.disable2FA');
    });

//
// Changelog
//

Route::middleware(['language', 'auth', 'can:view-changelogs'])
    ->namespace('Changelog')
    ->group(function () {
        Route::get('changelog', 'ChangelogController@index')
            ->name('changelog');
    });

//
// Logviewer
//
Route::middleware(['language', 'auth', 'can:view-logs'])
    ->namespace('Logviewer')
    ->group(function () {
        Route::get('logviewer', 'LogViewerController@index')
            ->name('logviewer.index');
    });

//
// Badges
//
Route::middleware(['language', 'auth'])
    ->namespace('Badges')
    ->name('badges.')
    ->prefix('badges')
    ->group(function () {
        Route::middleware(['can:create-badges'])
            ->group(function(){
                Route::get('/', 'BadgeMakerController@index')
                    ->name('index');
                Route::post('/selection', 'BadgeMakerController@selection')
                    ->name('selection');
                Route::post('/make', 'BadgeMakerController@make')
                    ->name('make');
            });
    });

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->namespace('Fundraising')
    ->prefix('fundraising')
    ->name('fundraising.')
    ->group(function () {
        // Donors
        Route::name('donors.export')
            ->get('donors/export', 'DonorController@export');
        Route::name('donors.vcard')
            ->get('donors/{donor}/vcard', 'DonorController@vcard');
        Route::resource('donors', 'DonorController');

        // Donations
        Route::name('donations.index')
            ->get('donations', 'DonationController@index');
        Route::name('donations.import')
            ->get('donations/import', 'DonationController@import');
        Route::name('donations.export')
            ->get('donations/export', 'DonationController@export');
        Route::name('donations.doImport')
            ->post('donations/import', 'DonationController@doImport');
        Route::prefix('donors/{donor}')
            ->group(function () {
                Route::name('donations.exportDonor')
                    ->get('export', 'DonationController@exportDonor');
                Route::resource('donations', 'DonationController')
                    ->except('show', 'index');
            });
    });
