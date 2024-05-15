<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('unit_in_stock');
            $table->string('product_barcode')->nullable()->after('code');
            $table->decimal('quantity', 10, 2)->nullable()->after('unit_price'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('cost');
            $table->dropColumn('product_barcode');
            $table->dropColumn('quantity');
        });
    }
}
