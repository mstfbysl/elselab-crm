<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ClientController;

use App\Http\Controllers\API\PurchaseInvoiceController;
use App\Http\Controllers\API\PurchaseInvoiceItemController;
use App\Http\Controllers\API\PurchaseInvoiceRentalController;

use App\Http\Controllers\API\WarehouseController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ProjectExpenseController;
use App\Http\Controllers\API\ProjectIncomeController;
use App\Http\Controllers\API\ProjectOwnerController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\InvoiceItemController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\OfferItemController;
use App\Http\Controllers\API\CompanyExpenseController;
use App\Http\Controllers\API\UserPaymentController;
use App\Http\Controllers\API\FixAssetController;
use App\Http\Controllers\API\ProjectServiceController;
use App\Http\Controllers\API\ProjectRentalController;
use App\Http\Controllers\API\ProjectBundleController;
use App\Http\Controllers\API\RentalController;
use App\Http\Controllers\API\RentalBundlesController;
use App\Http\Controllers\API\BundleRentalsController;
use App\Http\Controllers\API\ServiceController;

use App\Http\Controllers\API\SystemCurrencyController;
use App\Http\Controllers\API\LabelController;
use App\Http\Controllers\API\ProjectStatusController;
use App\Http\Controllers\API\SystemFileController;
use App\Http\Controllers\API\SystemInvoiceSerieController;
use App\Http\Controllers\API\SystemOfferSerieController;
use App\Http\Controllers\API\SystemPermissionController;
use App\Http\Controllers\API\SystemDefinitionController;
use App\Http\Controllers\API\SystemSettingController;
use App\Http\Controllers\API\ProjectDocumentController;
use App\Http\Controllers\API\UserDocumentController;
use App\Http\Controllers\API\ClientDocumentController;
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

##Auth
Route::post('auth/login', [AuthController::class, 'login'])->name('api.auth.login');
Route::post('auth/password-reset', [AuthController::class, 'password_reset'])->name('api.auth.password_reset');
Route::post('auth/password-reset/confirm', [AuthController::class, 'password_reset_confirm'])->name('api.auth.password_reset_confirm');

##Account
Route::get('account/detail', [AccountController::class, 'detail'])->name('api.account.detail');
Route::post('account/save-details', [AccountController::class, 'save_details'])->name('api.account.save_details');
Route::post('account/save-password', [AccountController::class, 'save_password'])->name('api.account.save_password');
Route::post('account/save-theme', [AccountController::class, 'save_theme'])->name('api.account.save_theme');

##User
Route::get('user/list-all', [UserController::class, 'list_all'])->name('api.user.list_all');
Route::get('user/list-datatable', [UserController::class, 'list_datatable'])->name('api.user.list_datatable');
Route::get('user/detail/{id}', [UserController::class, 'detail'])->name('api.user.detail');
Route::post('user/create', [UserController::class, 'create'])->name('api.user.create');
Route::post('user/edit/{id}', [UserController::class, 'edit'])->name('api.user.edit');
Route::get('user/delete/{id}', [UserController::class, 'delete'])->name('api.user.delete');

##User Document
Route::get('user-document/list-by-user/{user_id}', [UserDocumentController::class, 'list_by_user'])->name('api.user_document.list_by_user');
Route::post('user-document/create', [UserDocumentController::class, 'create'])->name('api.user_document.create');
Route::get('user-document/delete/{id}', [UserDocumentController::class, 'delete'])->name('api.user_document.delete');

##Client Document
Route::get('client-document/list-by-client/{client_id}', [ClientDocumentController::class, 'list_by_client'])->name('api.client_document.list_by_client');
Route::post('client-document/create', [ClientDocumentController::class, 'create'])->name('api.client_document.create');
Route::get('client-document/delete/{id}', [ClientDocumentController::class, 'delete'])->name('api.client_document.delete');

##Role
Route::get('role/list-all', [RoleController::class, 'list_all'])->name('api.role.list_all');
Route::get('role/list-datatable', [RoleController::class, 'list_datatable'])->name('api.role.list_datatable');
Route::get('role/detail/{id}', [RoleController::class, 'detail'])->name('api.role.detail');
Route::post('role/create', [RoleController::class, 'create'])->name('api.role.create');
Route::post('role/edit/{id}', [RoleController::class, 'edit'])->name('api.role.edit');
Route::get('role/delete/{id}', [RoleController::class, 'delete'])->name('api.role.delete');

