<?php

use App\Http\Controllers\Accounting\API\BudgetController;
use App\Http\Controllers\Accounting\API\CategoriesController;
use App\Http\Controllers\Accounting\API\ControllingController;
use App\Http\Controllers\Accounting\API\ExportController;
use App\Http\Controllers\Accounting\API\TransactionsController;
use App\Http\Controllers\Accounting\API\ProjectsController;
use App\Http\Controllers\Accounting\API\SummaryController;
use App\Http\Controllers\Accounting\API\SuppliersController;
use App\Http\Controllers\Accounting\API\WalletsController;
use App\Http\Controllers\API\CommentsController;
use App\Http\Controllers\API\DataListController;
use App\Http\Controllers\Collaboration\API\ArticleController;
use App\Http\Controllers\CommunityVolunteers\API\CommunityVolunteerCommentsController;
use App\Http\Controllers\CommunityVolunteers\API\CommunityVolunteerController;
use App\Http\Controllers\CommunityVolunteers\API\ReportController as CommunityVolunteersReportController;
use App\Http\Controllers\CommunityVolunteers\ImportExportController;
use App\Http\Controllers\Fundraising\API\DonationController;
use App\Http\Controllers\Fundraising\API\DonorCommentsController;
use App\Http\Controllers\Fundraising\API\DonorController;
use App\Http\Controllers\Fundraising\API\DonorDonationsController;
use App\Http\Controllers\Fundraising\API\DonorTagsController;
use App\Http\Controllers\Fundraising\API\ReportController;
use App\Http\Controllers\Fundraising\API\TagsController;
use App\Http\Controllers\Fundraising\API\WebhookController;
use App\Http\Controllers\Settings\API\SettingsController;
use App\Http\Controllers\UserManagement\API\RoleAdministratorRelationshipController;
use App\Http\Controllers\UserManagement\API\RoleController;
use App\Http\Controllers\UserManagement\API\RoleUserRelationshipController;
use App\Http\Controllers\UserManagement\API\UserController;
use App\Http\Controllers\UserManagement\API\UserRoleRelationshipController;
use App\Http\Controllers\Visitors\API\VisitorController;
use Illuminate\Support\Facades\Route;

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

//
// User management
//
Route::middleware(['auth', 'language'])
    ->name('api.')
    ->group(function () {
        Route::apiResource('users', UserController::class);
        Route::prefix('users/{user}')
            ->name('users.')
            ->group(function () {
                Route::get('roles', [UserController::class, 'roles'])
                    ->name('roles.index');
                Route::prefix('relationships')
                    ->name('relationships.')
                    ->group(function () {
                        Route::prefix('roles')
                            ->name('roles.')
                            ->group(function () {
                                Route::get('', [UserRoleRelationshipController::class, 'index'])
                                    ->name('index');
                                Route::post('', [UserRoleRelationshipController::class, 'store'])
                                    ->name('store');
                                Route::put('', [UserRoleRelationshipController::class, 'update'])
                                    ->name('update');
                                Route::delete('', [UserRoleRelationshipController::class, 'destroy'])
                                    ->name('destroy');
                            });
                    });
            });

        Route::apiResource('roles', RoleController::class);
        Route::prefix('roles/{role}')
            ->name('roles.')
            ->group(function () {
                Route::get('users', [RoleController::class, 'users'])
                    ->name('users.index');
                Route::get('administrators', [RoleController::class, 'administrators'])
                    ->name('administrators.index');
                Route::prefix('relationships')
                    ->name('relationships.')
                    ->group(function () {
                        Route::prefix('users')
                            ->name('users.')
                            ->group(function () {
                                Route::get('', [RoleUserRelationshipController::class, 'index'])
                                    ->name('index');
                                Route::post('', [RoleUserRelationshipController::class, 'store'])
                                    ->name('store');
                                Route::put('', [RoleUserRelationshipController::class, 'update'])
                                    ->name('update');
                                Route::delete('', [RoleUserRelationshipController::class, 'destroy'])
                                    ->name('destroy');
                            });
                        Route::prefix('administrators')
                            ->name('administrators.')
                            ->group(function () {
                                Route::get('', [RoleAdministratorRelationshipController::class, 'index'])
                                    ->name('index');
                                Route::post('', [RoleAdministratorRelationshipController::class, 'store'])
                                    ->name('store');
                                Route::put('', [RoleAdministratorRelationshipController::class, 'update'])
                                    ->name('update');
                                Route::delete('', [RoleAdministratorRelationshipController::class, 'destroy'])
                                    ->name('destroy');
                            });
                    });
            });
    });

