<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
  protected $table = 'taxes';
  protected $guarded = ['id','created_at','updated_at'];
}
