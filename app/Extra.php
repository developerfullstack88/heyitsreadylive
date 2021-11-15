<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
  protected $table = 'extras';
  protected $guarded = ['id','created_at','updated_at'];
  protected $hidden = array('quantity');
}