##Client
Route::get('client/list-all', [ClientController::class, 'list_all'])->name('api.client.list_all');
Route::get('client/list-datatable', [ClientController::class, 'list_datatable'])->name('api.client.list_datatable');
Route::get('client/detail/{id}', [ClientController::class, 'detail'])->name('api.client.detail');
Route::post('client/create', [ClientController::class, 'create'])->name('api.client.create');
Route::post('client/edit/{id}', [ClientController::class, 'edit'])->name('api.client.edit');
Route::get('client/delete/{id}', [ClientController::class, 'delete'])->name('api.client.delete');

##Purchase Invoice
Route::get('purchase-invoice/list-datatable', [PurchaseInvoiceController::class, 'list_datatable'])->name('api.purchase_invoice.list_datatable');
Route::get('purchase-invoice/detail/{id}', [PurchaseInvoiceController::class, 'detail'])->name('api.purchase_invoice.detail');
Route::post('purchase-invoice/create', [PurchaseInvoiceController::class, 'create'])->name('api.purchase_invoice.create');
Route::post('purchase-invoice/edit/{id}', [PurchaseInvoiceController::class, 'edit'])->name('api.purchase_invoice.edit');
Route::get('purchase-invoice/delete/{id}', [PurchaseInvoiceController::class, 'delete'])->name('api.purchase_invoice.delete');
Route::post('purchase-invoice/set-is-paid/{id}', [PurchaseInvoiceController::class, 'set_is_paid'])->name('api.purchase_invoice.set_is_paid');
Route::post('purchase-invoice/set-is-accountant/{id}', [PurchaseInvoiceController::class, 'set_is_accountant'])->name('api.purchase_invoice.set_is_accountant');

##Purchase Invoice Item
Route::get('purchase-invoice-item/list-short-term', [PurchaseInvoiceItemController::class, 'list_short_term'])->name('api.purchase_invoice_item.list_short_term');
Route::get('purchase-invoice-item/list-long-term', [PurchaseInvoiceItemController::class, 'list_long_term'])->name('api.purchase_invoice_item.list_long_term');
Route::get('purchase-invoice-item/list-fix-asset', [PurchaseInvoiceItemController::class, 'list_fix_asset'])->name('api.purchase_invoice_item.list_fix_asset');
Route::get('purchase-invoice-item/list-datatable-filter', [PurchaseInvoiceItemController::class, 'list_datatable_filter'])->name('api.purchase_invoice_item.list_datatable_filter');
Route::get('purchase-invoice-item/detail/{id}', [PurchaseInvoiceItemController::class, 'detail'])->name('api.purchase_invoice_item.detail');
Route::post('purchase-invoice-item/create', [PurchaseInvoiceItemController::class, 'create'])->name('api.purchase_invoice_item.create');
Route::post('purchase-invoice-item/edit/{id}', [PurchaseInvoiceItemController::class, 'edit'])->name('api.purchase_invoice_item.edit');
Route::get('purchase-invoice-item/delete/{id}', [PurchaseInvoiceItemController::class, 'delete'])->name('api.purchase_invoice_item.delete');

##Purchase Invoice Rental
Route::get('purchase-invoice-rental/list-datatable-filter', [PurchaseInvoiceRentalController::class, 'list_datatable_filter'])->name('api.purchase_invoice_rental.list_datatable_filter');
Route::get('purchase-invoice-rental/detail/{id}', [PurchaseInvoiceRentalController::class, 'detail'])->name('api.purchase_invoice_rental.detail');
Route::post('purchase-invoice-rental/create', [PurchaseInvoiceRentalController::class, 'create'])->name('api.purchase_invoice_rental.create');
Route::post('purchase-invoice-rental/edit/{id}', [PurchaseInvoiceRentalController::class, 'edit'])->name('api.purchase_invoice_rental.edit');
Route::get('purchase-invoice-rental/delete/{id}', [PurchaseInvoiceRentalController::class, 'delete'])->name('api.purchase_invoice_rental.delete');

