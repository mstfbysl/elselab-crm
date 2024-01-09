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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('created_user_id');
            $table->string('invoice_no')->unique('invoice_no_uniq_index');
            $table->enum('type', ['Standartinė', 'Išankstinė', 'Kreditinė']);
            $table->date('date');
            $table->unsignedDouble('sub_total');
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedDouble('tax');
            $table->unsignedInteger('is_paid')->default(0);
            $table->unsignedInteger('is_accountant')->default(0);
            $table->string('note')->nullable(true);
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
        Schema::dropIfExists('invoices');
    }
};
