<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartEndTimeRestaurantMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
          $table->dropColumn('menu_time');
          $table->time('start_time',0)->after('company_id')->nullable();
          $table->time('end_time',0)->after('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
          $table->dropColumn('start_time');
          $table->dropColumn('end_time');
        });
    }
}