##Rental
Route::get('rental/list-not-in-bundle/{rentalId}/{bundleId}', [RentalController::class, 'list_not_in_bundle'])->name('api.rental.list_not_in_bundle');
Route::get('rental/list-available', [RentalController::class, 'list_available'])->name('api.rental.list_available');
Route::get('rental/list-all', [RentalController::class, 'list_all'])->name('api.rental.list_all');
Route::get('rental/list-datatable', [RentalController::class, 'list_datatable'])->name('api.rental.list_datatable');
Route::get('rental/detail/{id}', [RentalController::class, 'detail'])->name('api.rental.detail');
Route::post('rental/create', [RentalController::class, 'create'])->name('api.rental.create');
Route::post('rental/edit/{id}', [RentalController::class, 'edit'])->name('api.rental.edit');
Route::get('rental/delete/{id}', [RentalController::class, 'delete'])->name('api.rental.delete');
Route::post('rental/save-image', [RentalController::class, 'save_image'])->name('api.rental.save_image');
##Rental Bundles
Route::get('bundle/list-all', [RentalBundlesController::class, 'list_all'])->name('api.bundles.list_all');
Route::get('bundle/list-datatable', [RentalBundlesController::class, 'list_datatable'])->name('api.bundles.list_datatable');
Route::get('bundle/detail/{id}', [RentalBundlesController::class, 'detail'])->name('api.bundles.detail');
Route::post('bundle/create', [RentalBundlesController::class, 'create'])->name('api.bundles.create');
Route::post('bundle/edit/{id}', [RentalBundlesController::class, 'edit'])->name('api.bundles.edit');
Route::get('bundle/delete/{id}', [RentalBundlesController::class, 'delete'])->name('api.bundles.delete');

##Bundle Rentals
Route::get('bundle-rentals/list-datatable-filter', [BundleRentalsController::class, 'list_datatable_filter'])->name('api.bundle_rentals.list_datatable_filter');
Route::get('bundle-rentals/detail/{id}', [BundleRentalsController::class, 'detail'])->name('api.bundle_rentals.detail');
Route::post('bundle-rentals/create', [BundleRentalsController::class, 'create'])->name('api.bundle_rentals.create');
Route::post('bundle-rentals/edit/{id}', [BundleRentalsController::class, 'edit'])->name('api.bundle_rentals.edit');
Route::get('bundle-rentals/delete/{id}', [BundleRentalsController::class, 'delete'])->name('api.bundle_rentals.delete');

##Service
Route::get('services/list-all', [ServiceController::class, 'list_all'])->name('api.service.list_all');
Route::get('service/list-datatable', [ServiceController::class, 'list_datatable'])->name('api.service.list_datatable');
Route::get('service/detail/{id}', [ServiceController::class, 'detail'])->name('api.service.detail');
Route::post('service/create', [ServiceController::class, 'create'])->name('api.service.create');
Route::post('service/edit/{id}', [ServiceController::class, 'edit'])->name('api.service.edit');
Route::get('service/delete/{id}', [ServiceController::class, 'delete'])->name('api.service.delete');

##Project
Route::get('project/list-datatable', [ProjectController::class, 'list_datatable'])->name('api.project.list_datatable');
Route::get('project/detail/{id}', [ProjectController::class, 'detail'])->name('api.project.detail');
Route::get('project/labels/{id}', [ProjectController::class, 'labels'])->name('api.project.labels');
Route::post('project/create', [ProjectController::class, 'create'])->name('api.project.create');
Route::post('project/edit/{id}', [ProjectController::class, 'edit'])->name('api.project.edit');
Route::get('project/delete/{id}', [ProjectController::class, 'delete'])->name('api.project.delete');
Route::get('project/close-project/{id}', [ProjectController::class, 'close_project'])->name('api.project.close_project');
Route::get('project/change-status-preparing/{id}', [ProjectController::class, 'change_status_preparing'])->name('api.project.change_status_preparing');
Route::get('project/change-status-ongoing/{id}', [ProjectController::class, 'change_status_ongoing'])->name('api.project.change_status_ongoing');
Route::get('project/change-status-completed/{id}', [ProjectController::class, 'change_status_completed'])->name('api.project.change_status_completed');
Route::get('project/project-info-pdf/{id}', [ProjectController::class, 'project_info_pdf'])->name('api.project.project_info_pdf');
Route::get('project/rent-contract-pdf/{id}', [ProjectController::class, 'rent_contract_pdf'])->name('api.project.rent_contract_pdf');
Route::get('project/aktas-pdf/{id}', [ProjectController::class, 'aktas_pdf'])->name('api.project.aktas_pdf');

