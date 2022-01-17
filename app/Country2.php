<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country2 extends Model
{
    protected $table = 'countries2';
    protected $guarded = ['id','created_at','updated_at'];
}
