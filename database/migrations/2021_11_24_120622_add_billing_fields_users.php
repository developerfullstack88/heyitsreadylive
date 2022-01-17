<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingFieldsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('street_address')->after('country')->nullable();
          $table->string('line2_address')->after('street_address')->nullable();
          $table->string('state')->after('line2_address')->nullable();
          $table->string('city')->after('state')->nullable();
          $table->string('zip_code')->after('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropcolumn('street_address');
          $table->dropcolumn('line2_address');
          $table->dropcolumn('state');
          $table->dropcolumn('city');
          $table->dropcolumn('zip_code');
        });
    }
}