##Project Expense
Route::get('project-expense/list-datatable-filter', [ProjectExpenseController::class, 'list_datatable_filter'])->name('api.project_expense.list_datatable_filter');
Route::post('project-expense/create', [ProjectExpenseController::class, 'create'])->name('api.project_expense.create');
Route::get('project-expense/delete/{id}', [ProjectExpenseController::class, 'delete'])->name('api.project_expense.delete');

##Project Rental
Route::get('project-rental/list-datatable-filter', [ProjectRentalController::class, 'list_datatable_filter'])->name('api.project_rental.list_datatable_filter');
Route::get('project-rental/detail/{id}', [ProjectRentalController::class, 'detail'])->name('api.project_rental.detail');
Route::post('project-rental/create', [ProjectRentalController::class, 'create'])->name('api.project_rental.create');
Route::post('project-rental/edit/{id}', [ProjectRentalController::class, 'edit'])->name('api.project_rental.edit');
Route::get('project-rental/delete/{id}', [ProjectRentalController::class, 'delete'])->name('api.project_rental.delete');
Route::post('project-rental/set-returned/{id}', [ProjectRentalController::class, 'set_returned'])->name('api.project_rental.set_returned');

##Project Bundle
Route::get('project-bundle/list-by-project/{project_id}', [ProjectBundleController::class, 'list_by_project'])->name('api.project_bundle.list_by_project');
Route::post('project-bundle/create', [ProjectBundleController::class, 'create'])->name('api.project_bundle.create');
Route::get('project-bundle/delete/{id}', [ProjectBundleController::class, 'delete'])->name('api.project_bundle.delete');

##Project Income
Route::get('project-income/list-datatable-filter', [ProjectIncomeController::class, 'list_datatable_filter'])->name('api.project_income.list_datatable_filter');
Route::post('project-income/create', [ProjectIncomeController::class, 'create'])->name('api.project_income.create');
Route::get('project-income/delete/{id}', [ProjectIncomeController::class, 'delete'])->name('api.project_income.delete');

##Project User
Route::get('project-user/list-datatable-filter', [ProjectOwnerController::class, 'list_datatable_filter'])->name('api.project_user.list_datatable_filter');
Route::get('project-user/detail/{id}', [ProjectOwnerController::class, 'detail'])->name('api.project_user.detail');
Route::post('project-user/create', [ProjectOwnerController::class, 'create'])->name('api.project_user.create');
Route::post('project-user/edit/{id}', [ProjectOwnerController::class, 'edit'])->name('api.project_user.edit');
Route::get('project-user/delete/{id}', [ProjectOwnerController::class, 'delete'])->name('api.project_user.delete');

##Project Services
Route::get('project-services/list-datatable-filter', [ProjectServiceController::class, 'list_datatable_filter'])->name('api.project-services.list_datatable_filter');
Route::get('project-services/detail/{id}', [ProjectServiceController::class, 'detail'])->name('api.project_services.detail');
Route::post('project-services/create', [ProjectServiceController::class, 'create'])->name('api.project_services.create');
Route::post('project-services/edit/{id}', [ProjectServiceController::class, 'edit'])->name('api.project_services.edit');
Route::get('project-services/delete/{id}', [ProjectServiceController::class, 'delete'])->name('api.project_services.delete');

##Project Document
Route::get('project-document/list-by-project/{project_id}', [ProjectDocumentController::class, 'list_by_project'])->name('api.project_document.list_by_project');
Route::post('project-document/create', [ProjectDocumentController::class, 'create'])->name('api.project_document.create');
Route::get('project-document/delete/{id}', [ProjectDocumentController::class, 'delete'])->name('api.project_document.delete');

##Invoice Serie
Route::get('invoice-serie/list-all', [SystemInvoiceSerieController::class, 'list_all'])->name('api.invoice_serie.list_all');
Route::get('invoice-serie/list-datatable', [SystemInvoiceSerieController::class, 'list_datatable'])->name('api.invoice_serie.list_datatable');
Route::post('invoice-serie/create', [SystemInvoiceSerieController::class, 'create'])->name('api.invoice_serie.create');
Route::get('invoice-serie/delete/{id}', [SystemInvoiceSerieController::class, 'delete'])->name('api.invoice_serie.delete');

