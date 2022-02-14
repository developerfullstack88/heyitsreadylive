<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\User;
use App\Country;
use Auth;
use Mail;
use App\Mail\SignupEmail;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['trackOrder','privacyPolicy','termsServices','activateAccount','thankyou','marketingQr','trackQr','setupGuides','testQr']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
      $type=$request->get('type');
      $defaultSiteModal=$request->session()->get('default-site-modal');
      $request->session()->put('default-site-modal',0);

      if(Auth::user()->role=='super admin' || Auth::user()->role=='admin'){
        return redirect()->route('admin.dashboard');
      }
      if($type=='active'){
        $OrderWithoutComplete=Order::with(['user'])
        ->where(['company_id'=>Auth::user()->company_id,'new_order'=>0,'deleted'=>0,'cancel'=>0,['status','!=','complete'],
        'location_id'=>getDefaultLocationLoggedUser()])
        ->wheredate('eta',currentLocalDate())
        ->orderBy('created_at','desc')->get();
      }else if($type=='future'){
        $OrderWithoutComplete=Order::with(['user'])
        ->where(['company_id'=>Auth::user()->company_id,'new_order'=>0,'deleted'=>0,'cancel'=>0,['status','!=','complete'],
        'location_id'=>getDefaultLocationLoggedUser()])
        ->wheredate('eta','>',currentLocalDate())
        ->orderBy('created_at','desc')->get();
      }else{
        $OrderWithoutComplete=Order::with(['user'])
        ->where(['company_id'=>Auth::user()->company_id,'new_order'=>0,'deleted'=>0,'cancel'=>0,['status','!=','complete'],
        'location_id'=>getDefaultLocationLoggedUser()])
        ->orderBy('created_at','desc')->get();
      }

      if($type=='completed' || $type=='all'){
        $OrdersComplete=Order::with(['user'])
        ->where(['company_id'=>Auth::user()->company_id,'new_order'=>0,'deleted'=>0,'cancel'=>0,'status'=>'complete',
        'location_id'=>getDefaultLocationLoggedUser()])
        ->orderBy('created_at','desc')->get();
        if($type=='all')
          $orders=$OrderWithoutComplete->merge($OrdersComplete);
        else
          $orders=$OrdersComplete;
        return view('home',compact('orders','OrdersComplete','defaultSiteModal'));
      }else{
        $orders=$OrderWithoutComplete;
        return view('home',compact('orders','defaultSiteModal'));
      }

    }

    public function testQr(){
      /*stripe testing*/
      $stripe = new \Stripe\StripeClient('sk_test_51Jom48BQPKc0l2BLwxhs6zgnkbQFVNHaCuv4sxhdFSeLTyJTPpyOYNBLpFccYI9kbp1DLIABKmsESGisv2BUsOdy001WV8ZFyp');
      $all=$stripe->charges->all(['limit' => 10]);
      /*$all=$stripe->payouts->all([
        'destination' => 'acct_1036Wg2QeS4g6WPv'
      ]);*/
      echo "<pre>";print_r($all);die;
      $all=$stripe->accounts->all();
      echo "<pre>";print_r($all);die;
      $stripe->accounts->delete('acct_1JnMpoQIpKQfhmDk',[]);die('done');
      $stripeAcc=$stripe->accounts->create([
        'type' => 'custom',
        'country' => 'CA',
        'email' => 'jas2@mailinator.com',
        'capabilities' => [
          'card_payments' => ['requested' => true],
          'transfers' => ['requested' => true],
        ],
        'business_type'=>'company',
        'company'=>[
          'address'=>[
            'city'=>'Brampton',
            'country'=>'CA',
            'line1'=>'241 Advance Boulevard Brampton, ON L6T 4J2',
            'postal_code'=>'L5S',
            'state'=>'Ontario',
          ],
          'directors_provided'=> false,
          'executives_provided'=> false,
          'name'=>'jas company',
          'tax_id'=>'BGH678566',
          //'structure'=>'private_corporation'
        ],
        'external_account'=>[
          'object'=>'bank_account',
          'country'=>'CA',
          'currency'=>'cad',
          'account_holder_name'=>'Jasmaninder Singh',
          'account_number'=>'000123456789',
          'routing_number'=>'11000-000'
        ],
        'tos_acceptance'=>[
          'date'=>time(),
          'ip'=>$_SERVER['REMOTE_ADDR']
        ]
      ]);
      echo "<pre>";print_r($stripeAcc);die;
      /*$stripeAcc=$stripe->accounts->create([
        'type' => 'custom',
        'country' => 'CA',
        'email' => 'jas1@mailinator.com',
        'capabilities' => [
          'card_payments' => ['requested' => true],
          'transfers' => ['requested' => true],
        ],
        'business_type'=>'individual',
        'individual'=>[
          'address'=>[
            'city'=>'Brampton',
            'country'=>'CA',
            'line1'=>'241 Advance Boulevard Brampton, ON L6T 4J2',
            'postal_code'=>'L5S',
            'state'=>'Ontario',
          ],
          'dob'=>[
            'day'=>10,
            'month'=>03,
            'year'=>1988
          ],
          'first_name'=>'jasmaninder',
          'last_name'=>'singh'
        ],
        'external_account'=>[
          'object'=>'bank_account',
          'country'=>'CA',
          'currency'=>'cad',
          'account_holder_name'=>'Jasmaninder Singh',
          'account_number'=>'000123456789',
          'routing_number'=>'11000-000'
        ],
        'tos_acceptance'=>[
          'date'=>time(),
          'ip'=>$_SERVER['REMOTE_ADDR']
        ]
      ]);
      echo "<pre>";print_r($stripeAcc);die;*/
      /*stripe testing*/
      //$data = array('title'=>'','body'=>'testing');
      //sendPushNotification($data,2,array('id'=>2,'order_number'=>'abc'));
      /*$data=User::find(1);
      $data->subject='testing';
      Mail::to('jasmanindergill@gmail.com')->send(new SignupEmail($data));*/
      //die('done');
      $data=User::find(3);
      $data->subject='testing';
      //echo "<pre>";print_r(Mail::failures());die;
      try {
        Mail::to('jas1@mailinator.com')->send(new SignupEmail($data));
        /*Mail::send('emails.signup',['userData' => $data],function($message){
          $message->to('jas1@mailinator.com')->subject('testing');
          //echo public_path('files/Set_up_guide_for_full_app.pdf');die;
          /*$message->attach(public_path('files/Set_up_guide_for_menu_QR_Code_only.pdf'),[
            'mime' => 'application/pdf',
          ]);
          $message->attach(public_path('files/setup_guide.pdf'),[
            'mime' => 'application/pdf',
          ]);
        $address = env("MAIL_FROM_ADDRESS");
        $name = "Hey It's Ready";
        $message->from($address, $name);
      },true);*/
        echo "Success";die;
      } catch (Exception $ex) {
        // Debug via $ex->getMessage();
        echo "We've got errors!";die;
      }
    }

    /*This will send in completed push
    after 5 min*/
    public function sendIncompletePush($oid){
      if($oid){
        $orderInfo=Order::find($oid);
        $data = array('title'=>'','body'=>'Order is not complete yet.');
        return sendPushNotification($data,$orderInfo->user_id);
      }
    }

    /*Track order method*/
    public function trackOrder(Request $request){
      $orderInfo= $userInfo = array();
      $notFound=0;
      $originalPhone='';
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
      $oid=$request->get('id');
      if($oid){
        $autoOrderInfo=Order::with(['user'])->find($oid);
        if($autoOrderInfo){
          $phoneArr=explode(" ",$autoOrderInfo->user->phone_number);
          if(count($phoneArr)>1){
            $originalPhone=$phoneArr[1];
            $phoneCode=$phoneArr[0];
          }
        }
      }
      if($request->isMethod('post')){
        /*fetch phone code*/
        $phoneNumber = $originalPhone = $request->get('phone_number');
        $phoneNumber=str_replace(array('(',')','-'),'',$phoneNumber);
        $phoneNumber=preg_replace("/\s+/", "", $phoneNumber);

        if($phoneCode){
          $phoneNumber=$phoneCode.' '.$phoneNumber;
        }
        /*fetch phone code*/
        $userInfo=User::where('phone_number',$phoneNumber)->get();
        if($userInfo->count()>0){
          $userInfo=$userInfo->first();
          $uid=$userInfo->id;
          $orderInfo=Order::with(['user','company'])->where(['user_id'=>$uid,'deleted'=>0])->orderBy('created_at','desc')->limit(1)->get();
          if($orderInfo->count()>0){
            $orderInfo=$orderInfo->last();
            /*update order and user info*/
            $orderInfo->confirm=1;
            $orderInfo->save();
            $userInfo->app_used=1;
            $userInfo->save();
            /*update order and user info*/

            /*Order status text changed*/
            $orderInfo->status=ucfirst($orderInfo->status);
            switch($orderInfo->status){
              case 'Confirm':
                $orderInfo->status='Confirmed';
                break;
              case 'Complete':
                $orderInfo->status='Completed';
                break;
            }
            if($orderInfo->status=='Confirmed' && $orderInfo->delayed==1){
              $orderInfo->status='EPUT Updated';
            }
            /*Order status text changed*/

            $userInfo=User::where(['company_id'=>$orderInfo->company_id,'role'=>'company'])->first();
            $orderInfo->eta_original=$orderInfo->eta;
            $orderInfo->eta_24=$orderInfo->eta;
            if($orderInfo->eta){
              $date=Carbon::create($orderInfo->eta)->timezone($userInfo->timezone)->format('d/m/Y h:i:s a');
              $onlyDate=Carbon::create($orderInfo->eta)->timezone($userInfo->timezone)->format('M d, Y');
              $onlyTime=Carbon::create($orderInfo->eta)->timezone($userInfo->timezone)->format('h:i a');
              $dateTwentyFourFormat=Carbon::create($orderInfo->eta)->timezone($userInfo->timezone)->format('d/m/Y H:i:s');
              $orderInfo->eta=$date;
              $orderInfo->only_date=$onlyDate;
              $orderInfo->only_time=$onlyTime;
              $orderInfo->eta_24=$dateTwentyFourFormat;
            }
          }else{
            $orderInfo=array();
            $notFound=1;
          }
        }
      }
      return view('track-order',compact('orderInfo','userInfo','notFound','originalPhone','phoneCode'));
    }

    public function setLocale($locale){
      if (! in_array($locale, ['en', 'es', 'fr','aus','ca','nz'])) {
        abort(400);
      }
      Session::put('locale', $locale);
      return redirect()->back();
    }

    /*save token of logged business user*/
    public function saveToken (Request $request)
    {
      if(auth()->user()){
        $user = User::find(auth()->user()->id);
        $user->device_token = $request->fcm_token;
        $user->save();
        if($user)
          return response()->json(['message' => 'User token updated']);
      }
      return response()->json(['message' => 'Error!']);
    }

    /*
    @developer:jasmaninder
    @method:-Privacy policy page
    @description:-This will used for privcy policy page
    */
    public function privacyPolicy(){
      return view('home.privacy-policy');
    }

    /*
    @developer:jasmaninder
    @method:-termsServices
    @description:-terms and service of heyitsready
    */
    public function termsServices(){
      return view('home.terms-services');
    }

    /*
    @developer:jasmaninder
    @method:-activateAccount
    @description:-This will open set password page.
    */
    public function activateAccount($id,Request $request){
      if($id){
        if($request->isMethod('post')){
          $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
          ]);
          $postedData = $request->all();
          extract($postedData);
          $userInfo = User::find($id);
          $userInfo->password=bcrypt($password);
          $userInfo->active=1;
          if($userInfo->save()){
            if($userInfo->role!=USER){
              Mail::send('emails.signup',['userData' => $userInfo],function($message) use($userInfo){
                $message->to($userInfo->email)->subject('Welcome Email');
                //echo public_path('files/Set_up_guide_for_full_app.pdf');die;
                $message->attach(public_path('files/Set_up_guide_for_menu_QR_Code_only.pdf'),[
                  'mime' => 'application/pdf',
                ]);
                $message->attach(public_path('files/setup_guide.pdf'),[
                  'mime' => 'application/pdf',
                ]);
              $address = env("MAIL_FROM_ADDRESS");
              $name = "Hey It's Ready";
              $message->from($address, $name);
              },true);
            }

            Session::flash('success', 'Congratulations!You have successfully activate account.');
            if($userInfo->role!=USER){
              Auth::loginUsingId($userInfo->id);
            }
            return redirect()->route('login');
          }else{
            Session::flash('warning', 'There is some problem in adding password.');
          }
        }
        return view('auth.set-password')->with(compact('id'));
      }
    }

    /*
    @developer:jasmaninder
    @method:-thankyou
    @description:-This will open thank you page
    */
    public function thankyou(){
      return view('auth.thankyou');
    }

    public function marketingQr(){
      $qrUrl='www.heyitsready.com';
      // instantiate the barcode class
      $barcode = new \Com\Tecnick\Barcode\Barcode();
      // generate a barcode
      $bobj = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -8,                             // bar width (use absolute or negative value as multiplication factor)
          -8,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -1)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color
      return view('home.marketing-qr',compact('bobj'));
    }

    public function trackQr(){
      $qrUrl='https://heyitsready.app/track-order';
      // instantiate the barcode class
      $barcode = new \Com\Tecnick\Barcode\Barcode();
      // generate a barcode
      $bobj = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -8,                             // bar width (use absolute or negative value as multiplication factor)
          -8,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -1)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color
      return view('home.marketing-qr',compact('bobj'));
    }

    public function steamBoat(){
      $qrUrl='https://steamboathomeconcierge.com/';
      // instantiate the barcode class
      $barcode = new \Com\Tecnick\Barcode\Barcode();
      // generate a barcode
      $bobj = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -8,                             // bar width (use absolute or negative value as multiplication factor)
          -8,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -1)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color
      return view('home.marketing-qr',compact('bobj'));
    }

    public function setupGuides(){
      return view('guides.setup');
    }
}
