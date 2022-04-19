<?php

use App\Http\Controllers\Accounting\WeblingApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Badges\BadgeMakerController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\CommunityVolunteers\ImportExportController as CommunityVolunteersImportExportController;
use App\Http\Controllers\CommunityVolunteers\ListController;
use App\Http\Controllers\CommunityVolunteers\ResponsibilitiesController;
use App\Http\Controllers\PrivacyPolicy;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\UserProfileController;
use Illuminate\Support\Facades\Route;

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
        Route::view('', 'vue-app')
            ->name('home');
    });

    // Authentication
    Auth::routes();

    $socialite_drivers = config('auth.socialite.drivers');
    Route::get('login/{driver}/redirect', [LoginController::class, 'redirectToProvider'])
        ->name('login.provider')
        ->where('driver', implode('|', $socialite_drivers));
    Route::get('login/{driver}/callback', [LoginController::class, 'handleProviderCallback'])
        ->name('login.callback')
        ->where('driver', implode('|', $socialite_drivers));

    // Privacy policy
    Route::get('userPrivacyPolicy', [PrivacyPolicy::class, 'userPolicy'])
        ->name('userPrivacyPolicy');

    // Settings
    Route::middleware('auth')->group(function () {
        Route::get('settings', [SettingsController::class, 'edit'])
            ->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])
            ->name('settings.update');
    });
});

//
// User management
//
Route::middleware(['auth', 'language'])
    ->group(function () {

        // User management
        Route::prefix('admin')
            ->group(function () {

                // Users
                Route::get('users/permissions', [UserController::class, 'permissions'])
                    ->name('users.permissions')
                    ->middleware('can:viewAny,App\Models\User');
                Route::put('users/{user}/disable2FA', [UserController::class, 'disable2FA'])
                    ->name('users.disable2FA');
                Route::put('users/{user}/disableOAuth', [UserController::class, 'disableOAuth'])
                    ->name('users.disableOAuth');
                Route::resource('users', UserController::class);

                // Roles
                Route::get('roles/permissions', [RoleController::class, 'permissions'])
                    ->name('roles.permissions')
                    ->middleware('can:viewAny,App\Models\Role');
                Route::get('roles/{role}/members', [RoleController::class, 'manageMembers'])
                    ->name('roles.manageMembers');
                Route::put('roles/{role}/members', [RoleController::class, 'updateMembers'])
                    ->name('roles.updateMembers');
                Route::resource('roles', RoleController::class);
            });

        // User profile
        Route::get('userprofile', [UserProfileController::class, 'index'])
            ->name('userprofile');
        Route::post('userprofile', [UserProfileController::class, 'update'])
            ->name('userprofile.update');
        Route::post('userprofile/updatePassword', [UserProfileController::class, 'updatePassword'])
            ->name('userprofile.updatePassword');
        Route::delete('userprofile', [UserProfileController::class, 'delete'])
            ->name('userprofile.delete');
        Route::get('userprofile/2FA', [UserProfileController::class, 'view2FA'])
            ->name('userprofile.view2FA');
        Route::post('userprofile/2FA', [UserProfileController::class, 'store2FA'])
            ->name('userprofile.store2FA');
        Route::delete('userprofile/2FA', [UserProfileController::class, 'disable2FA'])
            ->name('userprofile.disable2FA');
    });

Route::get('users/{user}/avatar', [UserController::class, 'avatar'])
    ->name('users.avatar');

//
// Changelog
//

Route::middleware(['language'])
    ->group(function () {
        Route::get('changelog', [ChangelogController::class, 'index'])
            ->name('changelog');
    });

//
// Badges
//
Route::middleware(['language', 'auth', 'can:create-badges'])
    ->name('badges.')
    ->prefix('badges')
    ->group(function () {
        Route::view('', 'vue-app')
            ->name('index');
    });

//
// Fundraising
//

Route::middleware(['language', 'auth', 'can:view-fundraising'])
    ->prefix('fundraising')
    ->name('fundraising.')
    ->group(function () {
        // SPA
        Route::view('', 'vue-app')
            ->name('index');
        Route::view('/{any}', 'vue-app')
            ->where('any', '.*');
    });

//
// Accounting
//

