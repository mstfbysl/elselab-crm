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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->string('title');
            $table->string('description')->nullable(true);
            $table->unsignedDecimal('cost', 10, 2);
            $table->unsignedInteger('tax');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('is_used')->default(0);
            $table->unsignedInteger('is_used_by')->nullable(true);
            $table->date('used_date')->nullable(true);
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
        Schema::dropIfExists('invoice_items');
    }
};
