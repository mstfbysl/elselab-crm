<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 1 - 20 Cost - 5 piece
    // 2 - 30 Cost - 3 piece
    // 3 - 15 Cost - 10 piece

    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('purchase_invoice_id');
            $table->enum('type', ['Pardavimui', 'Naudojimui']);
            $table->string('serial')->nullable(true);
            $table->string('title');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('used_quantity')->default(0);
            $table->unsignedDecimal('cost', 10, 2);
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
        Schema::dropIfExists('purchase_invoice_items');
    }
};
