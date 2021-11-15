<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Subscription;
use App\Mail\TrialEnd;
use Mail;
use Carbon\Carbon;

class TrialEndEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send before 3 days email to user for trial expiration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /*
    @developer:jasmaninder
    @method:allBusinessSubscribers
    @description:This will provide list of users
    */
    private function allBusinessSubscribers(){
      $allUsers=User::where('role',null);
      if($allUsers->count()>0){
        return $allUsers=$allUsers->get();
      }else{
        return false;
      }
    }

    /*
    @developer:jasmaninder
    @method:subscriptionInfo
    @description:This will return subscription info for user
    */
    private function subscriptionInfo($uid){
      if($uid){
        if(Subscription::whereNotNull('trial_end_date')->where('user_id',$uid)->exists()){
          return Subscription::whereNotNull('trial_end_date')->where('user_id',$uid)->get()->last();
        }else{
          return false;
        }
      }
    }

    /*
    @developer:jasmaninder
    @method:trialEndEmail
    @description:This will send trial end email to user
    */
    private function trialEndEmail($userInfo){
      if($userInfo){
        $userInfo->subject='Free Trial will End';
        Mail::to($userInfo->email)->send(new TrialEnd($userInfo));
      }
    }

    /*
    @developer:jasmaninder
    @method:getCustomerDetail
    @description:This will get customer detail
    */
    private function getCustomerDetail($stripeCustomerId){
      if($stripeCustomerId){
        return \Stripe\Customer::retrieve($stripeCustomerId);
      }else{
        return false;
      }
    }

    public function handle()
    {
      $businessUsers=$this->allBusinessSubscribers();
      if($businessUsers){
        foreach ($businessUsers as $user) {
          if($user->role=='' && $user->id){
            $subscriptionInfo=$this->subscriptionInfo($user->id);
            if($subscriptionInfo){
              if($subscriptionInfo->trial_end_date){
                $subscriptionEndDate = new \DateTime($subscriptionInfo->trial_end_date);
                $subscriptionEndDate->modify('-3 days');
                $endDateBeforeThreeDays = $subscriptionEndDate->format('Y-m-d');
                $currentDate=date('Y-m-d');
                if($currentDate==$endDateBeforeThreeDays){
                  $subscriptionOriginalEndDate= new \DateTime($subscriptionInfo->trial_end_date);
                  $user->date=$subscriptionOriginalEndDate->format('d,F Y');
                  $user->time=Carbon::create($subscriptionInfo->trial_end_date)->timezone($user->timezone)->format('h:i:s A');

                  $customerDetail=$this->getCustomerDetail($user->stripe_customer_id);
                  $user->card_number='';
                  if($customerDetail){
                    $cardId=$customerDetail->default_source;
                    if($cardId){
                      $cardInfo=\Stripe\Customer::retrieveSource(
                        $user->stripe_customer_id,
                        $cardId,
                      );
                      if($cardInfo){
                        $user->card_number=$cardInfo->last4;
                      }

                    }
                  }
                  $this->trialEndEmail($user);
                }
              }
            }
          }
        }
      }
      $this->info('Done');
    }
}
