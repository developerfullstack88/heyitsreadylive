<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Subscription;
use Session;
use Stripe;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function __construct(){
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /*
    This will get all card for customer id
    */
    private function allCards($customerId){
      if($customerId){
        $allCards=\Stripe\Customer::allSources($customerId,['object' => 'card']);
        $allCards['default_card']=\Stripe\Customer::retrieve($customerId)->default_source;
        return $allCards;
      }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
       $stripeCustomerId=auth()->user()->stripe_customer_id;
       $companyId=auth()->user()->company_id;
       $customerDetail=array();
       $allCards=array();
       if($stripeCustomerId){
         $customerDetail=\Stripe\Customer::retrieve($stripeCustomerId);
         $allCards=$this->allCards($stripeCustomerId);
       }
       $companyInfo=array();
       if($companyId){
         $companyInfo=Company::find($companyId);
       }

       return view('billing.index',compact('customerDetail','allCards','companyInfo'));

     }

    /*add new stripe card for customer*/
    public function addNewCard(Request $request){
      $postedData=$request->all();
      $source=$postedData['source'];
      unset($postedData['source']);
      $stripeCustomerId=auth()->user()->stripe_customer_id;
      if($stripeCustomerId){
        $userDetail=User::with(['company'])->find(auth()->user()->id);
        $postedData['address_line1']=$userDetail->company->address;
        $card=\Stripe\Customer::createSource(
          $stripeCustomerId,
          ['source' => $source],
          $postedData
        );
        if($card){
          Session::flash('success', 'Card has been added successfully');
          return redirect()->route('settings.index');
        }else{
          Session::flash('success', 'There is some problem in adding card');
          return redirect()->route('settings.index');
        }
      }
    }

    /*update card settings*/
    public function updateCard(Request $request){
      $postedData = $cardData = $request->all();
      $cardId=$postedData['card_id'];
      $stripeCustomerId=auth()->user()->stripe_customer_id;
      unset($postedData['_token'],$postedData['_method'],$postedData['card_id'],
      $postedData['make_default'],$postedData['default_card_id']);
      \Stripe\Customer::updateSource(
        $stripeCustomerId,
        $cardId,
        $postedData
      );
      if(isset($cardData['make_default']) && $cardData['make_default']=='1'){
        $customer=\Stripe\Customer::retrieve($stripeCustomerId);
        $customer->default_source = $cardId;
        $customer->save();
      }
      Session::flash('success', 'Card has been updated successfully');
      return redirect()->route('settings.index');
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
        //
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
        $postedData=$request->all();
        $userDetail=User::find($id);
        $subscription=Subscription::where('user_id',$id);
        if(!isset($postedData['need_billing'])){
          $userDetail->need_billing=0;
        }else{
          $userDetail->need_billing=1;
          if($subscription->count()==0){
            $stripeCustomerId=auth()->user()->stripe_customer_id;
            $this->createSubscription($stripeCustomerId,$id);
            $userDetail->menu_qr_code=0;
          }else{
            $subscriptionInfo=$subscription->get()->last();
            if($subscriptionInfo->subscription_cancel==1){
              $stripeCustomerId=auth()->user()->stripe_customer_id;
              $this->createSubscription($stripeCustomerId,$id);
              $userDetail->menu_qr_code=0;
            }
          }

        }
        $userDetail->save();
        Session::flash('success', 'Settings has been updated successfully');
        return redirect()->route('settings.index');
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
    update user settings for menu qr only
    */
    public function updatePlan(Request $request, $id)
    {
        $postedData=$request->all();
        $userDetail=User::find($id);
        if(!isset($postedData['menu_qr_code'])){
          $userDetail->menu_qr_code=0;
        }else{
          $userDetail->menu_qr_code=1;
          $userDetail->need_billing=0;
          $this->cancelSubscription($id);
        }
        $userDetail->save();
        Session::flash('success', 'Settings has been updated successfully');
        return redirect()->route('settings.index');
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

    /*Settings of delete complete order*/
    public function deleteCompleteOrder(Request $request){
      $postedData=$request->all();
      if($postedData){
        if(isset($postedData['delete_complete_order'])){
          $userInfo=User::find(auth()->user()->id);
          if($userInfo){
            $userInfo->delete_complete_order=$postedData['delete_complete_order'];
            if($userInfo->save()){
              Session::flash('success', 'Settings has been updated successfully');
              return redirect()->route('settings.index');
            }else{
              Session::flash('warning', 'There is some problem in updation.');
              return redirect()->route('settings.index');
            }
          }else{
            Session::flash('warning', 'user info not found in database.');
            return redirect()->route('settings.index');
          }
        }
      }
    }

    /*Settings of delete complete order*/
    public function estimatedDeliveryTime(Request $request){
      $postedData=$request->all();
      if($postedData){
        if(isset($postedData['estimated_delivery_time'])){
          $userInfo=User::find(auth()->user()->id);
          if($userInfo){
            $companyId=$userInfo->company_id;
            $companyInfo=Company::find($companyId);
            $companyInfo->estimated_delivery_time=$postedData['estimated_delivery_time'];
            if($companyInfo->save()){
              Session::flash('success', 'Settings has been updated successfully');
              return redirect()->route('settings.index');
            }else{
              Session::flash('warning', 'There is some problem in updation.');
              return redirect()->route('settings.index');
            }
          }else{
            Session::flash('warning', 'user info not found in database.');
            return redirect()->route('settings.index');
          }
        }
      }
    }

    /*add connect account*/
    public function addConnectAccount(){
      /**/
      die('done');
      $account = \Stripe\Account::create([
        'type' => 'standard'
      ]);
      $account_links = \Stripe\AccountLink::create([
        'account' => $account->id,
        'refresh_url' => 'https://example.com/reauth',
        'return_url' => 'https://example.com/return',
        'type' => 'account_onboarding',
      ]);
      echo "<pre>";print_r($account_links);die;
    }

    public function returnConnectAccount(){
      $response = \Stripe\OAuth::token([
        'grant_type' => 'authorization_code',
        'code' => $_REQUEST['code']
      ]);
      $uid=auth()->user()->id;
      updateStripeConnectId($uid,$response->stripe_user_id);
      updateStripeAccessToken($uid,$response->access_token);
      Session::flash('success', 'Stripe connect account has been setup successfully');
      return redirect()->route('settings.index');
      /*$charge=\Stripe\Charge::create([
        "amount" => 20*100,
        "currency" => "usd",
        "customer" => 'cus_KTigfnwaiRfRW5',
        "source" => 'card_1JolEwGDZJH5E3zZR7ieUAFy',
        "on_behalf_of"=>$response->stripe_user_id
      ]);
  echo "<pre>";print_r($transfer);die;
      $charge=\Stripe\Charge::create([
        "amount" => 10*100,
        "currency" => "usd",
        "customer" => 'cus_KTigfnwaiRfRW5',
        "source" => 'card_1JolEwGDZJH5E3zZR7ieUAFy',
        "transfer_data" => [
          "destination" => $response->stripe_user_id,
        ]
      ]);*/

    }
}
