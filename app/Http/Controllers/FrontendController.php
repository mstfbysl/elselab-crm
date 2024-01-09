<?php

namespace App\Http\Controllers;

// use App\Libraries\SumToText;
class FrontendController extends Controller
{

    public function index()
    {   
        return view('project.index');
    }

    /** Dashboard */
    public function dashboard()
    {   
        return view('dashboard.index');
    }

    /** Settings */
    public function settings()
    {
        return view('administration.setting.index');
    }

    /** Files */
    public function files()
    {
        return view('administration.file.index');
    }
    
    /** Invoice Series */
    public function invoice_series()
    {
        return view('administration.invoice_serie.index');
    }
    
    /** Currencies */
    public function currencies()
    {
        return view('administration.currency.index');
    }
    
    /** User */
    public function users()
    {
        return view('administration.user.index');
    }

    /** Role */
    public function roles()
    {
        return view('administration.role.index');
    }

    /** Account Settings */
    public function account_settings()
    {
        return view('account.settings');
    }

    public function account_security()
    {
        return view('account.security');
    }

    public function account_theme()
    {
        return view('account.theme');
    }

    /** Clients */
    public function clients()
    {
        return view('client.index');
    }
    
    /** Purchase Invoices */
    public function purchase_invoices()
    {
        return view('purchase_invoice.index');
    }

    public function purchase_invoice_edit()
    {
        return view('purchase_invoice.edit');
    }

    /** Rentals */
    public function rentals()
    {
        return view('rentals.index');
    }

    /** Warehouse */
    public function warehouse()
    {
        return view('warehouse.index');
    }

    /** Projects */
    public function projects()
    {
        return view('project.index');
    }

    public function project_edit()
    {
        return view('project.edit');
    }

    /** Invoices */
    public function invoices()
    {
        return view('invoice.list');
    }

    public function invoice_create()
    {
        return view('invoice.create');
    }

    public function invoice_edit()
    {
        return view('invoice.edit');
    }

    public function invoice_project()
    {
        return view('invoice.from_project');
    }

    /** Company Expenses */
    public function company_expenses()
    {
        return view('company_expense.index');
    }

    /** User Payments */
    public function user_payments()
    {
        return view('user_payment.index');
    }

    /** Fix Assets */
    public function fix_assets()
    {
        return view('fix_asset.index');
    }

    /** Services */
    public function services()
    {
        return view('services.index');
    }
    
    /** Labels */
    public function labels()
    {
        return view('administration.labels.index');
    }

    public function statuses()
    {
        return view('administration.statuses.index');
    }

    /** Offers */
    public function offers()
    {
        return view('offer.list');
    }

    public function offer_create()
    {
        return view('offer.create');
    }

    public function offer_edit()
    {
        return view('offer.edit');
    }

    public function offer_project()
    {
        return view('offer.from_project');
    }
}