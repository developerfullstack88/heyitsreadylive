<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerWktConvertToBinary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
          DB::unprepared('CREATE TRIGGER `wkt_convert_to_binary` BEFORE INSERT ON `sites` FOR EACH ROW BEGIN
          	SET NEW.polygon=ST_GeomFromText(NEW.polygon_wkt);
              SET NEW.center=ST_Centroid(NEW.polygon);

              SET NEW.center_wkt=ST_AsText(NEW.center);
          END');
          DB::unprepared('CREATE TRIGGER `wkt_update` BEFORE UPDATE ON `sites` FOR EACH ROW BEGIN
        	SET NEW.polygon=ST_GeomFromText(NEW.polygon_wkt);
            SET NEW.center=ST_Centroid(NEW.polygon);
            SET NEW.center_wkt=ST_AsText(NEW.center);
        END');
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
            DB::unprepared('DROP TRIGGER `wkt_convert_to_binary`');
            DB::unprepared('DROP TRIGGER `wkt_update`');
        });
    }
}
