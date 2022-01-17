<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableToPolygonWkt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
          DB::statement('ALTER TABLE `sites` CHANGE `polygon_wkt` `polygon_wkt` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
          DB::statement('ALTER TABLE `sites` CHANGE `center_wkt` `center_wkt` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
          DB::statement('ALTER TABLE `sites` CHANGE `polygon` `polygon` POLYGON NULL;');
          DB::statement('ALTER TABLE `sites` CHANGE `center` `center` POINT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}