##Offer Serie
Route::get('offer-serie/list-all', [SystemOfferSerieController::class, 'list_all'])->name('api.offer_serie.list_all');
Route::get('offer-serie/list-datatable', [SystemOfferSerieController::class, 'list_datatable'])->name('api.offer_serie.list_datatable');
Route::post('offer-serie/create', [SystemOfferSerieController::class, 'create'])->name('api.offer_serie.create');
Route::get('offer-serie/delete/{id}', [SystemOfferSerieController::class, 'delete'])->name('api.offer_serie.delete');


##Currency
Route::get('currency/list-all', [SystemCurrencyController::class, 'list_all'])->name('api.currency.list_all');
Route::get('currency/list-datatable', [SystemCurrencyController::class, 'list_datatable'])->name('api.currency.list_datatable');
Route::get('currency/detail/{id}', [SystemCurrencyController::class, 'detail'])->name('api.currency.detail');
Route::post('currency/create', [SystemCurrencyController::class, 'create'])->name('api.currency.create');
Route::post('currency/edit/{id}', [SystemCurrencyController::class, 'edit'])->name('api.currency.edit');
Route::get('currency/delete/{id}', [SystemCurrencyController::class, 'delete'])->name('api.currency.delete');

##labels
Route::get('labels/list-all', [LabelController::class, 'list_all'])->name('api.labels.list_all');
Route::get('labels/list-datatable', [LabelController::class, 'list_datatable'])->name('api.labels.list_datatable');
Route::get('labels/detail/{id}', [LabelController::class, 'detail'])->name('api.labels.detail');
Route::post('labels/create', [LabelController::class, 'create'])->name('api.labels.create');
Route::post('labels/edit/{id}', [LabelController::class, 'edit'])->name('api.labels.edit');
Route::get('labels/delete/{id}', [LabelController::class, 'delete'])->name('api.labels.delete');

##Statuses
Route::get('status/list-all', [ProjectStatusController::class, 'list_all'])->name('api.status.list_all');
Route::get('status/list-datatable', [ProjectStatusController::class, 'list_datatable'])->name('api.status.list_datatable');
Route::get('status/detail/{id}', [ProjectStatusController::class, 'detail'])->name('api.status.detail');
Route::post('status/create', [ProjectStatusController::class, 'create'])->name('api.status.create');
Route::post('status/edit/{id}', [ProjectStatusController::class, 'edit'])->name('api.status.edit');
Route::get('status/delete/{id}', [ProjectStatusController::class, 'delete'])->name('api.status.delete');


