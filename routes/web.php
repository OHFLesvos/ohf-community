<?php

use App\Http\Controllers\Accounting\MoneyTransactionsController;
use App\Http\Controllers\Accounting\SummaryController;
use App\Http\Controllers\Accounting\WalletController;
use App\Http\Controllers\Accounting\WeblingApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Badges\BadgeMakerController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\Collaboration\ArticleController;
use App\Http\Controllers\Collaboration\SearchController;
use App\Http\Controllers\Collaboration\TagController;
use App\Http\Controllers\CommunityVolunteers\ImportExportController as CommunityVolunteersImportExportController;
use App\Http\Controllers\CommunityVolunteers\ListController;
use App\Http\Controllers\CommunityVolunteers\ResponsibilitiesController;
use App\Http\Controllers\Fundraising\FundraisingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrivacyPolicy;
use App\Http\Controllers\Reports\ReportsController;
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
        Route::get('/', [HomeController::class, 'index'])
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
    Route::get('settings', [SettingsController::class, 'edit'])
        ->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])
        ->name('settings.update');
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
Route::middleware(['language', 'auth'])
    ->name('badges.')
    ->prefix('badges')
    ->group(function () {
        Route::middleware(['can:create-badges'])
            ->group(function () {
                Route::get('/', [BadgeMakerController::class, 'index'])
                    ->name('index');
                Route::post('selection', [BadgeMakerController::class, 'selection'])
                    ->name('selection');
                Route::post('make', [BadgeMakerController::class, 'make'])
                    ->name('make');
            });
    });

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->prefix('fundraising')
    ->name('fundraising.')
    ->group(function () {
        // SPA
        Route::get('', [FundraisingController::class, 'index'])
            ->name('index');
        Route::get('/{any}', [FundraisingController::class, 'index'])
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
        Route::get('', [WalletController::class, 'index'])
            ->name('index');
        Route::get('transactions/summary', [SummaryController::class, 'index'])
            ->name('transactions.summary');

        // Transactions
        Route::get('wallets/{wallet}/transactions/export', [MoneyTransactionsController::class, 'export'])
            ->name('transactions.export');
        Route::post('wallets/{wallet}/transactions/doExport', [MoneyTransactionsController::class, 'doExport'])
            ->name('transactions.doExport');
        Route::get('transactions/{transaction}/snippet', [MoneyTransactionsController::class, 'snippet'])
            ->name('transactions.snippet');
        Route::put('transactions/{transaction}/undoBooking', [MoneyTransactionsController::class, 'undoBooking'])
            ->name('transactions.undoBooking');
        Route::get('wallets/{wallet}/transactions', [MoneyTransactionsController::class, 'index'])
            ->name('transactions.index');
        Route::get('wallets/{wallet}/transactions/create', [MoneyTransactionsController::class, 'create'])
            ->name('transactions.create');
        Route::post('wallets/{wallet}/transactions', [MoneyTransactionsController::class, 'store'])
            ->name('transactions.store');
        Route::get('transactions/{transaction}', [MoneyTransactionsController::class, 'show'])
            ->name('transactions.show');
        Route::get('transactions/{transaction}/edit', [MoneyTransactionsController::class, 'edit'])
            ->name('transactions.edit');
        Route::put('transactions/{transaction}', [MoneyTransactionsController::class, 'update'])
            ->name('transactions.update');
        Route::delete('transactions/{transaction}', [MoneyTransactionsController::class, 'destroy'])
            ->name('transactions.destroy');

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
        Route::view('wallets', 'accounting.wallets')
            ->name('wallets');
        Route::view('wallets/{any}', 'accounting.wallets')
            ->where('any', '.*')
            ->name('wallets.any');

        // Categories
        Route::view('categories', 'accounting.categories')
            ->name('categories');
        Route::view('categories/{any}', 'accounting.categories')
            ->where('any', '.*')
            ->name('categories.any');

        // Projects
        Route::view('projects', 'accounting.projects')
            ->name('projects');
        Route::view('projects/{any}', 'accounting.projects')
            ->where('any', '.*')
            ->name('projects.any');

        // Suppliers
        Route::view('suppliers', 'accounting.suppliers')
            ->name('suppliers');
        Route::view('suppliers/{supplier}', 'accounting.suppliers')
            ->name('suppliers.show');
        Route::view('suppliers/{any}', 'accounting.suppliers')
            ->where('any', '.*')
            ->name('suppliers.any');
    });

//
// Collaboration
//

Route::middleware(['language'])
    ->prefix('kb')
    ->name('kb.')
    ->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('', [SearchController::class, 'index'])
                ->name('index');
            Route::get('latest_changes', [SearchController::class, 'latestChanges'])
                ->name('latestChanges');

            Route::get('tags', [TagController::class, 'tags'])
                ->name('tags');
            Route::get('tags/{tag}/pdf', [TagController::class, 'pdf'])
                ->name('tags.pdf');

            Route::get('articles/{article}/pdf', [ArticleController::class, 'pdf'])
                ->name('articles.pdf');
            Route::resource('articles', ArticleController::class)
                ->except('show');
        });
        Route::get('tags/{tag}', [TagController::class, 'tag'])
            ->name('tag');
        Route::resource('articles', ArticleController::class)
            ->only('show');
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
                Route::view('overview', 'cmtyvol.overview')
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
        Route::view('', 'visitors.index')
            ->name('index');
        Route::view('/{any}', 'visitors.index')
            ->where('any', '.*')
            ->name('any');
    });

// Reports
Route::prefix('reports')
    ->name('reports.')
    ->middleware(['auth', 'language'])
    ->group(function () {

        // Reports overview
        Route::get('', [ReportsController::class, 'index'])
            ->name('index')
            ->middleware('can:view-reports');


        // Reports: Community volunteers
        Route::prefix('cmtyvol')
            ->name('cmtyvol.')
            ->middleware('can:view-community-volunteer-reports')
            ->group(function () {
                Route::view('report', 'reports.cmtyvol.report')
                    ->name('report');
            });

        // Reports: Visitors
        Route::prefix('visitors')
            ->name('visitors.')
            ->middleware('can:register-visitors')
            ->group(function () {
                Route::view('checkins', 'reports.visitors.checkins')
                    ->name('checkins');
            });

        // Reports: Fundraising
        Route::prefix('fundraising')
            ->name('fundraising.')
            ->middleware('can:view-fundraising-reports')
            ->group(function () {
                Route::view('donations', 'reports.fundraising.donations')
                    ->name('donations');
            });
    });

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
