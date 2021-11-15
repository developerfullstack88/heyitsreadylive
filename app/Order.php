<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function company(){
      return $this->belongsTo(Company::class);
    }

    public function payment(){
      return $this->hasOne(Payment::class);
    }
}
