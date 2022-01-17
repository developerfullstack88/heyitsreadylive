<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AndroidHelpController extends Controller
{
    public function index(){
      return view('android.index');
    }
}
