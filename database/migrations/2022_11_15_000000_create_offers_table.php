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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('created_user_id');
            $table->string('offer_no')->unique('offer_no_uniq_index');
            $table->date('date');
            $table->unsignedDouble('sub_total');
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedDouble('tax');
            $table->unsignedInteger('is_send')->default(0);
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
        Schema::dropIfExists('offers');
    }
};
