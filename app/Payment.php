<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $table = 'payments';
  protected $guarded = ['id','created_at','updated_at'];

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function order(){
    return $this->belongsTo(Order::class);
  }


}
