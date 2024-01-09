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
        Schema::create('system_offer_serie_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('serie_id');
            $table->year('year');
            $table->unsignedInteger('count');
            $table->unsignedInteger('offer_id');
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
        Schema::dropIfExists('system_offer_serie_counters');
    }
};
