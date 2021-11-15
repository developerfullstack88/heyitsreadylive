<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverImageSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('cover_image',100)->after('name')->nullable();
            $table->string('cover_image_thumbnail',100)->after('cover_image')->nullable();
            $table->string('category',191)->after('cover_image_thumbnail')->nullable();
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
          $table->dropColumn('cover_image');
          $table->dropColumn('cover_image_thumbnail');
          $table->dropColumn('category');
        });
    }
}
