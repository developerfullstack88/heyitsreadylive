<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IosHelpController extends Controller
{
    public function index(){
      return view('ios.index');
    }
}
