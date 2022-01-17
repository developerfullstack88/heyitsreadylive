<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $guarded = ['id','created_at','updated_at'];

    public function site(){
      return $this->hasMany(Site::class);
    }

    public function order(){
      return $this->hasMany(Order::class);
    }
}
