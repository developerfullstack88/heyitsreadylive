<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cart;
//use App\User;
//use Mail;
use Carbon\Carbon;

class CartNotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send cart notification to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      /*$data=User::find(1);
      $data->subject='testing';
      try {
        Mail::send('emails.signup',['userData' => $data],function($message){
          $message->to('jasmanindergill@gmail.com')->subject('testing');
          $address = env("MAIL_FROM_ADDRESS");
          $name = "Hey It's Ready";
          $message->from($address, $name);
        },true);
        $this->info("Success");
      } catch (Exception $ex) {
        $this->info("We've got errors!");
      }*/

      $cartsDetail=Cart::
      whereDate('created_at', Carbon::today())
      ->select('id','user_id','payment_id','created_at')
      ->whereRaw('id IN (select MAX(id) FROM carts GROUP BY user_id)')->get();
      $currentTime=Carbon::now();

      if($cartsDetail->count()>0){
        foreach ($cartsDetail as $cart) {
          $cartDate=$cart->created_at;
          $cartDiff=cartTimeDifference($cartDate,$currentTime);
          $userInfo=getUserInfo($cart->user_id);
          if($cartDiff['hour']>0 || $cartDiff['minute']>58){
            if($userInfo->notification==1){
              $pushMessage='Items are exists in your cart from last 1 hour';
              $data = array('title'=>'','body'=>$pushMessage);
              $extraData['type']='order_reminder';

              if($cartDiff['hour']>0){
                sendPushNotification($data,$cart->user_id,$extraData);
              }
              if($cartDiff['minute']>58){
                sendPushNotification($data,$cart->user_id,$extraData);
              }
              $newTimecreated['created_at']=Carbon::now();
              Cart::where('id',$cart->id)->update($newTimecreated);
            }
          }
        }
      }
      $this->info('This will send cart notification to user');
    }
}