##Invoice
Route::post('invoice/check-invoice-serie', [InvoiceController::class, 'check_invoice_serie'])->name('api.invoice.check_invoice_serie');
Route::post('invoice/new-invoice-no', [InvoiceController::class, 'new_invoice_no'])->name('api.invoice.new_invoice_no');
Route::get('invoice/list-all', [InvoiceController::class, 'list_all'])->name('api.invoice.list_all');
Route::get('invoice/list-datatable', [InvoiceController::class, 'list_datatable'])->name('api.invoice.list_datatable');
Route::get('invoice/detail/{id}', [InvoiceController::class, 'detail'])->name('api.invoice.detail');
Route::post('invoice/create', [InvoiceController::class, 'create'])->name('api.invoice.create');
Route::get('invoice/details-from-project/{id}', [InvoiceController::class, 'details_from_project'])->name('api.invoice.details_from_project');
Route::post('invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('api.invoice.edit');
Route::get('invoice/preview/{id}', [InvoiceController::class, 'preview'])->name('api.invoice.preview');
Route::get('invoice/download/{id}', [InvoiceController::class, 'download'])->name('api.invoice.download');
Route::get('invoice/sendmail/{id}', [InvoiceController::class, 'sendmail'])->name('api.invoice.sendmail');
Route::post('invoice/set-is-paid/{id}', [InvoiceController::class, 'set_is_paid'])->name('api.invoice.set_is_paid');
Route::post('invoice/set-is-accountant/{id}', [InvoiceController::class, 'set_is_accountant'])->name('api.invoice.set_is_accountant');
##Invoice Item
Route::get('invoice-item/list-filter', [InvoiceItemController::class, 'list_filter'])->name('api.invoice_item.list_filter');

##Offer
Route::post('offer/check-offer-serie', [OfferController::class, 'check_offer_serie'])->name('api.offer.check_offer_serie');
Route::post('offer/new-offer-no', [OfferController::class, 'new_offer_no'])->name('api.offer.new_offer_no');
Route::get('offer/list-all', [OfferController::class, 'list_all'])->name('api.offer.list_all');
Route::get('offer/list-datatable', [OfferController::class, 'list_datatable'])->name('api.offer.list_datatable');
Route::get('offer/detail/{id}', [OfferController::class, 'detail'])->name('api.offer.detail');
Route::post('offer/create', [OfferController::class, 'create'])->name('api.offer.create');
Route::get('offer/details-from-project/{id}', [OfferController::class, 'details_from_project'])->name('api.offer.details_from_project');
Route::post('offer/edit/{id}', [OfferController::class, 'edit'])->name('api.offer.edit');
Route::get('offer/preview/{id}', [OfferController::class, 'preview'])->name('api.offer.preview');
Route::get('offer/download/{id}', [OfferController::class, 'download'])->name('api.offer.download');
Route::get('offer/sendmail/{id}', [OfferController::class, 'sendmail'])->name('api.offer.sendmail');
Route::post('offer/set-is-send/{id}', [OfferController::class, 'set_is_send'])->name('api.offer.set_is_send');
##Offer Item
Route::get('offer-item/list-filter', [offerItemController::class, 'list_filter'])->name('api.offer_item.list_filter');


##Company Expense
Route::get('company-expense/list-datatable', [CompanyExpenseController::class, 'list_datatable'])->name('api.company_expense.list_datatable');
Route::post('company-expense/create', [CompanyExpenseController::class, 'create'])->name('api.company_expense.create');
Route::post('company-expense/edit/{id}', [CompanyExpenseController::class, 'edit'])->name('api.company_expense.edit');
Route::get('company-expense/delete/{id}', [CompanyExpenseController::class, 'delete'])->name('api.company_expense.delete');

##User Payment
Route::get('user-payment/list-datatable', [UserPaymentController::class, 'list_datatable'])->name('api.user_payment.list_datatable');
Route::post('user-payment/create-cash', [UserPaymentController::class, 'create_cash'])->name('api.user_payment.create_cash');
Route::post('user-payment/create-item', [UserPaymentController::class, 'create_item'])->name('api.user_payment.create_item');
Route::post('user-payment/edit/{id}', [UserPaymentController::class, 'edit'])->name('api.user_payment.edit');
Route::get('user-payment/delete/{id}', [UserPaymentController::class, 'delete'])->name('api.user_payment.delete');

##Fix Asset
Route::get('fix-asset/list/datatable', [FixAssetController::class, 'list_datatable'])->name('api.fix_asset.list_datatable');
Route::get('fix-asset/detail/{id}', [FixAssetController::class, 'detail'])->name('api.fix_asset.detail');
Route::post('fix-asset/create', [FixAssetController::class, 'create'])->name('api.fix_asset.create');
Route::post('fix-asset/edit/{id}', [FixAssetController::class, 'edit'])->name('api.fix_asset.edit');
Route::get('fix-asset/delete/{id}', [FixAssetController::class, 'delete'])->name('api.fix_asset.delete');

##Warehouse 
Route::post('warehouse/list-datatable-filter', [WarehouseController::class, 'list_datatable_filter'])->name('api.warehouse.list_datatable_filter');

##System
Route::post('system-file/upload', [SystemFileController::class, 'upload'])->name('api.file.upload');
Route::get('system-file/list-datatable', [SystemFileController::class, 'list_datatable'])->name('api.file.list_datatable');
Route::get('system-permission/list-all', [SystemPermissionController::class, 'list_all'])->name('api.system_permission.list_all');
Route::post('system-definition/list-filter', [SystemDefinitionController::class, 'list_filter'])->name('api.definition.list_filter');

##Setting
Route::get('setting/detail', [SystemSettingController::class, 'detail'])->name('api.setting.detail');
Route::post('setting/save-logo', [SystemSettingController::class, 'save_logo'])->name('api.setting.save_logo');
Route::post('setting/save-company', [SystemSettingController::class, 'save_company'])->name('api.setting.save_company');
