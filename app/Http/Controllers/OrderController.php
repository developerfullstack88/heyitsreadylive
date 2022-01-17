<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Mail\OrderEmail;
use App\Mail\EtaEmail;
use App\Mail\OrderReady;
use App\Mail\OrderComplete;
use Mail;
use Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\TmpOrder;
use App\QrItem;
use App\Country;
use App\Payment;
use App\Cart;
use App\Http\Controllers\PaymentController;

class OrderController extends Controller{
    public $paymentController;

    public function __construct(){
      $this->paymentController = new PaymentController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orderId=getRandomOrderNumber(Auth::user()->company_id);
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

        /*old code stripe month billing will work
        if(checkStripeAmountPaidForMonth()) {
          if(!$this->paymentController->doCompanyStripePayment()){
            Session::flash('warning', 'Stripe Monthly payment not paid due to some problem!');
            return redirect()->route('home');
          }
        }
        /*stripe month billing will work*/
        return view('orders.create',compact('orderId','phoneCode'));
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
        $userData['company_id']=Auth::user()->company_id;
        $userData['role']='user';
        $userData['disabled']=1;
        /*add user*/

        return User::create($userData);
      }else{
        return false;
      }

    }

    /*send order email*/
    private function sendOrderEmail($data){
      $data['subject']='Hey, your order has been added at '.getBusinessName().'.';
      $data['message']=getBusinessName().' has added your order number '.$data['order_number'].' successfully.';
      Mail::to($data['email'])->send(new OrderEmail($data));
    }

    /*This will modify eta time
    from local to server*/
    private function setEtaTime($postedData){
      $currentDate=Carbon::now()->format('Y-m-d');
      if(strpos($postedData['eta'],"-")>0) {
        $postedData['eta']=Timezone::convertFromLocal($postedData['eta']);
      }else{
        if(strpos($postedData['eta'],'Min')>0) {
          $postedData['eta']=trim(str_replace("Min","",$postedData['eta']));
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $carbonDate=Carbon::now()->addMinutes($postedData['eta']);
            $postedData['eta']=$carbonDate->addHours(1);
          }else{
            $postedData['eta']=Carbon::now()->addMinutes($postedData['eta']);
          }
        }else if(strpos($postedData['eta'],'Hour')>0) {
          $postedData['eta']=trim(str_replace("Hour","",$postedData['eta']));
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $postedData['eta']=Carbon::now()->addHours($postedData['eta']+1);
          }else{
            $postedData['eta']=Carbon::now()->addHours($postedData['eta']);
          }
        }else{
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $carbonDate=Carbon::now()->addMinutes($postedData['eta']);
            $postedData['eta']=$carbonDate->addHours(1);
          }else{
            $postedData['eta']=Carbon::now()->addMinutes($postedData['eta']);
          }
        }
      }
      return $postedData['eta'];
    }

    /*Update eta time*/
    public function updateEtaTime($postedData,$oldEtaTime){
      $currentDate=Carbon::now()->format('Y-m-d');
      $currentDateTime=Carbon::now()->format('Y-m-d H:i:s');
      if($oldEtaTime<$currentDateTime){
        $oldEtaTime=$currentDateTime;
      }

      if(strpos($postedData['eta'],"-")>0) {
        $postedData['eta']=Timezone::convertFromLocal($postedData['eta']);
      }else{
        if(strpos($postedData['eta'],'Min')>0) {
          $postedData['eta']=trim(str_replace("Min","",$postedData['eta']));
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $carbonDate=Carbon::create($oldEtaTime)->addMinutes($postedData['eta']);
            $postedData['eta']=$carbonDate->addHours(1);
          }else{
            $postedData['eta']=Carbon::create($oldEtaTime)->addMinutes($postedData['eta']);
          }
        }else if(strpos($postedData['eta'],'Hour')>0) {
          $postedData['eta']=trim(str_replace("Hour","",$postedData['eta']));
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $postedData['eta']=Carbon::create($oldEtaTime)->addHours($postedData['eta']+1);
          }else{
            $postedData['eta']=Carbon::create($oldEtaTime)->addHours($postedData['eta']);
          }
        }else{
          if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
            $carbonDate=Carbon::create($oldEtaTime)->addMinutes($postedData['eta']);
            $postedData['eta']=$carbonDate->addHours(1);
          }else{
            $postedData['eta']=Carbon::create($oldEtaTime)->addMinutes($postedData['eta']);
          }
        }
      }
      return $postedData['eta'];
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
          $postedData = $userData = $emailData = $request->all();
          $postedData['app_used']=1;
          $userData['app_used']=1;
          //$postedData['phone_code']=$userData['phone_code']='+1';
          if($postedData['phone_code']){
            $userData['phone_number']=str_replace(array('(',')','-'),'',$userData['phone_number']);
            $userData['phone_number']=preg_replace("/\s+/", "", $userData['phone_number']);
            $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
            $userData['phone_number']=$userData['phone_code'].' '.$userData['phone_number'];
            unset($postedData['phone_code'],$userData['phone_code'],$emailData['phone_code']);
          }
          //call helper method
          $postedData['user_id']=checkUserExistByPhone($userData['phone_number']);

          $validationRules=array(
            'order_number' => 'required',
            'phone_number' => 'required',
            'name' => 'required'
          );
          $messageArray=array(
            'order_id.required' => 'Order Number is required',
          );
          if(!$postedData['user_id']) {
            $validationRules['phone_number']='required|unique:users,phone_number';
            $messageArray['phone_number.unique'] = 'Phone number is already exist.';
          }
          if(!$postedData['user_id'] && isset($postedData['email']) && $postedData['email']) {
            $postedData['user_id']=checkUserExistByEmail($userData['email']);
            if(!$postedData['user_id']){
              $validationRules['email']='unique:users,email';
              $messageArray['email.unique'] = 'Email is already exist.';
            }
          }

          $validatedData = $request->validate($validationRules,$messageArray);

          $postedData['status']='pending';

          /*eta in minutes call internal method*/
          if(!empty($postedData['eta'])) {
            $postedEta=$postedData['eta'];
            $postedData['eta']=$this->setEtaTime($postedData);
            $postedData['actual_order_time']=$postedData['eta'];
            $postedData['status']='confirm';
            $postedData['eta_current']=Carbon::now();
          }
          /*eta in minutes call internal method*/

          unset($userData['amount'],$userData['eta'],$userData['eta_radio'],$postedData['eta_radio'],
          $userData['user_id']);

          /*insert user*/
          if(!$postedData['user_id']) {
            if($userInserted=$this->insertUser($userData)) {
              $postedData['user_id']=$userInserted->id;
            }
          }
          /*insert user*/

          /*app used or not*/
          if(isset($postedData['app_used']) && $postedData['app_used']==1) {
            updateAppUsed($postedData['user_id'],$postedData['app_used']);
          }else{
            updateAppUsed($postedData['user_id'],0);
            $postedData['confirm']=1;
            $postedData['call_btn']=1;
          }

          /*app used or not*/
          if(!empty($postedData['eta'])) {
            if(strpos($postedEta,'Min')>0) {
              $postedEta=trim(str_replace("Min","",$postedEta));
              $data = array('title'=>'','body'=>"Your order EPUT number #".$postedData['order_number']." at ".getBusinessName()." is in ".$postedEta." minute(s).");
            }else if(strpos($postedEta,'Hour')>0) {
              $postedEta=trim(str_replace("Hour","",$postedEta));
              $data = array('title'=>'','body'=>"Your order EPUT number #".$postedData['order_number']." at ".getBusinessName()." is in ".$postedEta." Hours.");
            }else{
              if(strpos($postedEta,"-")>0) {
                $data = array('title'=>'','body'=>"Your order EPUT number #".$postedData['order_number']." at ".getBusinessName()." time is ".$postedEta.".");
              }else{
                $data = array('title'=>'','body'=>"Your order EPUT number #".$postedData['order_number']." at ".getBusinessName()." is in ".$postedEta." minute(s).");
              }
            }
            //sendPushNotification($data,$postedData['user_id']);
          }

          if($postedData['user_id']) {
            $postedData['company_id']=Auth::user()->company_id;
            unset($postedData['name'],$postedData['email'],$postedData['phone_number'],$postedData['app_used']);

            if(isset($postedData['amount']) && $postedData['amount']){
              $postedData['amount']=str_replace(",","",$postedData['amount']);
            }else{
              unset($postedData['amount']);
            }

            /*set default location id to order*/
            $postedData['location_id']=getDefaultLocationLoggedUser();
            /*set default location id to order*/
            if($insertedOrder=Order::create($postedData)) {
              /*Record usage API*/
              $subscriptionInfo=getLoggedSubscriptionInfo();
              $subscriptionInfo = \Stripe\Subscription::retrieve($subscriptionInfo->subscription_id);
              $subscriptionItem=$subscriptionInfo->items->data[0]->id;
              $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
              $stripe->subscriptionItems->createUsageRecord($subscriptionItem,['quantity' => 1]);
              /*Record usage API*/

              //if($userData['email'] && getUserInfo($postedData['user_id'])->email_enable==1) {
              if($userData['app_used']==0){
                unset($userData['app_used']);
              }
              if(!isset($userData['app_used']) && empty($userData['eta']) && isset($postedData['email'])) {
                $this->sendOrderEmail($emailData);
              }else if(!isset($userData['app_used']) && !empty($userData['eta']) &&  isset($postedData['email'])) {
                $this->sendEtaEmail($emailData);
              }


              /*push will send or not*/
              if(isset($userData['app_used']) && $userData['app_used']==1) {
                $data = array('title'=>'','body'=>'Your order number #'.$insertedOrder->order_number.' has been created at '.getBusinessName().'. Please confirm it!');

                $extraData=array(
                  'id'=>$insertedOrder->id,
                  'order_number'=>$insertedOrder->order_number,
                  'company_id'=>$insertedOrder->company_id,
                  'amount'=>($insertedOrder->amount)?$insertedOrder->amount:0,
                  'actual_order_time'=>($insertedOrder->actual_order_time)?convertDirectToLocal($insertedOrder->actual_order_time):null,
                  'business_name'=>getBusinessName(),
                  'business_address'=>getBusinessSiteInfo($insertedOrder->location_id)->address,
                  'type'=>'confirm_push'
                );
                if($insertedOrder->eta){
                  $now = date('Y-m-d');
                  $etaDate = date('Y-m-d',strtotime($insertedOrder->eta));
                  if ($etaDate > $now){
                    $extraData['eput']=convertDirectToLocal($insertedOrder->eta);
                  }else{
                    $extraData['eput']=convertDirectToLocal($insertedOrder->eta,1);
                  }
                }
                sendPushNotification($data,$postedData['user_id'],$extraData);
              }
              /*push will send or not*/
              Session::flash('success', 'Order has been added successfully');
              return redirect()->route('home');
            }
          }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
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
      if($id){
        $orderInfo=Order::with(['user'])->find($id);
        if($orderInfo){
          if($orderInfo->status=='complete'){
            Session::flash('warning', 'You can not update completed order.');
            return redirect()->route('home');
          }
          $phoneNumber='';
          if($orderInfo->user->phone_number){
            $phoneNumber=explode(" ",$orderInfo->user->phone_number);
            if(count($phoneNumber)>1){
              $phoneNumber=$phoneNumber[1];
            }
          }
          return view('orders.edit',compact('orderInfo','phoneCode','phoneNumber'));
        }else{
          Session::flash('warning', 'Order info not found in database');
          return redirect()->route('home');
        }
      }else{
        Session::flash('warning', 'id is missing in url');
        return redirect()->route('home');
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $orderInfo=Order::with(['user'])->find($id);
        $jsonArr['orderInfo']=$orderInfo;
        $jsonArr['order_paid']='';
        $postedData = $userData = $request->all();
        unset($postedData['_method']);
        if($postedData['status']=='complete'){
          $orderTimeDiff=getTimeDifference($orderInfo->eta_current);
          if($orderTimeDiff){
            $postedData['total_time']=$orderTimeDiff;
          }
        }
        $mainStatus=$postedData['status'];
        if(isset($postedData['amount'])){
          $paymentSaveArr=array(
            'user_id'=>$orderInfo->user_id,
            'order_id'=>$id,
            'amount'=>$postedData['amount'],
            'order_date'=>$orderInfo->created_at,
            'created_at'=>date('Y-m-d H:i:s'),
            'transaction_id'=>'cash_'.uniqid()
          );
          if(Payment::create($paymentSaveArr)) {
            $postedData['status']='ready';
            $orderInfo->update($postedData);
          }
        }else{
          $orderInfo->update($postedData);
        }
        if($orderInfo){
          $data['name']=$orderInfo->user->first_name.' '.$orderInfo->user->last_name;
          $data['order_number']=$orderInfo->order_number;
          if($postedData['status']=='ready'){
            $data['subject']="Hey, your order number ".$orderInfo->order_number." at ".getBusinessName()." is ready for pickup now.";
            $data['message']="Your order number ".$orderInfo->order_number." at ".getBusinessName()." is ready for pickup now.";
            if(!getNormalUserInfo($orderInfo->user_id)->device_token && $orderInfo->user->email) {
              Mail::to($orderInfo->user->email)->send(new OrderReady($data));
            }
          }else{
            $data['subject']='Hey, your order number '.$orderInfo->order_number.' at '.getBusinessName().' has been completed.';
            $data['message']='Your order number '.$orderInfo->order_number.' at '.getBusinessName().' has been completed.';
            if(!getNormalUserInfo($orderInfo->user_id)->device_token && $orderInfo->user->email) {
              Mail::to($orderInfo->user->email)->send(new OrderComplete($data));
            }
          }
          $pushMessage='Your order is '.$postedData['status'].' now.';
          $extraData['type']='';
          if($mainStatus=='payment'){
            $extraData['type']='cash_payment';
            $pushMessage="Your payment for order number #".$orderInfo->order_number." at ".getBusinessName()." has been completed. Thank you for using Hey it's ready.";
          }else{
            if($postedData['status']=='complete'){
              $extraData['type']='order_completed';
              $pushMessage="Your order number #".$orderInfo->order_number." at ".getBusinessName()." has been completed. Thank you for using Hey it's ready.";
              Order::where('status','complete')->update(['spot_number'=>'']);
            }else if($postedData['status']=='ready'){
                $pushMessage='Your order number #'.$orderInfo->order_number.' at '.getBusinessName().' is ready for pick up now. ';
                $extraData['type']='order_ready';
            }
          }
          $data = array('title'=>'','body'=>$pushMessage);
          sendPushNotification($data,$orderInfo->user_id,$extraData);
          $jsonArr['status']=true;
        }else{
          $jsonArr['status']=false;
        }
        $paymentCount=Payment::where(['order_id'=>$id])->count();
        if($paymentCount>0){
          $jsonArr['order_paid']='<button class="btn btn-success">Yes</button>';
        }
        echo json_encode($jsonArr);die;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /*send eta order email*/
    private function sendEtaEmail($data){
      /*$data['subject']='Your order at '.getBusinessName().' Order number:'.$data['order_number'].' is estimated to be ready for pick up '.$data['order_date'].' at '.$data['order_time'];*/
      $data['subject']='Hey, your order has been confirmed at '.getBusinessName().'.';
      $data['message']=getBusinessName().' has confirmed your order number '.$data['order_number'].' successfully.';
      Mail::to($data['email'])->send(new EtaEmail($data));
    }

    /*set eta for order*/
    public function setEta(Request $request){
      if($request->isMethod('post')){
        extract($request->all());
        $postedData=$request->all();
        $order=Order::with(['user'])->find($order_id);
        //$order->eta=date('Y-m-d H:i:s',strtotime($eta));
        $postedEta=$postedData['eta'];
        if($order->actual_order_time){
          $order->actual_order_time=Carbon::create($order->actual_order_time);
        }
        if(!$order->eta){
          $delayed=0;
          $order->eta=$this->setEtaTime($postedData);
          $order->actual_order_time=$order->eta;
          $order->eta_current=Carbon::now();
        }else{
          $delayed=1;
          $order->delayed=1;
          $order->eta=$this->updateEtaTime($postedData,$order->eta);
        }

        if(isset($postedData['amount']) && $postedData['amount']) {
          $order->amount=$postedData['amount'];
        }

        $order->status='confirm';
        if($order->save()){
          /*set email data for eta*/
          $emailData=array(
            'email'=>$order->user->email,
            'name'=>$order->user->first_name.' '.$order->user->last_name,
            'eta'=>$order->eta,
            'order_number'=>$order->order_number,
            'order_date'=>date('F d,Y',strtotime($order->eta)),
            'order_time'=>date('h:i A',strtotime($order->eta))
          );
          if(!getNormalUserInfo($order->user_id)->device_token && $order->user->email) {
            $this->sendEtaEmail($emailData);
          }

          /*set email data for eta*/
          Timezone::convertToLocal($order->eta,'d/m/Y h:i:s a');
          if($order->eta){
            if(strpos($postedEta,'Min')>0) {
              $postedEta=trim(str_replace("Min","",$postedEta));
              $data = array('title'=>'','body'=>"Your order EPUT number #".$order->order_number." at ".getBusinessName()." is in ".$postedEta." minute(s).");
            }else if(strpos($postedEta,'Hour')>0) {
              $postedEta=trim(str_replace("Hour","",$postedEta));
              $data = array('title'=>'','body'=>"Your order EPUT number #".$order->order_number." at ".getBusinessName()." is in ".$postedEta." Hours.");
            }else{
              if(strpos($postedEta,"-")>0) {
                $data = array('title'=>'','body'=>"Your order EPUT number #".$order->order_number." at ".getBusinessName()." time is ".$postedEta.".");
              }else{
                $data = array('title'=>'','body'=>"Your order EPUT number #".$order->order_number." at ".getBusinessName()." is in ".$postedEta." minute(s).");
              }
            }
            if($delayed===1){
              $newDelayedDate=Timezone::convertToLocal($order->eta,'F d,Y');
              $newDelayedTime=Timezone::convertToLocal($order->eta,'h:i a');
              $data = array('title'=>'','body'=>'Your order number #'.$order->order_number.' at '.getBusinessName().' is delayed.Your new EPUT is '.$newDelayedDate.' at '.$newDelayedTime);
              sendPushNotification($data,$order->user_id,array('type'=>'delayed'));
            }else{
              sendPushNotification($data,$order->user_id);
            }
          }else{
            $data = array('title'=>'','body'=>'Order EPUT has been set.');
            sendPushNotification($data,$order->user_id);
          }
          $json['local_12']=Timezone::convertToLocal($order->eta,'d/m/Y h:i a');
          $json['local_12_time_only']=Timezone::convertToLocal($order->eta,'h:i a');
          $json['local_24']=Timezone::convertToLocal($order->eta,'d/m/Y H:i');
          $json['actual_order_time']=Timezone::convertToLocal($order->actual_order_time,'d/m/Y h:i a');
          echo json_encode($json);die;
        }else{
          return false;
        }
      }
    }

    /*get user info with user id*/
    public function getUserInfo(Request $request){
      $name=$request->query('term');
      $uid=$request->query('uid');
      if($uid){
        $userInfo=User::find($uid);
        echo json_encode($userInfo);die;
      }else{
        if($name){
          $userInfo=User::orWhere('name', 'like', '%' . $name . '%')
          ->orWhere('email', 'like', '%' . $name . '%')
          ->orWhere('phone_number', 'like', '%' . $name . '%')
          ->where('role','user')->get();
          $userInfoArr=array();
          if($userInfo->count()>0){
            foreach($userInfo as $user){
              if($user->role=='user'){
                $data['id']=$user->id;
                $data['label']=$user->name;
                $data['value']=$user->name;
                $userInfoArr[]=$data;
              }
            }
          }
          echo json_encode($userInfoArr);die;
        }
      }
    }

    /*This will get new orders for business
    and return to jquery ajax*/
    public function ajaxOrders($cid){
      $orders=Order::with(['user'])->where(['company_id'=>$cid,'new_order'=>1,
      'location_id'=>getDefaultLocationLoggedUser()])->get();
      if($orders->count()>0){
        return View::make('orders.ajax_orders',compact('orders'));
      }else{
        return '';
      }

    }

    /*This will get new orders for business
    and return to jquery ajax*/
    public function updateAjaxOrders($cid){
      Order::where(['company_id'=>$cid,
      'location_id'=>getDefaultLocationLoggedUser()])->update(['new_order'=>0]);
    }

    /*This will check any calls user using app*/
    public function ajaxCallsOrders($cid){
      $orders=Order::with(['user'])->where(['company_id'=>$cid,'cancel'=>1,'deleted'=>0,['status','<>','complete'],
      'location_id'=>getDefaultLocationLoggedUser()])->get();
      $orderConfirm=Order::with(['user'])->where(['company_id'=>$cid,'confirm'=>1,'deleted'=>0,['status','<>','complete'],
      'location_id'=>getDefaultLocationLoggedUser()])->get();
      if($orders->count()>0 || $orderConfirm->count()>0){
        if($orderConfirm->count()>0){
          $orders=$orders->merge($orderConfirm);
        }
        $orderArr=array();
        foreach ($orders as $order) {
          if($order->user->app_used==1){
            Order::where('id',$order->id)->update(['call_btn'=>0]);
            $orderArr[]=$order;
          }
        }
        echo json_encode($orderArr);die;
      }else{
        return '';
      }
    }

    /*This will get single order info*/
    public function ajaxOrderDetail($oid){
      $order=Order::with(['user','payment'])->where(['id'=>$oid])->get();
      $cartCount=0;
      $cartInfo=array();
      if($order->count()>0){
        $order=$order->first();
        if($order->has('payment') && $order->payment){
          $cartCount=Cart::where('payment_id',$order->payment->id)->count();
          if($cartCount>0){
            $cartInfo=Cart::with(['category'=>function($query){
              $query->select('id','name');
            },'company'=>function($query){
              $query->select('id','company_name');
            },'item'=>function($query){
              $query->select('id','name');
            },'restaurant_menu'=>function($query){
              $query->select('id','name');
            }])
            ->where('payment_id',$order->payment->id)->get();
          }
        }
        return view('home-popup',compact('order','cartInfo','cartCount'));
      }else{
        return false;
      }
    }


    /*This will get single order info*/
    public function ajaxUserOrder($cid){
      $order=TmpOrder::where(['company_id'=>$cid,'new_order'=>0])->get();
      if($order->count()>0){
        $order=$order->first();
        $userInfo=getNormalUserInfo($order->user_id);
        if($userInfo){
          $orderArr['name']=$userInfo->name;
          $orderArr['email']=$userInfo->email;
          $orderArr['phone_number']=$userInfo->phone_number;
          $orderArr['user_id']=$userInfo->id;
          $order->new_order=1;
          $order->save();
          echo json_encode($orderArr);die;
        }
      }else{
        return '';
      }
    }

    /*This will get track order detail*/
    public function ajaxTrackDetail($id){
      $order=Order::with(['company'])->find($id);
      if($order){
          $cid=$order->company->id;
          $userInfo=User::where(['company_id'=>$cid,'role'=>'company'])->first();
          if($order->eta){
            $date=Carbon::create($order->eta)->timezone($userInfo->timezone)->format('d/m/Y h:i:s a');
            $onlyDate=Carbon::create($order->eta)->timezone($userInfo->timezone)->format('M d, Y');
            $onlyTime=Carbon::create($order->eta)->timezone($userInfo->timezone)->format('h:i a');
            $dateTwentyFourFormat=Carbon::create($order->eta)->timezone($userInfo->timezone)->format('d/m/Y H:i:s');
            $order->eta=$date;
            $order->only_date=$onlyDate;
            $order->only_time=$onlyTime;
            $order->eta_24=$dateTwentyFourFormat;
          }

          /*Order status text changed*/
          $order->status=ucfirst($order->status);
          switch($order->status){
            case 'Confirm':
              $order->status='Confirmed';
              break;
            case 'Complete':
              $order->status='Completed';
              break;
          }
          if($order->status=='Confirmed' && $order->delayed==1){
            $order->status='EPUT Updated';
          }
          /*Order status text changed*/

          echo json_encode($order);die;
      }else{
        return '';
      }
    }

    /*delete complete orders*/
    public function deleteCompleteOrder(){
      $deleted=Order::where(['status'=>'complete','company_id'=>auth()->user()->company_id,
      'location_id'=>getDefaultLocationLoggedUser()])->update(['deleted'=>1]);
      if($deleted){
        Session::flash('success', 'Completed orders has been successfully deleted');
        return redirect()->route('home');
      }else{
        Session::flash('warning', 'There is some problem in delete.');
        return redirect()->route('home');
      }
    }

    /*delete complete orders*/
    public function delete(Request $request){
      $postedData=$request->all();
      if($postedData){
        extract($postedData);
        $hiddenOrderId=explode(",",$hiddenOrderId);
        /*$deleted=Order::whereIn('id',$hiddenOrderId)
        ->where(['company_id'=>auth()->user()->company_id])->update(['deleted'=>1]);*/
        $deleted=Order::whereIn('id',$hiddenOrderId)->update(['deleted'=>1]);
        if($deleted){
          Session::flash('success', 'Orders has been successfully deleted');
          return redirect()->route('home');
        }else{
          Session::flash('warning', 'There is some problem in delete.');
          return redirect()->route('home');
        }
      }
    }

    //Will send reminder of email and push for ready
    public function readyReminder($id){
      if($id){
        $orderInfo=Order::with(['user'])->find($id);
        if($orderInfo){
          $userInfo=getNormalUserInfo($orderInfo->user_id);
          $orderInfo->update(array('friendly_reminder'=>1));
          if($userInfo->notification==1){
            $pushMessage='Just a friendly reminder that your order is ready for pick up at '.getBusinessName();
            $data = array('title'=>'','body'=>$pushMessage);
            $extraData['type']='order_reminder';
            sendPushNotification($data,$orderInfo->user_id,$extraData);
          }
          if(!$userInfo->device_token && $orderInfo->user->email){
            $datas['name']=$orderInfo->user->first_name.' '.$orderInfo->user->last_name;
            $datas['order_number']=$orderInfo->order_number;
            $datas['subject']="Hey, your order number ".$orderInfo->order_number." at ".getBusinessName()." is ready for pickup now.";
            $datas['message']="Your order number ".$orderInfo->order_number." at ".getBusinessName()." is ready for pickup now.";
            Mail::to($orderInfo->user->email)->send(new OrderReady($datas));
          }
        }
        die('done');
      }
    }

    //save reorder images
    public function imagesReorder(Request $request){
      if($request->isMethod('post')){
        $postedImages=$request->all();
        if($postedImages['ids']){
          $count=1;
          foreach($postedImages['ids'] as $id){
            QrItem::where('id',$id)->update(['img_order'=>$count]);
            $count++;
          }
          echo "success";die;
        }
      }
    }

    /*update order detail*/
    public function updateOrderDetail(Request $request,$id){
      if($request->isMethod('put')){
        $orderInfo=$origialOrderInfo=Order::find($id);
        if($orderInfo){
          $postedData = $userData = $emailData = $request->all();
          if($postedData['phone_code']){
            $userData['phone_number']=str_replace(array('(',')','-'),'',$userData['phone_number']);
            $userData['phone_number']=preg_replace("/\s+/", "", $userData['phone_number']);
            $postedData['phone_number']=$postedData['phone_code'].' '.$postedData['phone_number'];
            $userData['phone_number']=$userData['phone_code'].' '.$userData['phone_number'];
            unset($postedData['phone_code'],$userData['phone_code'],$emailData['phone_code']);
          }
          $existUserId=checkUserExistByPhone($userData['phone_number']);
          if($existUserId!=$orderInfo->user_id){
            Session::flash('warning-pop', 'Phone number already exist with other user.');
            return redirect()->back();
          }

          //call helper method
          $postedData['user_id']=$orderInfo->user_id;

          /*validation*/
          $validationRules=array(
            'order_number' => 'required',
            'phone_number' => 'required',
            'name' => 'required'
          );
          $messageArray=array(
            'order_id.required' => 'Order Number is required',
          );
          if(!$postedData['user_id']) {
            $validationRules['phone_number']='required|unique:users,phone_number';
            $messageArray['phone_number.unique'] = 'Phone number is already exist.';
          }
          if(!$postedData['user_id'] && isset($postedData['email']) && $postedData['email']) {
            $validationRules['email']='unique:users,email';
            $messageArray['email.unique'] = 'Email is already exist.';
          }
          $validatedData = $request->validate($validationRules,$messageArray);
          /*validation*/

          /*app used or not*/
          if(isset($postedData['app_used'])){
            if($postedData['app_used']==1){
              updateAppUsed($postedData['user_id'],$postedData['app_used']);
              $postedData['call_btn']=0;
            }else{
              updateAppUsed($postedData['user_id'],0);
              $postedData['confirm']=1;
              $postedData['call_btn']=1;
            }
          }else{
            $postedData['call_btn']=$orderInfo->call_btn;
          }

          /*app used or not*/

          if($postedData['user_id']) {
            unset($postedData['name'],$postedData['email'],$postedData['phone_number'],$postedData['app_used']);

            /*order push when amount is entered on edit order page*/
            if($origialOrderInfo->amount==0 && isset($postedData['amount']) && $postedData['amount']>0){
              $pushMessage='Your can pay for your order number #'.$orderInfo->order_number.' at '.getBusinessName().' now. ';
              $data = array('title'=>'','body'=>$pushMessage);
              $extraData['type']='order_payment';
              sendPushNotification($data,$orderInfo->user_id,$extraData);
            }
            /*order push when amount is entered on edit order page*/

            if($orderInfo->update($postedData)) {
              $userInfo=User::find($postedData['user_id']);
              unset($userData['order_number'],$userData['amount']);
              $userInfo->update($userData);
              Session::flash('success', 'Order has been updated successfully');
              return redirect()->route('home');
            }
          }

        }
      }

    }

    /*this will get spot number if exist for order*/
    public function checkSpotExist($oid){
      $returnData['order_locate']='';
      $returnData['spot_number']='';
      $returnData['spot_color']='';
      $returnData['order_paid']='<button class="btn btn-danger">No</button>';
      $returnData['order_confirm']='<button class="btn btn-danger">No</button>';
      $returnData['geofence_count']=getGeofenceCustomers();
      if($oid){
        $orderInfo=Order::find($oid);
        if($orderInfo){
          if($orderInfo->locate==1){
            $imgPath=asset('img/tick-mark.jpg');
            $returnData['order_locate']='<img src="'.$imgPath.'" height="25" width="25"/>';
          }
          if($orderInfo->confirm==1){
            $returnData['order_confirm']='<button class="btn btn-success">Yes</button>';
          }
          $returnData['spot_number']=$orderInfo->spot_number;
          $returnData['spot_color']=$orderInfo->spot_color;
        }
        $paymentQuery=Payment::where(['order_id'=>$oid]);
        if($paymentQuery->count()>0){
          $paymentInfo=$paymentQuery->first();
          if(strpos($paymentInfo->transaction_id, 'cash')===false){
            $returnData['order_paid']='<button class="btn btn-success">Yes</button>';
          }
        }
      }
      echo json_encode($returnData);die;
    }

    /*this will check user is locate or not*/
    public function getOrderLocate($oid){
      if($oid){
        $orderInfo=Order::find($oid);
        if($orderInfo){
          if($orderInfo->locate==1){
            $imgPath=asset('img/tick-mark.jpg');
            echo '<img src="'.$imgPath.'" height="25" width="25"/>';die;
          }
        }
      }
      echo "";die;
    }

    /*this will make payment into DB*/
    public function setOrderPaid($id){
      $orderInfo=Order::find($id);
      $paymentSaveArr=array(
        'user_id'=>$orderInfo->user_id,
        'order_id'=>$id,
        'amount'=>$orderInfo->amount,
        'order_date'=>$orderInfo->created_at->format('Y-m-d H:i:s'),
        'created_at'=>date('Y-m-d H:i:s'),
        'transaction_id'=>'cash_'.uniqid()
      );
      if(Payment::create($paymentSaveArr)) {
        $postedData['status']='ready';
        $orderInfo->update($postedData);
      }
      Session::flash('success', 'Order payment has been paid successfully.');
      return redirect()->route('home');
    }
}
