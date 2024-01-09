<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FrontendAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\FileController;
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

/* Protected Routes */

/* Auth Routes */
Route::middleware('auth')->controller(FrontendController::class)->group(function () {

    Route::get('/', [FrontendController::class, 'index'])->name('index');
    //Route::get('dashboard', [FrontendController::class, 'dashboard'])->name('frontend.dashboard.index');
    Route::get('account/settings', [FrontendController::class, 'account_settings'])->name('frontend.account.settings');

    Route::get('administration/settings', [FrontendController::class, 'settings'])->name('frontend.administration.settings');
    Route::get('administration/files', [FrontendController::class, 'files'])->name('frontend.administration.files');
    Route::get('administration/invoice-series', [FrontendController::class, 'invoice_series'])->name('frontend.administration.invoice_series');
    Route::get('administration/currencies', [FrontendController::class, 'currencies'])->name('frontend.administration.currencies');
    Route::get('administration/labels', [FrontendController::class, 'labels'])->name('frontend.administration.labels');
    Route::get('administration/statuses', [FrontendController::class, 'statuses'])->name('frontend.administration.statuses');
    Route::get('administration/users', [FrontendController::class, 'users'])->name('frontend.administration.users');
    Route::get('administration/roles', [FrontendController::class, 'roles'])->name('frontend.administration.roles');
    
    Route::group(['prefix' => 'account'], function () {
        Route::get('settings', [FrontendController::class, 'account_settings'])->name('frontend.account.settings');
        Route::get('security', [FrontendController::class, 'account_security'])->name('frontend.account.security');
        Route::get('theme', [FrontendController::class, 'account_theme'])->name('frontend.account.theme');
    });

    Route::get('clients', [FrontendController::class, 'clients'])->name('frontend.clients');
    Route::get('purchase-invoices', [FrontendController::class, 'purchase_invoices'])->name('frontend.purchase_invoices');
    Route::get('purchase-invoice/edit/{id}/{tab}', [FrontendController::class, 'purchase_invoice_edit'])->name('frontend.purchase_invoice.edit');
    Route::get('rentals', [FrontendController::class, 'rentals'])->name('frontend.rentals');
    Route::get('bundles', [FrontendController::class, 'bundles'])->name('frontend.bundles');
    Route::get('bundle/edit/{id}/{tab}', [FrontendController::class, 'bundle_edit'])->name('frontend.bundle_edit.edit');
    Route::get('services', [FrontendController::class, 'services'])->name('frontend.services');
    Route::get('warehouse', [FrontendController::class, 'warehouse'])->name('frontend.warehouse');
    Route::get('projects', [FrontendController::class, 'projects'])->name('frontend.projects');
    Route::get('project/edit/{id}/{tab}', [FrontendController::class, 'project_edit'])->name('frontend.project.edit');

    Route::get('invoices', [FrontendController::class, 'invoices'])->name('frontend.invoices');
    Route::get('invoice/create', [FrontendController::class, 'invoice_create'])->name('frontend.invoice.create');
    Route::get('invoice/edit/{id}', [FrontendController::class, 'invoice_edit'])->name('frontend.invoice.edit');
    Route::get('invoice/project/{id}', [FrontendController::class, 'invoice_project'])->name('frontend.invoice.project');

    Route::get('offers', [FrontendController::class, 'offers'])->name('frontend.offers');
    Route::get('offer/create', [FrontendController::class, 'offer_create'])->name('frontend.offer.create');
    Route::get('offer/edit/{id}', [FrontendController::class, 'offer_edit'])->name('frontend.offer.edit');
    Route::get('offer/project/{id}', [FrontendController::class, 'offer_project'])->name('frontend.offer.project');

    Route::group(['prefix' => 'invoice'], function () {
        Route::get('all', [FrontendController::class, 'invoices'])->name('frontend.invoice.all');
        Route::get('create', [FrontendController::class, 'invoice_create'])->name('frontend.invoice.create');
        Route::get('edit/{id}', [FrontendController::class, 'invoice_edit'])->name('frontend.invoice.edit');
    });

    Route::get('company-expenses', [FrontendController::class, 'company_expenses'])->name('frontend.company_expenses');
    Route::get('user-payments', [FrontendController::class, 'user_payments'])->name('frontend.user_payments');
    Route::get('fix-assets', [FrontendController::class, 'fix_assets'])->name('frontend.fix_assets');

});

/* Route Authentication Pages */
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [FrontendAuthController::class, 'login'])->name('frontend.auth.login');
    Route::get('password-reset', [FrontendAuthController::class, 'password_reset'])->name('frontend.auth.password_reset');
    Route::get('logout', [FrontendAuthController::class, 'logout'])->name('frontend.auth.logout');
});

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// Files Route
Route::get('image/{uuid}', [FileController::class, 'preview_image']);
Route::get('dfile/{uuid}', [FileController::class, 'download_file']);
Route::get('pfile/{uuid}', [FileController::class, 'preview_file']);


//test to show:

Route::get('upload_max_filesize', function () {
    echo ini_get('upload_max_filesize');
});