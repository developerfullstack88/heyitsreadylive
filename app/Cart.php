<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function item(){
      return $this->belongsTo(Menu::class);
    }

    public function company(){
      return $this->belongsTo(Company::class);
    }
    public function category(){
      return $this->belongsTo(Category::class);
    }

    public function restaurant_menu(){
      return $this->belongsTo(RestaurantMenu::class);
    }

}
