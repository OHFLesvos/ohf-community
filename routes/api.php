<?php

use App\Http\Controllers\Accounting\API\ControllingController;
use App\Http\Controllers\Accounting\API\MoneyTransactionsController;
use App\Http\Controllers\Accounting\API\SuppliersController;
use App\Http\Controllers\Accounting\API\WalletsController;
use App\Http\Controllers\API\CommentsController;
use App\Http\Controllers\API\DataListController;
use App\Http\Controllers\Bank\API\VisitorReportingController;
use App\Http\Controllers\Bank\API\WithdrawalController;
use App\Http\Controllers\Bank\API\WithdrawalReportingController;
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
use App\Http\Controllers\People\API\MonthlySummaryReportController;
use App\Http\Controllers\People\API\PeopleController;
use App\Http\Controllers\People\API\ReportingController;
use App\Http\Controllers\Shop\API\CardsController;
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
        Route::resource('wallets', WalletsController::class);

        Route::post('transactions/{transaction}/receipt', [MoneyTransactionsController::class, 'updateReceipt'])
            ->name('transactions.updateReceipt');

        Route::get('transactions/{transaction}/controlled', [ControllingController::class, 'controlled'])
            ->name('transactions.controlled');
        Route::post('transactions/{transaction}/controlled', [ControllingController::class, 'markControlled'])
            ->name('transactions.markControlled');
        Route::delete('transactions/{transaction}/controlled', [ControllingController::class, 'undoControlled'])
            ->name('transactions.undoControlled');

        Route::get('suppliers/export', [SuppliersController::class, 'export'])
            ->name('suppliers.export');
        Route::resource('suppliers', SuppliersController::class);
        Route::get('suppliers/{supplier}/transactions', [SuppliersController::class, 'transactions'])
            ->name('suppliers.transactions');
    });

//
// People
//

Route::middleware(['auth', 'language'])
    ->name('api.people.')
    ->prefix('people')
    ->group(function () {

        // Get list of people
        Route::get('', [PeopleController::class, 'index'])
            ->name('index')
            ->middleware('can:viewAny,App\Models\People\Person');

        // Filter persons
        Route::get('filterPersons', [PeopleController::class, 'filterPersons'])
            ->name('filterPersons')
            ->middleware('can:viewAny,App\Models\People\Person');

        // Store new person
        Route::post('', [PeopleController::class, 'store'])
            ->name('store')
            ->middleware('can:create,App\Models\People\Person');

        // Show person
        Route::get('{person}', [PeopleController::class, 'show'])
            ->name('show')
            ->middleware('can:view,person');

        // Update person
        Route::put('{person}', [PeopleController::class, 'update'])
            ->name('update')
            ->middleware('can:update,person');

        // Set gender
        Route::patch('{person}/gender', [PeopleController::class, 'updateGender'])
            ->name('updateGender')
            ->middleware('can:update,person');

        // Set date of birth
        Route::patch('{person}/date_of_birth', [PeopleController::class, 'updateDateOfBirth'])
            ->name('updateDateOfBirth')
            ->middleware('can:update,person');

        // Set nationality
        Route::patch('{person}/nationality', [PeopleController::class, 'updateNationality'])
            ->name('updateNationality')
            ->middleware('can:update,person');

        // Update police number
        Route::patch('{person}/updatePoliceNo', [PeopleController::class, 'updatePoliceNo'])
            ->name('updatePoliceNo')
            ->middleware('can:update,person');

        // Update remarks
        Route::patch('{person}/remarks', [PeopleController::class, 'updateRemarks'])
            ->name('updateRemarks')
            ->middleware('can:update,person');

        // Register code card
        Route::patch('{person}/card', [PeopleController::class, 'registerCard'])
            ->name('registerCard')
            ->middleware('can:update,person');

        // Reporting
        Route::prefix('reporting')
            ->name('reporting.')
            ->middleware(['can:view-people-reports'])
            ->group(function () {
                Route::get('numbers', [ReportingController::class, 'numbers'])
                    ->name('numbers');
                Route::get('nationalities', [ReportingController::class, 'nationalities'])
                    ->name('nationalities');
                Route::get('genderDistribution', [ReportingController::class, 'genderDistribution'])
                    ->name('genderDistribution');
                Route::get('ageDistribution', [ReportingController::class, 'ageDistribution'])
                    ->name('ageDistribution');
                Route::get('registrationsPerDay', [ReportingController::class, 'registrationsPerDay'])
                    ->name('registrationsPerDay');
                Route::get('monthlySummary', [MonthlySummaryReportController::class, 'summary'])
                    ->name('monthlySummary');
            });
    });

