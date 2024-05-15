<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblinvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblinvoice', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('payment_type')->length(1);
            $table->float('total_amount', 10,2);
            $table->float('amount_tendered', 10, 2)->nullable();
            $table->integer('created_by');
            $table->date('date_recorded')->nullable();
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
        Schema::dropIfExists('tblinvoice');
    }
}
