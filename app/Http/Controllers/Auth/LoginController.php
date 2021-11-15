<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function credentials(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //$credentials = array_add($credentials, 'role','company');
        return $credentials;
    }

    protected function authenticated(Request $request, $user){
      //Check if user is approved
      if($user->deleted==1) {
          Auth::logout();
          Session::flash('warning','Ooops your account was canceled if this is not correct please contact our support desk to re activate your account.');
          return redirect()->route('heyItsReadyHome');

      }else if($user->active==0) {
        Auth::logout();
        Session::flash('warning','Your account is deactivated,Please contact our support desk to re activate your account');
        return redirect()->route('heyItsReadyHome');
      }else if($user->role==USER){
        Auth::logout();
        return redirect()->route('heyItsReadyHome');
      }
    }
}
