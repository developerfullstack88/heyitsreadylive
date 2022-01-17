<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
  protected $table = 'states';
  protected $guarded = ['id','created_at','updated_at'];
}
