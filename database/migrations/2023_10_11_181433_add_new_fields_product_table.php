<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->integer('unit_id')->nullable()->after('name');
            $table->integer('category_id')->nullable()->after('unit_id');
            $table->integer('unit_in_stock')->nullable()->after('category_id');
            $table->float('unit_price', 8, 2)->nullable()->after('unit_in_stock');
            $table->float('discount_percentage', 8, 2)->nullable()->after('unit_price');
            $table->string('created_by')->nullable()->after('discount_percentage');
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
            $table->dropColumn('code');
            $table->dropColumn('unit_id');
            $table->dropColumn('category_id');
            $table->dropColumn('unit_in_stock');
            $table->dropColumn('unit_price');
            $table->dropColumn('discount_percentage');
            $table->dropColumn('created_by');
        });
    }
}
