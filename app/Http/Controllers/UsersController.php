<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Order;
use App\Company;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Rules\MatchOldPassword;
use App\Country;
use App\Subscription;
use Mail;
use App\Mail\CancelAccount;
class UsersController extends Controller
{

    public function __construct(){
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        die('ddd');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /*This method will insert user for order*/
    private function insertUser($userData){
      if($userData){
        $name=$userData['name'];
        $userData['name']=explode(" ",$userData['name']);
        $userData['first_name']=$userData['name'][0];
        if(count($userData['name'])>1){
          unset($userData['name'][0]);
          $userData['last_name']=trim(implode(" ",$userData['name']));
        }
        unset($userData['order_number']);
        $userData['name']=$name;
        $userData['password']=bcrypt(12345678);
        $userData['company_id']=$userData['company_id'];
        $userData['role']='user';
        /*add user*/
        return User::create($userData);
      }else{
        return false;
      }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if($request->isMethod('post')){
        $postedData = $userData = $request->all();
        if($postedData['phone_code']){
          $userData['phone_number']=str_replace(array('(',')','-'),'',$userData['phone_number']);
          $userData['phone_number']=preg_replace("/\s+/", "", $userData['phone_number']);
          $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
          $userData['phone_number']=$userData['phone_code'].' '.$userData['phone_number'];
          unset($postedData['phone_code'],$userData['phone_code']);
        }

        //validation
        $validationRules=[
          'order_number' => 'required',
          'name' => 'required',
          'phone_number' => 'required',
          //'email' => 'required',
          //'email' => 'required|unique:users,email',
        ];
        $messageArray=[
          'order_number.required' => 'Order Number is required',
          'name.required' => 'Name is required',
        ];
        $postedData['user_id']=checkUserExistByPhone($userData['phone_number']);
        if(!$postedData['user_id'] && isset($postedData['email']) && !empty($postedData['email'])) {
          $validationRules['email'] = 'required|unique:users,email';
          $messageArray['email.unique'] = 'Email is already exist.';
        }
        $validatedData = $request->validate($validationRules,$messageArray);

        //call helper method
        $postedData['user_id']=checkUserExistByPhone($userData['phone_number']);


        if(!$postedData['user_id']) {
          unset($userData['user_id']);
          if($userInserted=$this->insertUser($userData)) {
            $postedData['user_id']=$userInserted->id;
          }
        }
        unset($postedData['name'],$postedData['email'],$postedData['phone_number']);
        $postedData['new_order']=1;
        $postedData['status']='pending';
        if($orderInserted=Order::create($postedData)) {
          /*Record usage API*/
          $subscriptionInfo=getCompanySubscriptionInfo($postedData['company_id']);
          $subscriptionInfo = \Stripe\Subscription::retrieve($subscriptionInfo->subscription_id);
          $subscriptionItem=$subscriptionInfo->items->data[0]->id;
          $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
          $stripe->subscriptionItems->createUsageRecord($subscriptionItem,['quantity' => 1]);
          /*Record usage API*/
          Session::flash('success', 'Order has been added successfully');
          return redirect()->route('home.trackOrder',['id'=>$orderInserted]);
        }

      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $userInfo=User::with(['company'])->find($id);
      return view('users.show',compact('userInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userInfo=User::with(['company'])->find($id);
        if($userInfo){
          if(strpos($userInfo->phone_number,'(')!==false){
            $phoneArr=explode(" ",$userInfo->phone_number);
            $userInfo->phone_code=$firstVal = array_shift($phoneArr);
            $userInfo->phone_number=trim(implode(" ",$phoneArr));
          }

        }

        return view('users.edit',compact('userInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id && $request->isMethod('put')) {
          $postedData= $request->all();
          $updateAction=$postedData['update_action'];
          if($updateAction=='profile'){
            /*validation*/
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'company_name' => ['required', 'string', 'max:100'],
                'address' => ['required', 'string', 'max:255'],
                'phone_code' => ['required'],
                'phone_number' => ['required'],
                'email' => ['required','email']
            ]);
            /*validation*/

            if($postedData['phone_code'] && $postedData['phone_number']){
              $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
              unset($postedData['phone_code']);
            }
            $companyArr['company_name']=$postedData['company_name'];
            $companyArr['company_website']=$postedData['company_website'];
            $companyArr['address']=$postedData['address'];

            unset($postedData['company_name'],$postedData['company_website'],$postedData['_token'],$postedData['_method'],$postedData['address'],$postedData['update_action']);

            if(User::where('id',$id)->update($postedData)){
              if(Company::where('id',$postedData['company_id'])->update($companyArr)){

                $this->updateStripeAddress($postedData['stripe_customer_id'],$companyArr['address']);

                Session::flash('success', 'Profile has been updated successfully');
                return redirect()->route('users.show',$id);
              }
            }else{
              Session::flash('warning', 'There is some problem in user updation');
              return redirect()->back();
            }
          }else{
            $validatedData=$request->validate([
              'old_password' => ['required', new MatchOldPassword],
              'new_password' => ['required'],
              'confirm_password' => ['same:new_password'],
            ]);
            User::find(auth()->user()->id)->update(['password'=> bcrypt($request->new_password)]);
            Session::flash('success', 'password has been changed successfully');
            return redirect()->route('users.show',$id);
          }
        }
    }

    /*This method will update stripe billing
    address*/
    private function updateStripeAddress($stripeCustomerId,$address){
      if($stripeCustomerId && $address){
        $postedData['address_line1']=$address;
        $customer=\Stripe\Customer::retrieve($stripeCustomerId);
        if($customer){
          $cardId=$customer->default_source;
          if($cardId){
            \Stripe\Customer::updateSource(
              $stripeCustomerId,
              $cardId,
              $postedData
            );
          }
        }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*create order of user*/
    public function orderCreate(Request $request){
      $companyId=$request->get('company_id');
      $locationId=$request->get('location_id');
      $orderId=getRandomOrderNumber();
      /*fetch phone code*/
      $phoneCode='';
      $ipInfo=ipInfo();
      if($ipInfo){
        if($ipInfo->countryCode){
          $countryInfo=Country::where('country_code',$ipInfo->countryCode);
          if($countryInfo->count()>0){
            $phoneCode=$countryInfo->first()->phone_code;
          }
        }
      }
      /*fetch phone code*/
      return view('users.create',compact('orderId','companyId','phoneCode','locationId'));
    }

    /*
    @developer:jasmaninder
    @method:-profileImageChange
    @description:-This will change user image
    */
    public function profileImageChange(Request $request){
      if($request->hasFile('file')){
        $uid=Auth::user()->id;
        $imagePath= $request->file('file')->store('company_profile','public');
        if($imagePath){
          User::where('id',$uid)->update(['profile_photo'=>$imagePath]);
          return "true";
        }else{
          return "false";
        }
      }
    }

    /*
    @developer:jasmaninder
    @method:subscriptionInfo
    @description:This will return subscription info for user
    */
    private function subscriptionInfo($uid){
      if($uid){
        if(Subscription::where('user_id',$uid)->exists()){
          return Subscription::where('user_id',$uid)->get()->last();
        }else{
          return false;
        }
      }
    }

    //cancel subscription
    private function cancelSubscription($uid){
      $subscriptionInfo=$this->subscriptionInfo($uid);
      if($subscriptionInfo){
        $sub = \Stripe\Subscription::retrieve($subscriptionInfo['subscription_id']);
        if($sub && $sub->status!='canceled'){
          $sub->cancel();
          $subscriptionInfo->subscription_cancel=1;
          $subscriptionInfo->save();
        }
      }
    }

    /*
    @developer:jasmaninder
    @method:-deleteAccount
    @description:-This soft delete user account
    */
    public function deleteAccount($id){
      $this->cancelSubscription($id);
      $userInfo=User::find($id);
      $superAdminCount=User::where('role','super admin')->count();
      if($superAdminCount>0){
        $data=User::find($id);
        $data->subject='Bo Cancelled account';
        $superAdminInfo=User::where('role','super admin')->first();
        $superAdminInfo->email;
        Mail::to($superAdminInfo->email)->send(new CancelAccount($data));
        $userInfo->active=2;
        $userInfo->deleted=1;
        $userInfo->save();
        Auth::logout();
        Session::flash('success','Business users has been cancelled his account');
        return redirect()->route('heyItsReadyHome');
      }else{
        Session::flash('warning','super admin info is not found');
        return redirect()->route('settings.index');
      }


    }
}