//
// Bank
//

Route::middleware(['auth', 'language'])
    ->group(function () {

        // Withdrawals
        Route::middleware('can:do-bank-withdrawals')
            ->prefix('bank')
            ->name('api.bank.withdrawal.')
            ->group(function () {
                Route::get('withdrawal/dailyStats', [WithdrawalController::class, 'dailyStats'])
                    ->name('dailyStats');
                Route::get('withdrawal/transactions', [WithdrawalController::class, 'transactions'])
                    ->name('transactions')
                    ->middleware('can:viewAny,App\Models\People\Person');
                Route::get('withdrawal/search', [WithdrawalController::class, 'search'])
                    ->name('search');
                Route::get('withdrawal/persons/{person}', [WithdrawalController::class, 'person'])
                    ->name('person');
                Route::post('person/{person}/couponType/{couponType}/handout', [WithdrawalController::class, 'handoutCoupon'])
                    ->name('handoutCoupon');
                Route::delete('person/{person}/couponType/{couponType}/handout', [WithdrawalController::class, 'undoHandoutCoupon'])
                    ->name('undoHandoutCoupon');
            });

        // Reporting
        Route::middleware('can:view-bank-reports')
            ->prefix('bank')
            ->name('api.bank.reporting.')
            ->group(function () {
                // Withdrawals
                Route::get('withdrawals', [WithdrawalReportingController::class, 'withdrawals'])
                    ->name('withdrawals');
                Route::get('withdrawals/chart/couponsHandedOutPerDay/{coupon}', [WithdrawalReportingController::class, 'couponsHandedOutPerDay'])
                    ->name('couponsHandedOutPerDay');

                // Visitors
                Route::get('visitors', [VisitorReportingController::class, 'visitors'])
                    ->name('visitors');
                Route::get('visitors/chart/visitorsPerDay', [VisitorReportingController::class, 'visitorsPerDay'])
                    ->name('visitorsPerDay');
                Route::get('visitors/chart/visitorsPerWeek', [VisitorReportingController::class, 'visitorsPerWeek'])
                    ->name('visitorsPerWeek');
                Route::get('visitors/chart/visitorsPerMonth', [VisitorReportingController::class, 'visitorsPerMonth'])
                    ->name('visitorsPerMonth');
                Route::get('visitors/chart/visitorsPerYear', [VisitorReportingController::class, 'visitorsPerYear'])
                    ->name('visitorsPerYear');
                Route::get('visitors/chart/avgVisitorsPerDayOfWeek', [VisitorReportingController::class, 'avgVisitorsPerDayOfWeek'])
                    ->name('avgVisitorsPerDayOfWeek');
            });
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
// Shop
//

Route::middleware(['auth', 'language'])
    ->prefix('shop')
    ->name('api.shop.')
    ->group(function () {
        Route::middleware(['can:validate-shop-coupons'])
            ->prefix('cards')
            ->name('cards.')
            ->group(function () {
                Route::get('listRedeemedToday', [CardsController::class, 'listRedeemedToday'])
                    ->name('listRedeemedToday');
                Route::get('searchByCode', [CardsController::class, 'searchByCode'])
                    ->name('searchByCode');
                Route::patch('redeem/{handout}', [CardsController::class, 'redeem'])
                    ->name('redeem');
                Route::delete('cancel/{handout}', [CardsController::class, 'cancel'])
                    ->name('cancel');
                Route::get('listNonRedeemedByDay', [CardsController::class, 'listNonRedeemedByDay'])
                    ->name('listNonRedeemedByDay');
                Route::post('deleteNonRedeemedByDay', [CardsController::class, 'deleteNonRedeemedByDay'])
                    ->name('deleteNonRedeemedByDay');
            });
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
