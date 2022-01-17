<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Subscription;
use Session;
use Stripe;
use Carbon\Carbon;
use Mail;
use App\Mail\SuspendAccount;

class BusinessUsersController extends Controller
{
    public function __construct(){
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $items = $request->items ?? 10;
      $businessUsers=User::with(['company'])->where('deleted',0);
      $allCompanies=Company::all();

      $postedData=$request->all();
      extract($postedData);
      if(isset($company_name) && !empty($company_name)){
        $businessUsers=User::with(['company'=>function($query) use($company_name){
          $query->where('id',$company_name);
        }])->where('deleted',0);
      }
      if(isset($status) && !empty($status)) {
        $businessUsers=$businessUsers->where('active',$status);
        if($status!=2){
          $businessUsers=$businessUsers->whereNotIn('active',[2]);
        }
      }else if(isset($status) && $status==='0'){
        $businessUsers=$businessUsers->where('active',0);
        $businessUsers=$businessUsers->orWhere('active',2);
      }
        $businessUsers=$businessUsers->where('role','company')->paginate($items);

      return view('admin.business_users.index',compact('businessUsers','items','allCompanies'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($id){
          $userInfo=User::with(['company'])->find($id);
          return view('admin.business_users.edit',compact('userInfo'));
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
      This will add subscription for customer
      with trial peroid
    */
    private function createSubscription($customerId,$uid){
      $planId=env('STRIPE_PLAN_ID');
      if($customerId){
        $subscriptionData=array(
          'customer' => $customerId,
          //'trial_period_days'=>30,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      if($request->isMethod('put')){
        extract($request->all());
        $userInfo=User::find($id);
        if($userInfo){
          /*Update company Info*/
          $companyInfo=Company::find($userInfo->company_id);
          $companyInfo->company_name=$company_name;
          $companyInfo->company_website=$company_website;
          $companyInfo->save();
          /*Update company Info*/

          /*Update user Info*/
          $userInfo->name=$name;
          $userInfo->phone_number=$phone_number;

          if(isset($status) && ($userInfo->active==0 || $userInfo->active==2)) {
            $userInfo->active=1;
            $userInfo->deleted=0;
            if($userInfo->stripe_customer_id){
              $this->createSubscription($userInfo->stripe_customer_id,$userInfo->id);
            }

          }else if($userInfo->active==1 && !(isset($status))) {
            $userInfo->active=0;
            $this->cancelSubscription($userInfo->id);
          }
          $userInfo->deactivate_reason=$deactivate_reason;
          $userInfo->save();
          /*Update user Info*/
          Session::flash('success','Business users updated successfully');
        }
        return redirect()->route('business-users.index');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id,$type)
    {
        if($type==0){
          $this->cancelSubscription($id);
          $userInfo=User::find($id);
          $data=User::find($id);
          $data->subject='Account Suspended for 90 days';
          Mail::to($userInfo->email)->send(new SuspendAccount($data));
          $userInfo->active=2;
          $userInfo->deleted=1;
          $userInfo->save();
          Session::flash('success','Business users soft deleted successfully');
        }else{

        }
        return redirect()->route('business-users.index');
    }
}
