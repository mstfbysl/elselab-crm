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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('title');
            $table->unsignedInteger('status_id')->nullable(true);
            $table->unsignedInteger('core_status')->default(1);
            $table->unsignedInteger('total_ownerness')->default(100);
            $table->text('comments')->nullable(true);
            $table->date('start_date')->nullable(true);
            $table->date('finish_date')->nullable(true);
            $table->unsignedInteger('used_ownerness')->default(0);
            $table->datetime('deleted_at')->nullable(true);    
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
        Schema::dropIfExists('projects');
    }
};
