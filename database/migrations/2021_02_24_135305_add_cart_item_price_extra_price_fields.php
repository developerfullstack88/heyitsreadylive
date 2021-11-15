<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCartItemPriceExtraPriceFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
          $table->float('item_price', 10, 2)->nullable()->after('total_price')->default(0);
          $table->float('extra_price', 10, 2)->nullable()->after('total_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
          $table->dropColumn('item_price');
          $table->dropColumn('extra_price');
        });
    }
}
