<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('company_name')->after('password')->nullable();
            $table->string('company_website')->after('company_name')->nullable();
            $table->string('phone_number')->after('company_website')->nullable();
            $table->string('country')->after('last_name')->nullable();
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
          $table->dropcolumn('first_name');
          $table->dropcolumn('last_name');
          $table->dropcolumn('company_name');
          $table->dropcolumn('company_website');
          $table->dropcolumn('phone_number');
          $table->dropcolumn('country');
        });
    }
}
