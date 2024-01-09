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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('is_main_admin')->default(0);
            $table->unsignedInteger('user_role_id');
            $table->string('name_surname')->default('bla bla');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('theme_color', ['light', 'semi-dark', 'dark'])->default('light');
            $table->enum('theme_layout', ['full', 'boxed'])->default('boxed');
            $table->enum('theme_nav_style', ['floating', 'sticky', 'static'])->default('floating');
            $table->enum('language', ['en', 'lt', 'tr'])->default('en');
            $table->unsignedInteger('profile_picture')->nullable(true);
            $table->unsignedInteger('profit_share')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
