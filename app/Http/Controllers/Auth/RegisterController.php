<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\State;
use App\City;
use App\Country;
use App\Country2;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use App\Mail\SignupEmail;
use App\Mail\ActivateEmail;
use Stripe;
use App\Subscription;
use Carbon\Carbon;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
      return Validator::make($data, [
          'first_name' => ['required', 'string', 'max:255'],
          'last_name' => ['required', 'string', 'max:255'],
          'company_name' => ['required', 'string', 'max:100'],
          'address' => ['required', 'string', 'max:255'],
          'phone_code' => ['required'],
          'phone_number' => ['required'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          //'password' => ['required', 'string', 'min:8', 'confirmed'],
      ]);
    }

    /*send register email*/
    private function sendRegisterEmail($data){
      $data->subject='Account Activate';
      $data->url=\URL::to('activate-account', array($data->id), true);
      Mail::to($data->email)->send(new ActivateEmail($data));
    }

    /*create stripe customer subscription with trial period*/
    private function CreateStripeCustomerCard($data){
      extract($data);
      $customer = \Stripe\Customer::create([
          'email' => $email,
          'source' => $stripeToken,
      ]);
      return ($customer)?$customer->id:false;
    }

    protected function redirectTo()
    {
        \Auth::logout();
        $this->redirectTo = '/thankyou';
        return $this->redirectTo;
    }

    /*
      This will add subscription for customer
      with trial peroid
    */
    private function createSubscription($customerId,$uid,$planId){
      if($customerId){
        $subscriptionData=array(
          'customer' => $customerId,
          'trial_period_days'=>60,
          'items' => [['plan' => $planId]]
        );
        $subscription = \Stripe\Subscription::create($subscriptionData);
        if($subscription){
          $subscriptionStart=Carbon::createFromTimestamp($subscription->current_period_start);
          $subscriptionEnd=Carbon::createFromTimestamp($subscription->current_period_end);
          $freeTrial=0;
          $trialStart=null;
          $trialEnd=null;
          if($subscription->trial_start){
            $freeTrial=1;
            $trialStart=Carbon::createFromTimestamp($subscription->trial_start);
            $trialEnd=Carbon::createFromTimestamp($subscription->trial_end);
          }
          $subscriptionTable=Subscription::create([
              'user_id'=>$uid,
              'subscription_id'=>$subscription->id,
              'customer_id'=>$subscription->customer,
              'plan_id'=>$planId,
              'subscription_start_date'=>$subscriptionStart,
              'subscription_end_date'=>$subscriptionEnd,
              'free_trial'=>$freeTrial,
              'trial_start_date'=>$trialStart,
              'trial_end_date'=>$trialEnd
          ]);
          if($subscriptionTable->id){
            return true;
          }else{
            return false;
          }
        }
      }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      extract($data);
      //$state=State::find($state)->name;
      ///$city=City::find($city)->name;

      $countryCodeQuery=Country::where('countries_name',$country);
      if($countryCodeQuery->count()>0){
        $countryCode=$countryCodeQuery->first()->country_code;
      }else{
        $countryCodeQuery2=Country2::where('name',$country);
        if($countryCodeQuery2->count()>0){
          $countryCode=$countryCodeQuery2->first()->sortname;
        }
      }

      https://esprit2.delfdalf.ch/vw-examregistrations/generate-examcenter-pdf/62/4
      $planId=getPlanId($countryCode);
      $stripeCustomerId=$this->CreateStripeCustomerCard($data);
      $company=Company::create([
        'company_name'=>$company_name,
        'company_website'=>$company_website,
        'address'=>$address
      ]);
      if($company->id>0){
        if($phone_code && $phone_number){
          $phone_number=$phone_code.' '.$phone_number;
        }
        if(isset($data['need_billing'])){
          //$data['need_billing']=($data['need_billing']=='yes')?1:0;
        }

        $user=User::create([
            'company_id'=>$company->id,
            'name' => $first_name.' '.$last_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'country' => $country,
            'phone_number' => $phone_number,
            'email' => $email,
            //'password' => bcrypt($password),
            'stripe_customer_id'=>$stripeCustomerId,
            'street_address'=>$street_address,
            'line2_address'=>$line2_address,
            'city'=>$city,
            'state'=>$state,
            'zip_code'=>$zip_code,
            'timezone'=>$timezone,
            //'need_billing'=>0
        ]);
        if($user->id && $stripeCustomerId){
          $this->createSubscription($stripeCustomerId,$user->id,$planId);
          /*if($data['need_billing']==1){

          }*/
          $this->sendRegisterEmail($user);
        }
        Session::flash('success', 'please check your email for activating account.');
        return $user;
        //Session::flash('success', 'please check your email for activating account.');
        //return redirect('/login');
        return redirect()->route('heyItsReadyHome')->with('success', 'please check your email for activating account');
      }
    }
}
