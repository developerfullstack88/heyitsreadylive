<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Hash;

/*
@developer:jasmaninder
@controller:DashboardController
@description:-This will used for dashboard super admin
*/
class DashboardController extends Controller
{

  /*
  @developer:jasmaninder
  @method:index
  @description:-This will used for super admin dashboard
  */
  public function index(Request $request){
    $landlords=User::where('role','admin')->count();
    $businessUsers=User::where('role','company')->count();
    return view('admin.dashboard',compact('landlords','businessUsers'));
  }

}
?>