//
// Fundraising
//

Route::middleware(['language', 'auth'])
    ->prefix('fundraising')
    ->name('api.fundraising.')
    ->group(function () {

        // Donor
        Route::get('donors/export', [DonorController::class, 'export'])
            ->name('donors.export');
        Route::get('donors/salutations', [DonorController::class, 'salutations'])
            ->name('donors.salutations');
        Route::apiResource('donors', DonorController::class);
        Route::get('donors/{donor}/vcard', [DonorController::class, 'vcard'])
            ->name('donors.vcard');

        // Donor's donations
        Route::apiResource('donors.donations', DonorDonationsController::class)
            ->only('index', 'store');
        Route::get('donors/{donor}/donations/export', [DonorDonationsController::class, 'export'])
            ->name('donors.donations.export');

        // Budgets
        Route::get('donors/{donor}/budgets', [DonorController::class, 'budgets'])
            ->name('donors.budgets');

        // Donor's comments
        Route::apiResource('donors.comments', DonorCommentsController::class)
            ->only('index', 'store');

        // Donor's tags
        Route::apiResource('donors.tags', DonorTagsController::class)
            ->only('index', 'store');
        Route::apiResource('tags', TagsController::class)
            ->only('index');

        // Donations
        Route::get('donations/channels', [DonationController::class, 'channels'])
            ->name('donations.channels');
        Route::get('donations/currencies', [DonationController::class, 'currencies'])
            ->name('donations.currencies');
        Route::get('donations/export', [DonationController::class, 'export'])
            ->name('donations.export');
        Route::post('donations/import', [DonationController::class, 'import'])
            ->name('donations.import');
        Route::apiResource('donations', DonationController::class);

        // Report
        Route::prefix('report')
            ->name('report.')
            ->group(function () {
                Route::get('donors/count', [ReportController::class, 'count'])
                    ->name('donors.count');
                Route::get('donors/languages', [ReportController::class, 'languages'])
                    ->name('donors.languages');
                Route::get('donors/countries', [ReportController::class, 'countries'])
                    ->name('donors.countries');
                Route::get('donors/registrations', [ReportController::class, 'donorRegistrations'])
                    ->name('donors.registrations');
                Route::get('donations/registrations', [ReportController::class, 'donationRegistrations'])
                    ->name('donations.registrations');
                Route::get('donations/currencies', [ReportController::class, 'currencies'])
                    ->name('donations.currencies');
                Route::get('donations/channels', [ReportController::class, 'channels'])
                    ->name('donations.channels');
            });
    });

Route::middleware(['language', 'auth'])
    ->name('api.')
    ->group(function () {
        Route::apiResource('comments', CommentsController::class)
            ->except('index', 'store');
    });

// RaiseNow Webhook
Route::middleware(['auth.basic', 'can:accept-fundraising-webhooks'])
    ->prefix('fundraising/donations')
    ->name('fundraising.')
    ->group(function () {
        Route::name('donations.raiseNowWebHookListener')
            ->post('raiseNowWebHookListener', [WebhookController::class, 'raiseNowWebHookListener']);
    });

//
// Accounting
//

