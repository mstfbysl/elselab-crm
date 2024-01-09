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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Juridinis', 'Privatus']);
            $table->string('name');
            $table->string('code')->nullable(true);
            $table->string('address');
            $table->string('phone')->nullable(true);;
            $table->string('email')->nullable(true);;
            $table->string('vat_number')->nullable(true);
            $table->string('comment')->nullable(true);
            $table->string('contract')->nullable(true);
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
        Schema::dropIfExists('clients');
    }
};
