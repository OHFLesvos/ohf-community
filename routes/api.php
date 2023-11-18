<?php

use App\Http\Controllers\Accounting\API\BudgetController;
use App\Http\Controllers\Accounting\API\CategoriesController;
use App\Http\Controllers\Accounting\API\ControllingController;
use App\Http\Controllers\Accounting\API\ExportController;
use App\Http\Controllers\Accounting\API\ProjectsController;
use App\Http\Controllers\Accounting\API\SummaryController;
use App\Http\Controllers\Accounting\API\SuppliersController;
use App\Http\Controllers\Accounting\API\TransactionsController;
use App\Http\Controllers\Accounting\API\WalletsController;
use App\Http\Controllers\API\ChangelogController;
use App\Http\Controllers\API\CommentsController;
use App\Http\Controllers\API\DataListController;
use App\Http\Controllers\API\SystemInfoController;
use App\Http\Controllers\Badges\API\BadgeMakerController;
use App\Http\Controllers\CommunityVolunteers\API\CommunityVolunteerCommentsController;
use App\Http\Controllers\CommunityVolunteers\API\CommunityVolunteerController;
use App\Http\Controllers\CommunityVolunteers\API\ReportController as CommunityVolunteersReportController;
use App\Http\Controllers\CommunityVolunteers\API\ResponsibilitiesController;
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
use App\Http\Controllers\UserManagement\API\RoleReportController;
use App\Http\Controllers\UserManagement\API\RoleUserRelationshipController;
use App\Http\Controllers\UserManagement\API\UserController;
use App\Http\Controllers\UserManagement\API\UserProfile2FAController;
use App\Http\Controllers\UserManagement\API\UserProfileController;
use App\Http\Controllers\UserManagement\API\UserReportController;
use App\Http\Controllers\UserManagement\API\UserRoleRelationshipController;
use App\Http\Controllers\Visitors\API\ExportController as VisitorsExportController;
use App\Http\Controllers\Visitors\API\ReportController as VisitorsReportController;
use App\Http\Controllers\Visitors\API\VisitorController;
use GrahamCampbell\Markdown\Facades\Markdown;
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

