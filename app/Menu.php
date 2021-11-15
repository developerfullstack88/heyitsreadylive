<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $hidden = array('quantity');
    public function category(){
      return $this->belongsTo(Category::class);
    }
}
