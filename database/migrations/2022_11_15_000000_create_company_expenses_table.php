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
        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('purchase_invoice_item_id');
            $table->unsignedInteger('purchase_invoice_id');
            $table->unsignedInteger('quantity')->nullable(true);
            $table->unsignedDecimal('total', 10, 2);
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
        Schema::dropIfExists('company_expenses');
    }
};
