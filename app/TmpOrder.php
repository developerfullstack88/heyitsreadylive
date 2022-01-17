<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TmpOrder extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
}
