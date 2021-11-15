<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
  /**
  * The attributes that aren't mass assignable.
  *
  * @var array
  */
  protected $guarded = [];

  public function company(){
    return $this->belongsTo(Company::class);
  }
}