Route::middleware(['language', 'auth'])
    ->prefix('accounting')
    ->name('api.accounting.')
    ->group(function () {
        Route::get('wallets/names', [WalletsController::class, 'names'])
            ->name('wallets.names');
        Route::resource('wallets', WalletsController::class);
        Route::get('categories/tree', [CategoriesController::class, 'tree'])
            ->name('categories.tree');
        Route::resource('categories', CategoriesController::class);
        Route::get('categories/{category}/donations', [CategoriesController::class, 'donations'])
            ->name('categories.donations');
        Route::get('projects/tree', [ProjectsController::class, 'tree'])
            ->name('projects.tree');
        Route::resource('projects', ProjectsController::class);

        Route::get('transactions/summary', [SummaryController::class, 'index'])
            ->name('transactions.summary');

        Route::get('wallets/{wallet}/transactions', [TransactionsController::class, 'index'])
            ->name('transactions.index');
        Route::post('wallets/{wallet}/transactions', [TransactionsController::class, 'store'])
            ->name('transactions.store');
        Route::get('wallets/{wallet}/transactions/export', [ExportController::class, 'doExport'])
            ->name('transactions.export');
        Route::post('transactions/{transaction}/receipt', [TransactionsController::class, 'updateReceipt'])
            ->name('transactions.updateReceipt');
        Route::get('transactions/secondaryCategories', [TransactionsController::class, 'secondaryCategories'])
            ->name('transactions.secondaryCategories');
        Route::get('transactions/locations', [TransactionsController::class, 'locations'])
            ->name('transactions.locations');
        Route::get('transactions/costCenters', [TransactionsController::class, 'costCenters'])
            ->name('transactions.costCenters');
        Route::get('transactions/attendees', [TransactionsController::class, 'attendees'])
            ->name('transactions.attendees');
        Route::get('transactions/taxonomies', [TransactionsController::class, 'taxonomies'])
            ->name('transactions.taxonomies');
        Route::apiResource('transactions', TransactionsController::class)->except(['index', 'store']);
        Route::put('transactions/{transaction}/undoBooking', [TransactionsController::class, 'undoBooking'])
            ->name('transactions.undoBooking');

        Route::get('transactions/{transaction}/controlled', [ControllingController::class, 'controlled'])
            ->name('transactions.controlled');
        Route::post('transactions/{transaction}/controlled', [ControllingController::class, 'markControlled'])
            ->name('transactions.markControlled');
        Route::delete('transactions/{transaction}/controlled', [ControllingController::class, 'undoControlled'])
            ->name('transactions.undoControlled');

        Route::get('suppliers/export', [SuppliersController::class, 'export'])
            ->name('suppliers.export');
        Route::get('suppliers/names', [SuppliersController::class, 'names'])
            ->name('suppliers.names');
        Route::resource('suppliers', SuppliersController::class);
        Route::get('suppliers/{supplier}/transactions', [SuppliersController::class, 'transactions'])
            ->name('suppliers.transactions');

        Route::apiResource('budgets', BudgetController::class);
        Route::get('budgets/{budget}/transactions', [BudgetController::class, 'transactions'])
            ->name('budgets.transactions');
    });

//
// Community volunteers
//

Route::middleware(['auth', 'language'])
    ->name('api.')
    ->group(function () {
        Route::name('cmtyvol.')
            ->prefix('cmtyvol')
            ->group(function () {

                // Age distribution
                Route::get('ageDistribution', [CommunityVolunteersReportController::class, 'ageDistribution'])
                    ->name('ageDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');
                // Nationality distribution
                Route::get('nationalityDistribution', [CommunityVolunteersReportController::class, 'nationalityDistribution'])
                    ->name('nationalityDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');
                // Gender distribution
                Route::get('genderDistribution', [CommunityVolunteersReportController::class, 'genderDistribution'])
                    ->name('genderDistribution')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');

                Route::post('getHeaderMappings', [ImportExportController::class, 'getHeaderMappings'])
                    ->name('getHeaderMappings')
                    ->middleware('can:import,App\Models\CommunityVolunteers\CommunityVolunteer');
            });

        Route::apiResource('cmtyvol', CommunityVolunteerController::class)
            ->only('index', 'show');

        // Comments
        Route::apiResource('cmtyvol.comments', CommunityVolunteerCommentsController::class)
            ->only('index', 'store');
    });

//
// Collaboration
//

Route::middleware(['auth', 'language'])
    ->prefix('kb')
    ->name('api.kb.')
    ->group(function () {
        Route::resource('articles', ArticleController::class);
    });

//
// Visitors
//
Route::middleware(['auth', 'language'])
    ->prefix('visitors')
    ->name('api.visitors.')
    ->group(function () {
        Route::get('current', [VisitorController::class, 'listCurrent'])
            ->name('listCurrent');
        Route::post('checkin', [VisitorController::class, 'checkin'])
            ->name('checkin');
        Route::put('{visitor}/checkout', [VisitorController::class, 'checkout'])
            ->name('checkout');
        Route::post('checkoutAll', [VisitorController::class, 'checkoutAll'])
            ->name('checkoutAll');
        Route::get('export', [VisitorController::class, 'export'])
            ->name('export');
        Route::get('dailyVisitors', [VisitorController::class, 'dailyVisitors'])
            ->name('dailyVisitors');
        Route::get('monthlyVisitors', [VisitorController::class, 'monthlyVisitors'])
            ->name('monthlyVisitors');
    });

//
// Common data
//

Route::get('countries', [DataListController::class, 'countries'])
    ->middleware(['language'])
    ->name('api.countries');

Route::get('languages', [DataListController::class, 'languages'])
    ->middleware(['language'])
    ->name('api.languages');

Route::get('settings', [SettingsController::class, 'list'])
    ->name('api.settings');
