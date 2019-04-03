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

        // Home (Dashboard)
        Route::get('/', 'HomeController@index')->name('home');

        // Changelog
        Route::group(['middleware' => ['can:view-changelogs']], function () {
            Route::get('/changelog', 'ChangelogController@index')->name('changelog');
        });

        // Log viewer
        Route::group(['middleware' => ['can:view-logs']], function () {
            Route::get('/logviewer', 'LogViewerController@index')->name('logviewer.index');
        });

        //
        // User and role management
        //
        Route::namespace('Admin')->prefix('admin')->group(function(){
            // Users
            Route::put('users/{user}/disable2FA', 'UserController@disable2FA')->name('users.disable2FA');
            Route::resource('users', 'UserController');

            // Roles
            Route::resource('roles', 'RoleController');

            // Reporting
            Route::group(['middleware' => ['can:view-usermgmt-reports']], function () {    
                Route::get('reporting/users/permissions', 'UserController@permissions')->name('users.permissions');
                Route::get('reporting/users/sensitiveData', 'UserController@sensitiveDataReport')->name('reporting.privacy');
                Route::get('reporting/roles/permissions', 'RoleController@permissions')->name('roles.permissions');
            });
        });

        //
        // User profile
        //
        Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
        Route::post('/userprofile', 'UserProfileController@update')->name('userprofile.update');
        Route::post('/userprofile/updatePassword', 'UserProfileController@updatePassword')->name('userprofile.updatePassword');
        Route::delete('/userprofile', 'UserProfileController@delete')->name('userprofile.delete');
        Route::get('/userprofile/2FA', 'UserProfileController@view2FA')->name('userprofile.view2FA');
        Route::post('/userprofile/2FA', 'UserProfileController@store2FA')->name('userprofile.store2FA');
        Route::delete('/userprofile/2FA', 'UserProfileController@disable2FA')->name('userprofile.disable2FA');

        //
        // Bank
        //
        Route::get('/bank', function(){
            return redirect()->route('bank.withdrawal');
        })->name('bank.index');

        // Withdrawals
        Route::group(['middleware' => ['can:do-bank-withdrawals']], function () {
            Route::get('/bank/withdrawal', 'People\Bank\WithdrawalController@index')->name('bank.withdrawal');
            Route::get('/bank/withdrawal/search', 'People\Bank\WithdrawalController@search')->name('bank.withdrawalSearch');
            Route::get('/bank/withdrawal/transactions', 'People\Bank\WithdrawalController@transactions')->name('bank.withdrawalTransactions');
            
            Route::get('/bank/withdrawal/cards/{card}', 'People\Bank\WithdrawalController@showCard')->name('bank.showCard');

            Route::get('/bank/codeCard', 'People\Bank\CodeCardController@create')->name('bank.prepareCodeCard');
            Route::post('/bank/codeCard', 'People\Bank\CodeCardController@download')->name('bank.createCodeCard');
        });

        // Deposits
        Route::group(['middleware' => ['can:do-bank-deposits']], function () {
            Route::get('/bank/deposit', 'People\Bank\DepositController@index')->name('bank.deposit');
            Route::post('/bank/deposit', 'People\Bank\DepositController@store')->name('bank.storeDeposit');
            Route::get('/bank/deposit/transactions', 'People\Bank\DepositController@transactions')->name('bank.depositTransactions');
        });

        // Settings
        Route::group(['middleware' => ['can:configure-bank']], function () {
            Route::get('/bank/settings', 'People\Bank\BankSettingsController@edit')->name('bank.settings.edit');
            Route::put('/bank/settings', 'People\Bank\BankSettingsController@update')->name('bank.settings.update');
            Route::resource('/bank/coupons', 'People\Bank\CouponTypesController');
        });

        // Maintenance
        Route::group(['middleware' => ['can:cleanup,App\Person']], function () {
            Route::get('/bank/maintenance', 'People\Bank\MaintenanceController@maintenance')->name('bank.maintenance');
            Route::post('/bank/maintenance', 'People\Bank\MaintenanceController@updateMaintenance')->name('bank.updateMaintenance');
        });

        // Export
        Route::group(['middleware' => ['can:export,App\Person']], function () {
            Route::get('/bank/export', 'People\Bank\ImportExportController@export')->name('bank.export');
            Route::post('/bank/doExport', 'People\Bank\ImportExportController@doExport')->name('bank.doExport');
        });

        //
        // People
        //
        Route::post('/people/filter', 'PeopleController@filter')->name('people.filter');
        Route::get('/people/export', 'PeopleController@export')->name('people.export');
        Route::get('/people/import', 'PeopleController@import')->name('people.import');
        Route::post('/people/doImport', 'PeopleController@doImport')->name('people.doImport');
        Route::get('/people/{person}/qrcode', 'PeopleController@qrCode')->name('people.qrCode');
        Route::get('/people/{person}/relations', 'PeopleController@relations')->name('people.relations');
        Route::get('/people/filterPersons', 'PeopleController@filterPersons')->name('people.filterPersons');
        Route::post('/people/{person}/relations', 'PeopleController@addRelation')->name('people.addRelation');
        Route::delete('/people/{person}/children/{child}', 'PeopleController@removeChild')->name('people.removeChild');
        Route::delete('/people/{person}/partner', 'PeopleController@removePartner')->name('people.removePartner');
        Route::delete('/people/{person}/mother', 'PeopleController@removeMother')->name('people.removeMother');
        Route::delete('/people/{person}/father', 'PeopleController@removeFather')->name('people.removeFather');
        Route::get('/people/duplicates', 'PeopleController@duplicates')->name('people.duplicates');
        Route::post('/people/duplicates', 'PeopleController@applyDuplicates')->name('people.applyDuplicates');
        Route::post('/people/bulkAction', 'PeopleController@bulkAction')->name('people.bulkAction');
        Route::resource('/people', 'PeopleController');

        // Shop
        Route::namespace('Shop')->prefix('shop')->name('shop.')->middleware(['can:validate-shop-coupons'])->group(function(){
            Route::get('/', 'ShopController@index')->name('index');
            Route::post('/', 'ShopController@redeem')->name('redeem');
            Route::get('/settings', 'ShopSettingsController@edit')->name('settings.edit')->middleware(['can:configure-shop']);
            Route::put('/settings', 'ShopSettingsController@update')->name('settings.update')->middleware(['can:configure-shop']);
        });
        Route::namespace('Shop')->prefix('barber')->name('shop.barber.')->middleware(['can:view-barber-list'])->group(function(){
            Route::get('/', 'BarberShopController@index')->name('index');
            Route::post('/checkin', 'BarberShopController@checkin')->name('checkin');
            Route::post('/addPerson', 'BarberShopController@addPerson')->name('addPerson');
            Route::delete('/removePerson', 'BarberShopController@removePerson')->name('removePerson');
            Route::get('/settings', 'BarberShopSettingsController@edit')->name('settings.edit')->middleware(['can:configure-barber-list']);
            Route::put('/settings', 'BarberShopSettingsController@update')->name('settings.update')->middleware(['can:configure-barber-list']);
        });

        Route::namespace('Library')->prefix('library')->name('library.')->middleware(['can:operate-library'])->group(function(){
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

        //
        // Reporting
        //
        Route::group(['middleware' => ['can:view-reports']], function () {
            Route::view('/reporting', 'reporting.index')->name('reporting.index');
        });

        // Reporting: Monthly summary report
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('/reporting/monthly-summary', 'Reporting\\MonthlySummaryReportingController@index')->name('reporting.monthly-summary');
        });

        // Reporting: People
        Route::group(['middleware' => ['can:view-people-reports']], function () {
            Route::get('/reporting/people', 'Reporting\\PeopleReportingController@index')->name('reporting.people');
            Route::get('/reporting/people/chart/nationalities', 'Reporting\\PeopleReportingController@nationalities')->name('reporting.people.nationalities');
            Route::get('/reporting/people/chart/genderDistribution', 'Reporting\\PeopleReportingController@genderDistribution')->name('reporting.people.genderDistribution');
            Route::get('/reporting/people/chart/demographics', 'Reporting\\PeopleReportingController@demographics')->name('reporting.people.demographics');
            Route::get('/reporting/people/chart/numberTypes', 'Reporting\\PeopleReportingController@numberTypes')->name('reporting.people.numberTypes');
            Route::get('/reporting/people/chart/visitorsPerDay', 'Reporting\\PeopleReportingController@visitorsPerDay')->name('reporting.people.visitorsPerDay');
            Route::get('/reporting/people/chart/visitorsPerWeek', 'Reporting\\PeopleReportingController@visitorsPerWeek')->name('reporting.people.visitorsPerWeek');
            Route::get('/reporting/people/chart/visitorsPerMonth', 'Reporting\\PeopleReportingController@visitorsPerMonth')->name('reporting.people.visitorsPerMonth');
            Route::get('/reporting/people/chart/visitorsPerYear', 'Reporting\\PeopleReportingController@visitorsPerYear')->name('reporting.people.visitorsPerYear');
            Route::get('/reporting/people/chart/avgVisitorsPerDayOfWeek', 'Reporting\\PeopleReportingController@avgVisitorsPerDayOfWeek')->name('reporting.people.avgVisitorsPerDayOfWeek');
            Route::get('/reporting/people/chart/registrationsPerDay', 'Reporting\\PeopleReportingController@registrationsPerDay')->name('reporting.people.registrationsPerDay');
        });

        // Reporting: Bank
        Route::group(['middleware' => ['can:view-bank-reports']], function () {
            Route::get('/reporting/bank/withdrawals', 'Reporting\\BankReportingController@withdrawals')->name('reporting.bank.withdrawals');
            Route::get('/reporting/bank/withdrawals/chart/couponsHandedOutPerDay/{coupon}', 'Reporting\\BankReportingController@couponsHandedOutPerDay')->name('reporting.bank.couponsHandedOutPerDay');

            Route::get('/reporting/bank/deposits', 'Reporting\\BankReportingController@deposits')->name('reporting.bank.deposits');
            Route::get('/reporting/bank/deposits/chart/stats', 'Reporting\\BankReportingController@depositStats')->name('reporting.bank.depositStats');
            Route::get('/reporting/bank/deposits/chart/stats/{project}', 'Reporting\\BankReportingController@projectDepositStats')->name('reporting.bank.projectDepositStats');
        });
    });

    Auth::routes();
    Route::get('/userPrivacyPolicy', 'PrivacyPolicy@userPolicy')->name('userPrivacyPolicy');

});
