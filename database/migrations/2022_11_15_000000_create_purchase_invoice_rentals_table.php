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
        Schema::create('purchase_invoice_rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('purchase_invoice_id');
            $table->unsignedInteger('rental_id');
            $table->string('serial')->nullable(true);
            $table->string('title');
            $table->unsignedDecimal('cost', 10, 2);
            $table->unsignedInteger('quantity');
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
        Schema::dropIfExists('purchase_invoice_rentals');
    }
};