Route::middleware(['auth:sanctum', 'language'])
    ->name('api.')
    ->group(function () {
        Route::get('system-info', SystemInfoController::class)
            ->name('system-info');
        Route::get('changelog', ChangelogController::class)
            ->name('changelog');

        //
        // User management
        //
        Route::apiResource('users', UserController::class);
        Route::prefix('users/{user}')
            ->name('users.')
            ->group(function () {
                Route::put('disable2FA', [UserController::class, 'disable2FA'])
                    ->name('disable2FA');
                Route::put('disableOAuth', [UserController::class, 'disableOAuth'])
                    ->name('disableOAuth');
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
        Route::get('users/report/permissions', [UserReportController::class, 'userPermissions'])
            ->name('users.report.permissions')
            ->middleware('can:viewAny,App\Models\User');

        //
        // Role management
        //

        Route::get('roles/permissions', [RoleController::class, 'permissions'])
            ->name('roles.permissions');
        Route::apiResource('roles', RoleController::class);

        Route::prefix('roles/{role}')
            ->name('roles.')
            ->group(function () {
                Route::put('members', [RoleController::class, 'updateMembers'])
                    ->name('updateMembers');
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
        Route::get('roles/report/permissions', [RoleReportController::class, 'rolePermissions'])
            ->name('roles.report.permissions')
            ->middleware('can:viewAny,App\Models\Role');

        //
        // Fundraising
        //
        Route::prefix('fundraising')
            ->name('fundraising.')
            ->group(function () {
                // Donor
                Route::get('donors/export', [DonorController::class, 'export'])
                    ->name('donors.export');
                Route::get('donors/salutations', [DonorController::class, 'salutations'])
                    ->name('donors.salutations');
                Route::get('donors/names', [DonorController::class, 'names'])
                    ->name('donors.names');
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
                        Route::get('summary', [ReportController::class, 'summary'])
                            ->name('summary');
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

        //
        // Accounting
        //
        Route::prefix('accounting')
            ->name('accounting.')
            ->group(function () {
                // Wallets
                Route::get('wallets/names', [WalletsController::class, 'names'])
                    ->name('wallets.names');
                Route::resource('wallets', WalletsController::class);

                // Categories
                Route::get('categories/tree', [CategoriesController::class, 'tree'])
                    ->name('categories.tree');
                Route::resource('categories', CategoriesController::class);

                // Projects
                Route::get('projects/tree', [ProjectsController::class, 'tree'])
                    ->name('projects.tree');
                Route::resource('projects', ProjectsController::class);

                // Transactions
                Route::get('transactions/summary', [SummaryController::class, 'index'])
                    ->name('transactions.summary');

                Route::get('transactions', [TransactionsController::class, 'index'])
                    ->name('transactions.index');
                Route::post('transactions', [TransactionsController::class, 'store'])
                    ->name('transactions.store');
                Route::get('transactions/export', [ExportController::class, 'doExport'])
                    ->name('transactions.export');
                Route::post('transactions/{transaction}/receipt', [TransactionsController::class, 'updateReceipt'])
                    ->name('transactions.updateReceipt');
                Route::put('transactions/{transaction}/receipt/rotate', [TransactionsController::class, 'rotateReceipt'])
                    ->name('transactions.rotateReceipt');
                Route::get('transactions/history', [TransactionsController::class, 'history'])
                    ->name('transactions.history');
                Route::get('transactions/{transaction}/history', [TransactionsController::class, 'transactionHistory'])
                    ->name('transactions.transactionHistory');
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
                Route::get('transactions/controllable', [ControllingController::class, 'controllable'])
                    ->name('transactions.controllable');
                Route::apiResource('transactions', TransactionsController::class)->except(['index', 'store']);
                Route::put('transactions/{transaction}/undoBooking', [TransactionsController::class, 'undoBooking'])
                    ->name('transactions.undoBooking');

                Route::get('transactions/{transaction}/controlled', [ControllingController::class, 'controlled'])
                    ->name('transactions.controlled');
                Route::post('transactions/{transaction}/controlled', [ControllingController::class, 'markControlled'])
                    ->name('transactions.markControlled');
                Route::delete('transactions/{transaction}/controlled', [ControllingController::class, 'undoControlled'])
                    ->name('transactions.undoControlled');

                // Suppliers
                Route::get('suppliers/export', [SuppliersController::class, 'export'])
                    ->name('suppliers.export');
                Route::get('suppliers/names', [SuppliersController::class, 'names'])
                    ->name('suppliers.names');
                Route::resource('suppliers', SuppliersController::class);
                Route::get('suppliers/{supplier}/transactions', [SuppliersController::class, 'transactions'])
                    ->name('suppliers.transactions');

                // Budgets
                Route::get('budgets/names', [BudgetController::class, 'names'])
                    ->name('budgets.names');
                Route::apiResource('budgets', BudgetController::class);
                Route::get('budgets/{budget}/transactions', [BudgetController::class, 'transactions'])
                    ->name('budgets.transactions');
                Route::get('budgets/{budget}/donations', [BudgetController::class, 'donations'])
                    ->name('budgets.donations');
                Route::get('budgets/{budget}/export', [BudgetController::class, 'export'])
                    ->name('budgets.export');
            });

        //
        // Community volunteers
        //
        Route::name('cmtyvol.')
            ->prefix('cmtyvol')
            ->group(function () {
                Route::get('languages', [CommunityVolunteerController::class, 'languages'])
                    ->name('languages')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');

                Route::get('pickupLocations', [CommunityVolunteerController::class, 'pickupLocations'])
                    ->name('pickupLocations')
                    ->middleware('can:viewAny,App\Models\CommunityVolunteers\CommunityVolunteer');

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

                Route::apiResource('responsibilities', ResponsibilitiesController::class);
            });

        Route::apiResource('cmtyvol', CommunityVolunteerController::class);

        // Comments
        Route::apiResource('cmtyvol.comments', CommunityVolunteerCommentsController::class)
            ->only('index', 'store');

        //
        // Visitors
        //
        Route::prefix('visitors')
            ->name('visitors.')
            ->group(function () {
                Route::get('export', [VisitorsExportController::class, 'doExport'])
                    ->name('export');

                // Report
                Route::get('report/checkins', [VisitorsReportController::class, 'visitorCheckins'])
                    ->name('report.visitorCheckins');
                Route::get('report/genderDistribution', [VisitorsReportController::class, 'genderDistribution'])
                    ->name('report.genderDistribution');
                Route::get('report/nationalityDistribution', [VisitorsReportController::class, 'nationalityDistribution'])
                    ->name('report.nationalityDistribution');
                Route::get('report/ageDistribution', [VisitorsReportController::class, 'ageDistribution'])
                    ->name('report.ageDistribution');
                Route::get('report/checkInsByPurpose', [VisitorsReportController::class, 'checkInsByPurpose'])
                    ->name('report.checkInsByPurpose');

                Route::get('checkins', [VisitorController::class, 'checkins'])
                    ->name('checkins');

                Route::get('', [VisitorController::class, 'index'])
                    ->name('index');
                Route::post('', [VisitorController::class, 'store'])
                    ->name('store');
                Route::get('{visitor}', [VisitorController::class, 'show'])
                    ->name('show');
                Route::put('{visitor}', [VisitorController::class, 'update'])
                    ->name('update');
                Route::delete('{visitor}', [VisitorController::class, 'destroy'])
                    ->name('destroy');

                Route::post('{visitor}/checkins', [VisitorController::class, 'checkin'])
                    ->name('checkin');

                Route::post('{visitor}/generateMembershipNumber', [VisitorController::class, 'generateMembershipNumber'])
                    ->name('generateMembershipNumber');
                Route::post('{visitor}/signLiabilityForm', [VisitorController::class, 'signLiabilityForm'])
                    ->name('signLiabilityForm');
                Route::post('{visitor}/giveParentalConsent', [VisitorController::class, 'giveParentalConsent'])
                    ->name('giveParentalConsent');
            });

        //
        // Badges
        //
        Route::middleware(['can:create-badges'])
            ->name('badges.')
            ->prefix('badges')
            ->group(function () {
                Route::post('make', [BadgeMakerController::class, 'make'])
                    ->name('make');
                Route::post('parseSpreadsheet', [BadgeMakerController::class, 'parseSpreadsheet'])
                    ->name('parseSpreadsheet');
                Route::get('fetchCommunityVolunteers', [BadgeMakerController::class, 'fetchCommunityVolunteers'])
                    ->name('fetchCommunityVolunteers');
            });

        //
        // User profile
        //
        Route::get('userprofile', [UserProfileController::class, 'index'])
            ->name('userprofile');
        Route::post('userprofile', [UserProfileController::class, 'update'])
            ->name('userprofile.update');
        Route::post('userprofile/updatePassword', [UserProfileController::class, 'updatePassword'])
            ->name('userprofile.updatePassword');
        Route::delete('userprofile', [UserProfileController::class, 'delete'])
            ->name('userprofile.delete');

        Route::get('userprofile/2FA', [UserProfile2FAController::class, 'index'])
            ->name('userprofile.2fa.index');
        Route::post('userprofile/2FA', [UserProfile2FAController::class, 'store'])
            ->name('userprofile.2fa.store');

        //
        // Comments
        //
        Route::apiResource('comments', CommentsController::class)
            ->except('index', 'store');

        //
        // Settings
        //
        Route::get('settings/fields', [SettingsController::class, 'fields'])
            ->name('settings.fields');
        Route::post('settings', [SettingsController::class, 'update'])
            ->name('settings.update');
        Route::delete('settings', [SettingsController::class, 'reset'])
            ->name('settings.reset');
        Route::delete('settings/{key}', [SettingsController::class, 'resetField'])
            ->name('settings.resetField');
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
// Common data
//
Route::get('countries', [DataListController::class, 'countries'])
    ->name('api.countries');

Route::get('localizedCountries', [DataListController::class, 'localizedCountries'])
    ->middleware(['language'])
    ->name('api.localizedCountries');

Route::get('localizedLanguages', [DataListController::class, 'localizedLanguages'])
    ->middleware(['language'])
    ->name('api.localizedLanguages');

Route::get('settings', [SettingsController::class, 'list'])
    ->name('api.settings');

Route::get('changelog', fn () => Markdown::convert(file_get_contents(base_path('Changelog.md'))))
    ->name('api.changelog');
