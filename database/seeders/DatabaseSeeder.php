<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SystemSetting::create([
            'app_name' => 'Onto CRM',
            'company_is_vat_member' => 1,
            'company_currency' => 1,
            'company_vat_number' => 'LT100015521918',
            'company_name' => 'MB OntoLT',
            'company_code' => '306136330',
            'company_address' => 'Santariškių g. 117-5, LT-08465 Vilnius',
            'company_email' => 'info@onto.lt',
            'company_phone' => '+370 676 93333',
            'company_bank_name' => 'Revolut Bank UAB',
            'company_bank_iban' => 'LT24 3250 0478 1846 3475',
            'company_bank_code' => 'REVOLT21'
        ]);

        \App\Models\SystemDefinitions::create(['type' => 'is_vat_member', 'title' => 'Yes']);
        \App\Models\SystemDefinitions::create(['type' => 'is_vat_member', 'title' => 'No']);

        \App\Models\SystemDefinitions::create(['type' => 'client_type', 'title' => 'Legal', 'is_custom' => 1, 'value' => 'Legal']);
        \App\Models\SystemDefinitions::create(['type' => 'client_type', 'title' => 'Private', 'is_custom' => 1, 'value' => 'Private']);

        \App\Models\SystemDefinitions::create(['type' => 'invoice_type', 'title' => 'Standart', 'is_custom' => 1, 'value' => 1]);
        \App\Models\SystemDefinitions::create(['type' => 'invoice_type', 'title' => 'Preliminary', 'is_custom' => 1, 'value' => 2]);
        \App\Models\SystemDefinitions::create(['type' => 'invoice_type', 'title' => 'Credit', 'is_custom' => 1, 'value' => 3]);

        \App\Models\SystemDefinitions::create(['type' => 'purchase_invoice_item_type', 'title' => 'Pardavimui', 'is_custom' => 1, 'value' => 'Pardavimui']);
        \App\Models\SystemDefinitions::create(['type' => 'purchase_invoice_item_type', 'title' => 'Naudojimui', 'is_custom' => 1, 'value' => 'Naudojimui']);

        \App\Models\SystemDefinitions::create(['type' => 'user_payment_type', 'title' => 'Prekė/Paslauga', 'is_custom' => 1, 'value' => 'Item']);
        \App\Models\SystemDefinitions::create(['type' => 'user_payment_type', 'title' => 'Kuras', 'is_custom' => 1, 'value' => 'Fuel']);
        \App\Models\SystemDefinitions::create(['type' => 'user_payment_type', 'title' => 'Atlyginimas', 'is_custom' => 1, 'value' => 'Cash']);



        \App\Models\SystemPermission::create(['id' => 1, 'title' => 'Dashboard']);

        \App\Models\SystemPermission::create(['id' => 2, 'title' => 'Administration']);
        \App\Models\SystemPermission::create(['id' => 3, 'title' => 'Setting']);
        //\App\Models\SystemPermission::create(['id' => 4, 'title' => 'Files']);
        \App\Models\SystemPermission::create(['id' => 5, 'title' => 'Invoice Series']);
        \App\Models\SystemPermission::create(['id' => 6, 'title' => 'Currencies']);
        \App\Models\SystemPermission::create(['id' => 7, 'title' => 'Users']);
        \App\Models\SystemPermission::create(['id' => 8, 'title' => 'Roles']);

        \App\Models\SystemPermission::create(['id' => 9, 'title' => 'Clients']);
        \App\Models\SystemPermission::create(['id' => 10, 'title' => 'Create Client']);

        \App\Models\SystemPermission::create(['id' => 13, 'title' => 'Purchase Invoices']);
        \App\Models\SystemPermission::create(['id' => 14, 'title' => 'Create Purchase Invoice']);

        \App\Models\SystemPermission::create(['id' => 17, 'title' => 'Warehouse']);

        \App\Models\SystemPermission::create(['id' => 18, 'title' => 'Rentals']);
        \App\Models\SystemPermission::create(['id' => 19, 'title' => 'Create Rental']);

        \App\Models\SystemPermission::create(['id' => 22, 'title' => 'Projects']);
        \App\Models\SystemPermission::create(['id' => 23, 'title' => 'Create Project']);

        \App\Models\SystemPermission::create(['id' => 26, 'title' => 'Invoices']);
        \App\Models\SystemPermission::create(['id' => 27, 'title' => 'Create Invoice']);

        \App\Models\SystemPermission::create(['id' => 32, 'title' => 'Company Expenses']);
        \App\Models\SystemPermission::create(['id' => 33, 'title' => 'Create Company Expense']);

        \App\Models\SystemPermission::create(['id' => 35, 'title' => 'User Payments']);
        \App\Models\SystemPermission::create(['id' => 36, 'title' => 'Create User Payment']);

        \App\Models\SystemPermission::create(['id' => 38, 'title' => 'Fix Assets']);
        \App\Models\SystemPermission::create(['id' => 39, 'title' => 'Create Fix Asset']);

        \App\Models\SystemPermission::create(['id' => 40, 'title' => 'Services']);
        \App\Models\SystemPermission::create(['id' => 41, 'title' => 'Create Service']);
        \App\Models\SystemPermission::create(['id' => 42, 'title' => 'Edit Service']);

        \App\Models\SystemPermission::create(['id' => 50, 'title' => 'Numbers / Profits']);

        \App\Models\Role::create(['title' => 'Admin']);
        \App\Models\Role::create(['title' => 'Accountant']);
        \App\Models\Role::create(['title' => 'Project Manager']);
        \App\Models\Role::create(['title' => 'Standart User']);

        \App\Models\SystemInvoiceSerie::create([
            'slug' => 'MK'
        ]);

        \App\Models\SystemOfferSerie::create([
            'slug' => 'PS'
        ]);


        \App\Models\SystemCurrency::create([
            'short_code' => 'EUR',
            'description' => 'Euro',
            'symbol' => '€'
        ]);

        \App\Models\SystemLabel::create([
            'title' => 'Onto',
            'color' => '#ffaacc'
        ]);

        \App\Models\SystemLabel::create([
            'title' => 'ACI',
            'color' => '#00ff00'
        ]);

        \App\Models\SystemLabel::create([
            'title' => 'ElseLab',
            'color' => '#00ff00'
        ]);

        \App\Models\ProjectStatus::create([
            'title' => 'Planning',
            'color' => '#08a0b4'
        ]);

        \App\Models\ProjectStatus::create([
            'title' => 'On Going',
            'color' => '#4f9d1b'
        ]);

        \App\Models\ProjectStatus::create([
            'title' => 'Finished',
            'color' => '#4f9d1b'
        ]);

        \App\Models\ProjectStatus::create([
            'title' => 'Need attention',
            'color' => '#711414'
        ]);

        $user_id = \App\Models\User::create([
            'is_main_admin' => 1,
            'user_role_id' => 1,
            'name_surname' => 'Marius Kirkliauskas',
            'email' => 'marius@onto.lt',
            'password' => Hash::make('marelis')
        ]);

        \App\Models\Client::create([
            'name' => 'VšĮ Just Projects',
            'code' => '303043967',
            'address' => 'Santariškių g. 117-5, LT-08465 Vilnius',
            'vat_number' => 'LT100013608310'
        ]);
    }
}
