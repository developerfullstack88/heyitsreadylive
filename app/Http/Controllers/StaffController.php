<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use Intervention\Image\Facades\Image;
use Collective\Html\HtmlFacade;
use Mail;
use App\Mail\SignupEmail;
use App\Mail\ActivateEmail;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
      if(auth()->user()->role==COMPANY)
        $users=User::where(['active'=>1,'deleted'=>0,'company_id'=>auth()->user()->company_id])->whereIn('role',[MANAGER,SUPERVISOR])->paginate(10);
      else if(auth()->user()->role==MANAGER)
        $users=User::where(['active'=>1,'deleted'=>0,'company_id'=>auth()->user()->company_id])->whereIn('role',[SUPERVISOR])->paginate(10);
      return view('staffs.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('staffs.create');
    }

    /*send register email*/
    private function sendRegisterEmail($data){
      $data->subject='Account Activate';
      $data->url=\URL::to('activate-account', array($data->id), true);

      try {
        Mail::to($data->email)->send(new ActivateEmail($data));
        return true;
      } catch (Exception $ex) {
        return false;
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      $validatedData = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'phone_number' => 'required',
        'role' => 'required'
      ]);
      if ($request->isMethod('post')) {
        $postedData=$request->all();

        /*profile photo and thumbnail upload*/
        if($request->hasFile('profile_photo')){
          $profileImageName = time().'.'.$postedData['profile_photo']->extension();
          $thumbnailProfileImagePath='images/profile-images/thumbnail/';
          if(!file_exists($thumbnailProfileImagePath)){
            mkdir($thumbnailProfileImagePath,0775,true);
          }

          $fullPathThumbnailProfileImage=public_path($thumbnailProfileImagePath).'/'.$profileImageName;
          $image = $request->file('profile_photo');
          Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnailProfileImage);
          $postedData['profile_photo_thumbnail']=$thumbnailProfileImagePath.$profileImageName;

          $profileImagePath='images/profile-images/';
          if(!file_exists($profileImagePath)){
            mkdir($profileImagePath,0775,true);
          }
          $uploadprofilePath='images/profile-images/';
          $fullPathProfileImage=public_path($uploadprofilePath);
          $postedData['profile_photo']->move($fullPathProfileImage, $profileImageName);
          $postedData['profile_photo']=$uploadprofilePath.$profileImageName;
        }
        /*profile photo and thumbnail upload*/
        $postedData['name']=$postedData['first_name'].' '.$postedData['last_name'];
        $postedData['created_at']=date('Y-m-d h:i:s');
        $postedData['company_id']=Auth::user()->company_id;
        if($postedData['phone_code'] && $postedData['phone_number']){
          $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
          unset($postedData['phone_code']);
        }
        $postedData['role']=strtolower($postedData['role']);
        if($user=User::create($postedData)){
          $this->sendRegisterEmail($user);
          Session::flash('success', 'Staff has been created successfully.');
          return redirect()->route('staffs.index');
        }else{
          Session::flash('warning', 'There is some problem in adding staff.');
        }

      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
      $user=User::find($id);
      return view('staffs.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
      $user=User::find($id);
      if(strpos($user->phone_number,'(')!==false){
        $phoneArr=explode(" ",$user->phone_number);
        $user->phone_code=$firstVal = array_shift($phoneArr);
        $user->phone_number=trim(implode(" ",$phoneArr));
      }
      return view('staffs.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
      $validatedData = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'phone_number' => 'required'
      ]);
      if ($request->isMethod('put')) {
        $postedData=$request->all();
        unset($postedData['_token'],$postedData['_method']);
        /*profile photo and thumbnail upload*/
        if($request->hasFile('profile_photo')){
          $profileImageName = time().'.'.$postedData['profile_photo']->extension();
          $thumbnailProfileImagePath='images/profile-images/thumbnail/';
          if(!file_exists($thumbnailProfileImagePath)){
            mkdir($thumbnailProfileImagePath,0775,true);
          }

          $fullPathThumbnailProfileImage=public_path($thumbnailProfileImagePath).'/'.$profileImageName;
          $image = $request->file('profile_photo');
          Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnailProfileImage);
          $postedData['profile_photo_thumbnail']=$thumbnailProfileImagePath.$profileImageName;

          $profileImagePath='images/profile-images/';
          if(!file_exists($profileImagePath)){
            mkdir($profileImagePath,0775,true);
          }
          $uploadprofilePath='images/profile-images/';
          $fullPathProfileImage=public_path($uploadprofilePath);
          $postedData['profile_photo']->move($fullPathProfileImage, $profileImageName);
          $postedData['profile_photo']=$uploadprofilePath.$profileImageName;
        }
        /*profile photo and thumbnail upload*/
        $postedData['name']=$postedData['first_name'].' '.$postedData['last_name'];
        $postedData['updated_at']=date('Y-m-d h:i:s');
        if($postedData['phone_code'] && $postedData['phone_number']){
          $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
          unset($postedData['phone_code']);
        }
        User::where('id',$id)->update($postedData);
        Session::flash('success', 'Staff has been updated successfully.');
        return redirect()->route('staffs.index');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
      User::where('id',$id)->delete();
      Session::flash('success', 'User has been deleted successfully');
      return redirect()->route('staffs.index');
    }

    /*
    Resend email functionality
    */
    public function resendEmail($uid){
      if($uid){
        $userInfo=User::find($uid);
        if($this->sendRegisterEmail($userInfo)){
          Session::flash('success', 'Email has been sent successfully');
          return redirect()->route('staffs.index');
        }
      }
    }
}
