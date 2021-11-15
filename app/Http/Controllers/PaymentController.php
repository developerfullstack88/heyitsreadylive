<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use App\User;
use Mail;
use App\Mail\BilledEmail;
class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web', ['except' => ['success','failed']]);
    }

    /*will send billed email to customer*/
    private function billedEmail($uid){
      if($uid){
        $data=User::find($uid);
        $data->subject='Customer Billed';
        Mail::to($data->email)->send(new BilledEmail($data));
      }
    }
    public function success(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $json = file_get_contents('php://input');
          $object = json_decode($json);
          $filePath=public_path().'/webhook/webhook.txt';
          file_put_contents($filePath,$json);

          /*$filePath=public_path().'/webhook/webhook.txt';
          $json=file_get_contents($filePath);
          $object = json_decode($json);*/
          $dataArray = $object->data->object->lines->data;
          $customerId=$object->data->object->customer;
          if($object->type == 'invoice.payment_succeeded'){
              foreach ($dataArray as $key => $value) {
                $subscriptionId=$value->subscription;
                $planId=$value->plan->id;
                $endDate = date('Y-m-d H:i:s', $value->period->end);
                $startDate = date('Y-m-d H:i:s', $value->period->start);
                $subscriptionInfo=Subscription::where([
                  'customer_id'=>$customerId,
                  'plan_id'=>$planId,
                  'subscription_id'=>$subscriptionId
                ])->get();
                if($subscriptionInfo->count()>0){
                  $subscriptionInfo=$subscriptionInfo->first();
                  if($subscriptionInfo->trial_end_date){
                    $trialEndDate=date('Y-m-d',strtotime($subscriptionInfo->trial_end_date));
                    $currentDate=date('Y-m-d');
                    if($currentDate<$trialEndDate){
                      continue;
                    }
                  }
                  $this->billedEmail($subscriptionInfo->user_id);
                  $subscriptionInfo->free_trial=0;
                  $subscriptionInfo->trial_start_date=null;
                  $subscriptionInfo->trial_end_date=null;
                  $subscriptionInfo->subscription_start_date=$startDate;
                  $subscriptionInfo->subscription_end_date=$endDate;
                  $subscriptionInfo->save();
                }

              }
          }
      }
      die('done');
    }

    public function failed(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $json = file_get_contents('php://input');
          $object = json_decode($json);
          $filePath=public_path().'/webhook/webhook_failed.txt';
          file_put_contents($filePath,$json);
          die('done');
          /*$dataArray = $object->data->object->lines->data;
          $customerId=$object->data->object->customer;
          if($object->type == 'invoice.payment_succeeded'){
              foreach ($dataArray as $key => $value) {
                  $endDate = date('Y-m-d H:i:s', $value->period->end);
                  $startDate = date('Y-m-d H:i:s', $value->period->start);
              }
          }*/
      }
    }
}
