<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PrivacyPolicyController;
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
        Route::view('system-info', 'vue-app')
            ->name('system-info');
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
    Route::get('privacy', PrivacyPolicyController::class)
        ->name('privacyPolicy');

    // Settings
    Route::middleware('auth')->group(function () {
        Route::view('settings', 'vue-app')
            ->name('settings.edit');
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
                Route::view('users', 'vue-app')
                    ->name('users.index');
                Route::view('users/create', 'vue-app')
                    ->name('users.create');
                Route::view('users/{user}', 'vue-app')
                    ->name('users.show');
                Route::view('users/{user}/edit', 'vue-app')
                    ->name('users.edit');

                // Roles
                Route::view('roles', 'vue-app')
                    ->name('roles.index');
                Route::view('roles/create', 'vue-app')
                    ->name('roles.create');
                Route::view('roles/{role}', 'vue-app')
                    ->name('roles.show');
                Route::view('roles/{role}/edit', 'vue-app')
                    ->name('roles.edit');
                Route::view('roles/{role}/manageMembers', 'vue-app')
                    ->name('roles.manageMembers');
            });

        // User profile
        Route::view('userprofile', 'vue-app')
            ->name('userprofile');
        Route::view('userprofile/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('userprofile.any');
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
        Route::view('wallets/{wallet}/webling', 'vue-app')
            ->name('webling.index');
        Route::view('wallets/{wallet}/webling/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('webling.any');

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
        Route::view('cmtyvol', 'vue-app')
            ->name('cmtyvol.index');
        Route::view('cmtyvol/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('cmtyvol.any');
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

        Route::view('/{any}', 'vue-app')
            ->where('any', '.*')
            ->name('any');
    });
