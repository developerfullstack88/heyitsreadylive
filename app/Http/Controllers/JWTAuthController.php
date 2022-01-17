<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Country;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Mail;
use App\Mail\ForgotPassword;
use App\Mail\ChangePassword;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        auth()->setDefaultDriver('api');
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
      if($request->all()){
        $user = User::where('email', '=', $request->get('email'))->first();
        $phoneUser = User::where('phone_number', '=', $request->get('phone_number'))->first();
    		if($user!=null && $user->count()>0 && $user->disabled==0){
            return response()->json(['code'=>400,'status'=>false,'message'=>'Email already exist']);
        }else if($phoneUser!=null && $phoneUser->count()>0 && $phoneUser->disabled==0){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Phone number already exist.']);
        }else if($phoneUser!=null && $phoneUser->count()>0 && $phoneUser->disabled==1) {
          Order::where('user_id',$phoneUser->id)->update(['confirm'=>0,'call_btn'=>1]);
          $postedArr=$request->all();
          $postedArr['password']=bcrypt($postedArr['password']);
          $postedArr['disabled']=0;
          if(User::where('id',$phoneUser->id)->update($postedArr)) {
            $user=getNormalUserInfo($phoneUser->id);
            $token = auth()->login($user);//get jwt token
            $user->token=$token;
            return response()->json(['code'=>200,'status'=>true,'message' => 'Regsitered successfully',
            'data' => $user,'token'=>$token]);
          }
        }else if($user!=null && $user->count()>0 && $user->disabled==1){
          Order::where('user_id',$user->id)->update(['confirm'=>0,'call_btn'=>1]);
          $postedArr=$request->all();
          $postedArr['password']=bcrypt($postedArr['password']);
          $postedArr['disabled']=0;
          if(User::where('id',$user->id)->update($postedArr)){
            $user=getNormalUserInfo($user->id);
            $token = auth()->login($user);//get jwt token
            $user->token=$token;
            return response()->json(['code'=>200,'status'=>true,'message' => 'Regsitered successfully',
            'data' => $user,'token'=>$token]);
          }
        }
        else{
          $validator = Validator::make($request->all(), [
              'email' => 'email|unique:users|max:50'
          ]);
          if($validator->fails()){
            return response()->json(['code'=>201,'status'=>false,'errors' => $validator->errors()->all()]);
          }else{
            $request->all()['role']='user';
            $user = User::create(array_merge(
              $request->all(),
              ['password' => bcrypt($request->password)]
            ));
            $token = auth()->login($user);//get jwt token
            $user->token=$token;
            $user->email_enable=1;
            $user->notification=1;
            if(!updateLoginToken($user->id,$token)) {
              return response()->json(['code'=>400,'status'=>false,'message'=>'Login token is not updated']);
            }
            return response()->json(['code'=>200,'status'=>true,'message' => 'Regsitered successfully',
            'data' => $user,'token'=>$token]);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), ['email' => 'email']);

        if ($validator->fails()) {
          return response()->json(['code'=>201,'errors' => $validator->errors()->all()]);
        }
        $postedData=$request->all();
        unset($postedData['device_token'],$postedData['timezone'],$postedData['device_type'],$postedData['ios_version'],$postedData['login_check']);
        if (! $token = auth()->attempt($postedData)) {
            return response()->json(['code'=>400,'status'=>false,'message' => 'Email or password is incorrect']);
        }
        $user = User::where('email', '=', $request->get('email'))->first();
    		if($user==null && $user->count()==0){
    			return response()->json(['code'=>400,'status'=>false,'message'=>'Record not found for this email']);
    		}
        if($tokenString=$this->createNewToken($token)){
          if($user->login_token && $request->get('login_check')===0){
            return response()->json(['code'=>700,'status'=>false,'message' => 'You already logged in from other device or same device.Do you want to continue?']);
          }else{
            if(!updateDeviceToken($user->id,$request->get('device_token'))){
              return response()->json(['code'=>400,'status'=>false,'message'=>'Device token is not updated']);
            }
            if(!updateTimzone($user->id,$request->get('timezone'))){
              return response()->json(['code'=>400,'status'=>false,'message'=>'Timezone is not updated']);
            }
            if(!updateDeviceType($user->id,$request->get('device_type'))){
              return response()->json(['code'=>400,'status'=>false,'message'=>'Device type is not updated']);
            }
            if(!updateIosVersion($user->id,$request->get('ios_version'))){
              return response()->json(['code'=>400,'status'=>false,'message'=>'Ios version is not updated']);
            }
            if(!updateLoginToken($user->id,$tokenString)) {
              return response()->json(['code'=>400,'status'=>false,'message'=>'Login token is not updated']);
            }
            updateBackground($user->id,0);
            return response()->json(['code'=>200,'status'=>true,'message' => 'Login successfully',
            'data' => $user,'token'=>$tokenString]);
          }
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $userDetail=auth()->user();
        if($userDetail){
          $phoneNumber=$userDetail->phone_number;
          $phoneArr=explode(" ",$phoneNumber);
          //echo "<pre>";print_r($phoneArr);die;
          if(count($phoneArr)>1){
            $countryCode=$phoneArr[0];
            $countryInfo=Country::where('phone_code',$countryCode)->pluck('country_code');
            //echo "<pre>";print_r($phoneArr);die;
            //$userDetail->country='ca';
            if($countryInfo->count()>0){
              $userDetail->country=strtolower($countryInfo[0]);
            }

          }
          unset($userDetail['created_at'],$userDetail['updated_at'],$userDetail['company_id'],$userDetail['role'],$userDetail['email_verified_at'],$userDetail['device_token']);
          return response()->json(['code'=>200,'data'=>$userDetail]);
        }else{
          return response()->json(['code'=>400,'message' =>'Record not found in database']);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->login_token=null;
        $user->save();
        return response()->json(['code'=>200,'message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return $token;
        /*return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);*/
    }

    /*update profile info of user*/
    public function update(Request $request){
      $userDetail=auth()->user();

      if($userDetail==null){
        return response()->json(['code'=>400,'status'=>false,'message'=>'Record not found in database']);
      }
      $userProfilePhoto=$userDetail->profile_photo;
      $postedData=$request->all();
      $phoneUserCount=User::where([['phone_number',$postedData['phone_number']],['id','!=',$userDetail->id]])->count();
      if($phoneUserCount>0){
        return response()->json(['code'=>400,'status'=>false,'message'=>'Phone number is already exist for another user']);
      }
      if($request->get('profile_photo')){
        $postedData['profile_photo']=uploadProfilePhoto($userDetail->id,$request->get('profile_photo'));
      }else{
        $postedData['profile_photo']=$userProfilePhoto;
      }

      $rowAffected=$userDetail->update($postedData);
      if($rowAffected){
        return response()->json(['code'=>200,'status'=>true,'data'=>$postedData,'message'=>'Profile info updated successfully']);
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Profile info not updated']);
      }
    }

    /*change old password to new password*/
    public function changePassword(Request $request){
		$postedArr=$request->all();
    if($postedArr){
      extract($postedArr);
  		$userDetail=auth()->user();
      $emailData=$userDetail;
  		if(!Hash::check($old_password, $userDetail->password)){
  			return response()->json(['code'=>400,'status'=>false,'message'=>'Old password does not match the database password.']);
  		}
  		if($userDetail){
        $original_password=$new_password;
        $new_password=bcrypt($new_password);
  			$userDetail->password=$new_password;
  			$userDetail->save();
        $emailData->subject='Password Changed';
        $emailData->new_password=$original_password;
        Mail::to($userDetail->email)->send(new ChangePassword($emailData));
  			return response()->json(['code'=>200,'status'=>true,'message'=>'Password changed successfully']);
  		}
  		return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in change password']);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }

	}

  /*forgot password */
  public function forgotPassword(Request $request){
    $postedArr=$request->all();
    if($postedArr){
      $userInfo=User::where('email',$postedArr['email']);
      if($userInfo->count()>0){
        $userInfo = $emailData = $userInfo->first();
        $newPassword=randomPassword();
        $userInfo->password=bcrypt($newPassword);
        $data['new_password']=$newPassword;
        if($userInfo->save()){
          $emailData->subject='Forgot Password';
          $emailData->new_password=$newPassword;
          Mail::to($userInfo->email)->send(new ForgotPassword($emailData));
          return response()->json(['code'=>200,'status'=>true,
          'message'=>'Email sent successfully on registered email','data'=>$data]);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in password updation']);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Record not found in database']);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*enable disable notification*/
  public function setNotification(Request $request){
    $postedArr=$request->all();
    if($postedArr){
      extract($postedArr);
  		$userDetail=auth()->user();
  		if($userDetail){
  			$userDetail->notification=$notification;
  			$userDetail->save();
  			return response()->json(['code'=>200,'status'=>true,'message'=>'Notification settings has been changed']);
  		}
  		return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in updation']);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*enable disable email settings*/
  public function setEmail(Request $request){
    $postedArr=$request->all();
    if($postedArr){
      extract($postedArr);
  		$userDetail=auth()->user();
  		if($userDetail){
  			$userDetail->email_enable=$email_enable;
  			$userDetail->save();
  			return response()->json(['code'=>200,'status'=>true,'message'=>'Email settings has been changed']);
  		}
  		return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in updation']);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*delete all info of user*/
  public function deleteAccount($uid){
    if($uid){
      if(auth()->user()){
        if(auth()->user()->id!=$uid){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else{
          Order::where('user_id',$uid)->delete();
          if(User::where('id',$uid)->delete()){
            return response()->json(['code'=>200,'status'=>true,'message'=>'Account has been deleted successfully.']);
          }else{
            return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in user deletion.']);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Required field id is missing.']);
    }
  }

  /*check background api*/
  public function checkBackground(Request $request){
    $uid=$request->get('uid');
    $type=$request->get('type');
    if($uid && $type){
      if(updateBackground($uid,$type)){
        return response()->json(['code'=>200]);
      }
    }else{
      if(updateBackground($uid,$type)){
        return response()->json(['code'=>200]);
      }
    }
  }
}
