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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('created_user_id');
            $table->string('title');
            $table->string('serie');
            $table->date('date');
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedDouble('total_with_vat');
            $table->unsignedDouble('used_total')->default(0);
            $table->unsignedInteger('is_paid')->default(0);
            $table->unsignedInteger('is_accountant')->default(0);
            $table->string('document')->nullable(true);
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
        Schema::dropIfExists('purchase_invoices');
    }
};
