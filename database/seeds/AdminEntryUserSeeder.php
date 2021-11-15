<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminEntryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        'name' => "Super Admin",
        'email' => 'superadmin@gmail.com',
        'password' => bcrypt('12345678'),
        'role'=>'super admin'
      ]);
    }
}
