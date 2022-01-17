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
@controller:SuperController
@description:-This will used for super admin
*/
class SuperController extends Controller
{

  /*
  @developer:jasmaninder
  @method:login
  @description:-This will used for super admin login
  */
  public function login(Request $request){
    if(auth()->user()){
      return redirect()->route('admin.dashboard');
    }
    if($request->isMethod('post')){
      $userDetail=User::where(['email'=>$request->input('email'),'role'=>'super admin'])->orWhere(['role'=>'admin']);
      if($userDetail->count()){
        $userDetail=User::where(['email'=>$request->input('email'),'role'=>'super admin'])->orWhere(['role'=>'admin'])
        ->first();
        if (Hash::check($request->password, $userDetail->password, [])) {
          Auth::login($userDetail);
          return redirect()->route('admin.dashboard');
        }else{
          Session::flash('warning','password is not correct');
        }
      }else{
        Session::flash('warning','email or password is not correct');
      }
    }
    return view('admin.login');
  }

  /*
  @developer:jasmaninder
  @method:emailExist
  @description:-email is exist or not
  */
  public function emailExist(Request $request){
    $email=$request->query('email');
    if($email){
      $userCount=User::where('email',$email)->count();
      if($userCount>0){
        echo "false";die;
      }else{
        echo "true";die;
      }
    }
  }

}
?>