Route::middleware(['language', 'auth'])
    ->prefix('accounting')
    ->name('accounting.')
    ->group(function () {

        // Overview
        Route::view('', 'vue-app')
            ->name('index');

        Route::view('transactions/summary', 'vue-app')
            ->name('transactions.summary');

        // Transactions
        Route::view('transactions', 'vue-app')
            ->name('transactions.index');
        Route::view('transactions/create', 'vue-app')
            ->name('transactions.create');
        Route::view('transactions/{transaction}', 'vue-app')
            ->name('transactions.show');
        Route::view('transactions/{transaction}/edit', 'vue-app')
            ->name('transactions.edit');

        // Webling
        Route::get('wallets/{wallet}/webling', [WeblingApiController::class, 'index'])
            ->name('webling.index');
        Route::get('wallets/{wallet}/webling/prepare', [WeblingApiController::class, 'prepare'])
            ->name('webling.prepare');
        Route::post('wallets/{wallet}/webling', [WeblingApiController::class, 'store'])
            ->name('webling.store');
        Route::get('wallets/{wallet}/webling/sync', [WeblingApiController::class, 'sync'])
            ->name('webling.sync');

        // Wallets
        Route::view('wallets', 'vue-app')
            ->name('wallets');
        Route::view('wallets/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('wallets.any');

        // Categories
        Route::view('categories', 'vue-app')
            ->name('categories');
        Route::view('categories/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('categories.any');

        // Projects
        Route::view('projects', 'vue-app')
            ->name('projects');
        Route::view('projects/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('projects.any');

        // Suppliers
        Route::view('suppliers', 'vue-app')
            ->name('suppliers');
        Route::view('suppliers/{supplier}', 'vue-app')
            ->name('suppliers.show');
        Route::view('suppliers/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('suppliers.any');

        // Budgets
        Route::view('budgets', 'vue-app')
            ->name('budgets');
        Route::view('budgets/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('budgets.any');
    });

//
// Community volunteers
//

Route::middleware(['auth', 'language'])
    ->group(function () {
        Route::redirect('helpers', 'cmtyvol');
        Route::name('cmtyvol.')
            ->prefix('cmtyvol')
            ->group(function () {

                // Overview
                Route::view('overview', 'vue-app')
                    ->name('overview')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');

                // Import & Export view
                Route::get('import-export', [CommunityVolunteersImportExportController::class, 'index'])
                    ->name('import-export');

                // Export download
                Route::post('doExport', [CommunityVolunteersImportExportController::class, 'doExport'])
                    ->name('doExport')
                    ->middleware('can:export,App\Models\CommunityVolunteers\CommunityVolunteer');

                // Import upload
                Route::post('doImport', [CommunityVolunteersImportExportController::class, 'doImport'])
                    ->name('doImport')
                    ->middleware('can:import,App\Models\CommunityVolunteers\CommunityVolunteer');

                // Download vCard
                Route::get('{cmtyvol}/vcard', [CommunityVolunteersImportExportController::class, 'vcard'])
                    ->name('vcard');

                // Responsibilities
                Route::get('{cmtyvol}/responsibilities', [ListController::class, 'responsibilities'])
                    ->name('responsibilities');
                Route::put('{cmtyvol}/responsibilities', [ListController::class, 'updateResponsibilities'])
                    ->name('updateResponsibilities');

                // Responsibilities resource
                Route::resource('responsibilities', ResponsibilitiesController::class)
                    ->except('show');
            });

        // Community volunteers resource
        Route::resource('cmtyvol', ListController::class);
    });

//
// Visitors
//
Route::middleware(['auth', 'language'])
    ->prefix('visitors')
    ->name('visitors.')
    ->group(function () {
        // TODO authorization
        Route::view('', 'vue-app')
            ->name('index');
        Route::view('/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('any');
    });

// Reports
Route::prefix('reports')
    ->name('reports.')
    ->middleware(['auth', 'language'])
    ->group(function () {

        // Reports overview
        Route::view('', 'vue-app')
            ->name('index')
            ->middleware('can:view-reports');

        // Reports: Community volunteers
        Route::prefix('cmtyvol')
            ->name('cmtyvol.')
            ->middleware('can:view-community-volunteer-reports')
            ->group(function () {
                Route::view('report', 'vue-app')
                    ->name('report');
            });

        // Reports: Visitors
        Route::prefix('visitors')
            ->name('visitors.')
            ->middleware('can:register-visitors')
            ->group(function () {
                Route::view('checkins', 'vue-app')
                    ->name('checkins');
            });

        // Reports: Fundraising
        Route::prefix('fundraising')
            ->name('fundraising.')
            ->middleware('can:view-fundraising-reports')
            ->group(function () {
                Route::view('donations', 'vue-app')
                    ->name('donations');
            });
    });
