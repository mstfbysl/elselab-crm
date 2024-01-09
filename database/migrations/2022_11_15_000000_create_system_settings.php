<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->unsignedInteger('logo')->nullable(true);
            $table->unsignedInteger('favicon')->nullable(true);
            $table->string('company_is_vat_member')->nullable(true);
            $table->string('company_vat_number')->nullable(true);
            $table->string('company_currency')->nullable(true);
            $table->string('company_name')->nullable(true);
            $table->string('company_code')->nullable(true);
            $table->string('company_address')->nullable(true);
            $table->string('company_phone')->nullable(true);
            $table->string('company_email')->nullable(true);
            $table->string('company_bank_name')->nullable(true);
            $table->string('company_bank_iban')->nullable(true);
            $table->string('company_bank_code')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
