<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Payment;
use Auth;
use Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
  public $stripe;
  public function __construct()
  {
    /*block access of full software*/
    $this->middleware(function ($request, $next) {
      $currentRoute=\Route::currentRouteName();
      if(checkFreeSoftwareExpire() && checkSubscriptionExpire()) {
        Session::flash("warning", "If you want to use Hey It's Ready full version, please go to “Settings” and select “Enable”");
        return redirect()->route('itemQr',0);
      }else{
        return $next($request);
      }
    });
    /*block access of full software*/

    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perpage=10;
      $perpageQueryString=$request->get('per_page');
      if($perpageQueryString){
        $perpage=$request->get('per_page');
      }

      $orders=Order::with(['user'])
      ->where(['company_id'=>Auth::user()->company_id,'status'=>'complete'])
      ->orderBy('created_at','desc')->paginate($perpage);
        return view('reports.basic-reporting',compact('orders','perpageQueryString'));
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
        $date='2020-01-01';
        $conditions=[['company_id',Auth::user()->company_id],['status','complete']];
          $postedData=$request->all();
          if(isset($postedData['date']) && $postedData['date']){
            $date=Timezone::convertFromLocal($postedData['date']);
          }
          if($postedData['date'] && $postedData['time_from']){
            $timeFrom=$postedData['date'] .' '.$postedData['time_from'];
            $timeFrom=Timezone::convertFromLocal($timeFrom);
            $conditions[]=['created_at','>=',$timeFrom];
          }
          if($postedData['date'] && $postedData['time_to']){
            $timeTo=$postedData['date'] .' '.$postedData['time_to'];
            $timeTo=Timezone::convertFromLocal($timeTo);
            $conditions[]=['created_at','<=',$timeTo];
          }

          $perpage=10;
          $perpageQueryString=$request->get('per_page');
          if($perpageQueryString){
            $perpage=$request->get('per_page');
          }

          $orders=Order::with(['user'])
          ->whereDate('created_at', '>=', $date)
          ->where($conditions)->orderBy('created_at','desc')->paginate($perpage);
          return view('reports.basic-reporting',compact('orders','postedData','perpageQueryString'));

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /*This will return all payouts associated
    with logged business user*/
    public function payouts(){
      $stripeAccessToken=auth()->user()->stripe_access_token;
      $payments=array();
      if($stripeAccessToken){
        $stripe = new \Stripe\StripeClient($stripeAccessToken);
        $payments=$stripe->charges->all(['limit' => 100000]);
      }
      return view('reports.payouts',compact('payments'));
    }

    /*This will return all payouts associated
    with logged business user*/
    public function payments(){
      $payments=Payment::whereHas('order', function($query) {
        $query->where('company_id', auth()->user()->company_id);
      })->where([['amount','>',0]])->orderBy('id', 'DESC')->get();
      $paymentsArr=array();
      if($payments->count()>0){
        foreach ($payments as $key => $value) {
          $value->transaction_detail='';
          $value->payment_type='Cash';
          if(strpos($value->transaction_id, 'cash')===false){
            $value->transaction_detail=$this->stripe->balanceTransactions->retrieve($value->transaction_id);
            $value->payment_type='Card';
            $stripeDetail=$this->stripe->charges->retrieve($value->transaction_detail->source);
            $value->card_number=$stripeDetail->payment_method_details->card->last4;
          }
          $paymentsArr[]=$value;
        }
      }
      $payments=$paymentsArr;
      return view('reports.payments',compact('payments'));
    }
}
