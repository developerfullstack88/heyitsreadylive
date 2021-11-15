<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\Payment;
use App\Cart;
use App\Order;
use App\Menu;
use App\Mail\PaymentSuccess;
use Mail;
use Timezone;
use Carbon\Carbon;

class PaymentController extends Controller
{

  /**
   * Create a new PaymentController instance.
   *
   * @return void
   */
  public function __construct()
  {
      auth()->setDefaultDriver('api');
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
  }

  /*
  @developer:jasmaninder
  @method:-doPayment
  @description:-Do stripe payment
  */
  private function doPayment($request_data){
    if($request_data['user_id']){
      $totalAmount=$request_data['total_price'];
      $cartDetail=$this->cartDetailWithoutPayment($request_data);
      $cartDetail['cart_items']=[];
      $cartDetail['datas']=[];
      if($request_data['customer_id'] && $request_data['card_id']){
        $orderInfo=Order::find($request_data['order_id']);
        $companyUserInfo=getBusinessUserInfo($orderInfo->company_id);
        $countryCode=getCountryCode($companyUserInfo->country);
        $currencyCode=getCurrencyCode($countryCode);
        if($currencyCode){
          try {
            /*$charge=\Stripe\Charge::create(array(
              "amount" => $totalAmount*100,
              "currency" => "usd",
              "customer" => $request_data['customer_id'],
              "source" => $request_data['card_id']
            ));*/
            $order=randomPassword();
            $charge = \Stripe\Charge::create([
              "amount" => $totalAmount*100,
              "currency" => $currencyCode,
              "customer" => $request_data['customer_id'],
              "source" => $request_data['card_id'],
              "expand" => array("balance_transaction"),
              'transfer_group' => $order,
            ]);


            if($orderInfo){
              if($companyUserInfo->connect_account_id){
                $transfer = \Stripe\Transfer::create([
                'amount' => $charge->balance_transaction->net,
                'currency' => $charge->balance_transaction->currency,
                'source_transaction'=>$charge->id,
                'destination' => $companyUserInfo->connect_account_id,
                ]);
                //echo "<pre>";print_r($transfer);die;
              }
            }
          }catch(\Stripe\Exception\CardException $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          }catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['code'=>804,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          } catch (\Stripe\Exception\AuthenticationException $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          } catch (Exception $e) {
            return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
            'data'=>$cartDetail['datas'],'message'=>$e->getError()->message]);
          }

          if($charge->id){
            return $charge->balance_transaction->id;
          }else{
            return false;
          }
        }else{
          return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
          'data'=>$cartDetail['datas'],'message'=>'Currency code is missing']);
        }
      }else{
        return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
        'data'=>$cartDetail['datas'],'message'=>'Card id and customer id is required field']);
      }

    }else{
      return response()->json(['code'=>400,'cart_items'=>$cartDetail['cart_items'],
      'data'=>$cartDetail['data'],'message'=>'card_id and user_id can not empty']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-generateOrderForPayment
  @description:-This method will generate order info for payment
  */
  private function generateOrderForPayment($paymentId,$orderDate){
    $cartInfo=Cart::where('payment_id',$paymentId)->first();
    $orderInfoArr=array();
    if($cartInfo){
      $orderInfoArr['company_id']=$cartInfo->company_id;
      $orderInfoArr['user_id']=$cartInfo->user_id;
      $orderInfoArr['order_number']=getRandomOrderNumber($cartInfo->company_id);
      $orderInfoArr['new_order']=1;
      $orderInfoArr['confirm']=1;
      $orderInfoArr['status']='pending';
      if($orderDate){
        $orderInfoArr['eta']=Timezone::convertFromLocal($orderDate);
        $orderInfoArr['status']='confirm';
      }
      $orderInfo=Order::create($orderInfoArr);
      if($orderInfo->id>0){
        Payment::where(['id'=>$paymentId])->update(['order_id'=>$orderInfo->id]);
        return $orderInfo;
      }
    }

  }

  /*
  @developer:jasmaninder
  @method:-cartDetail
  @description:-This method will get detail of cart
  */
  private function cartDetail($postedData,$oid){
    if($postedData){
      $uid=$postedData['user_id'];
      $paymentCount=Payment::where(['order_id'=>$oid,'user_id'=>$uid])->count();
      if($paymentCount>0){
        $paymentDetail=Payment::where(['order_id'=>$oid,'user_id'=>$uid])->first();
        if($paymentDetail){
          $cartCount=Cart::where(['user_id'=>$uid,['payment_id','>',0]])->count();
          if($cartCount>0){
            $cartDetailLatest=Cart::with(['item','company'])->where(['user_id'=>$uid,['payment_id','>',0]])->latest()->first();
            $cartDetail=Cart::with(['item','company'])->where(['user_id'=>$uid,'payment_id'=>$cartDetailLatest->payment_id])->latest()->get();
            $cartItems=array();

            if($cartDetail){
              foreach ($cartDetail as $cartInfo) {
                $data['id']=$cartInfo->id;
                $data['item_id']=$cartInfo->item_id;
                $data['item_count']=$cartInfo->quantity;
                $data['category_id']=$cartInfo->category_id;
                $data['item_name']='';
                $data['total_item_price']='';
                $data['item_price']='';
                $data['image_path']='';
                $data['thumbnail_path']='';
                //$data['quantity']='';
                if($cartInfo->has('item')){
                  $data['item_name']=$cartInfo->item->name;
                  $data['description']=$cartInfo->item->description;
                  $data['total_item_price']=number_format($cartInfo->total_price,2);
                  $data['item_price']=number_format($cartInfo->item->amount,2);
                  if(!$cartInfo->item->thumbnail_path){
                    //$data['thumbnail_path']=url('/').'/img/no-item-images.png';
                  }else{
                    $data['image_path']=url('/').'/'.$cartInfo->item->image_path;
                    $data['thumbnail_path']=url('/').'/'.$cartInfo->item->thumbnail_path;
                  }
                  //$data['quantity']=$cartInfo->item->quantity;
                  $data['quantity_added']=checkCartByItemPayment($cartInfo->item->id,$cartDetailLatest->payment_id);
                }

                /*extras of cart*/
                $data['extras_string']='';
                $data['is_extras']='';
                $data['extras']=null;
                if($cartInfo->extras){
                  $data['extras_string']=getCartExtrasString($cartInfo->extras);
                  $menuInfo=Menu::select('id','extras')->find($cartInfo->item_id);
                  if($menuInfo->extras){
                    $data['extras']=$menuInfo->extras;
                  }
                  $data['is_extras']=getCartExtrasIds($cartInfo->extras);
                }
                $cartItems[]=$data;
              }
              return $cartItems;
            }
          }
        }
      }
    }
  }

  /*
  @developer:jasmaninder
  @method:-cartDetailWithoutPayment
  @description:-This method will get detail of cart without payment
  */
  private function cartDetailWithoutPayment($postedData){
    if($postedData){
      $uid=$postedData['user_id'];
      $cartCount=Cart::where(['user_id'=>$uid,'payment_id'=>0])->count();
      if($cartCount>0){
        $cartDetail=Cart::with(['item','company'])->where(['user_id'=>$uid,'payment_id'=>0])->get();
        $cartItems=array();
        $returnArr=array();
        $datas=array();
        if($cartDetail){
          $datas['payable_tax']=number_format($postedData['payable_tax'],2);
          $datas['amount']=number_format($postedData['total_price'],2);

          foreach ($cartDetail as $cartInfo) {
            $data['id']=$cartInfo->id;
            $data['item_id']=$cartInfo->item_id;
            $data['item_count']=$cartInfo->quantity;
            $data['category_id']=$cartInfo->category_id;
            $data['item_name']='';
            $data['total_item_price']='';
            $data['item_price']='';
            $data['image_path']='';
            $data['thumbnail_path']='';
            $data['quantity']='';
            if($cartInfo->has('item')){
              $data['item_name']=$cartInfo->item->name;
              $data['description']=$cartInfo->item->description;
              $data['total_item_price']=number_format($cartInfo->total_price,2);
              $data['item_price']=number_format($cartInfo->item->amount,2);
              if(!$cartInfo->item->thumbnail_path){
                //$data['thumbnail_path']=url('/').'/img/no-item-images.png';
              }else{
                $data['image_path']=url('/').'/'.$cartInfo->item->image_path;
                $data['thumbnail_path']=url('/').'/'.$cartInfo->item->thumbnail_path;
              }
              $data['quantity']=$cartInfo->item->quantity;
              $data['quantity_added']=checkCartByItem($cartInfo->item->id);
            }

            /*extras of cart*/
            $data['extras_string']='';
            $data['is_extras']='';
            $data['extras']=null;
            if($cartInfo->extras){
              $data['extras_string']=getCartExtrasString($cartInfo->extras);
              $menuInfo=Menu::select('id','extras')->find($cartInfo->item_id);
              if($menuInfo->extras){
                $data['extras']=$menuInfo->extras;
              }
              $data['is_extras']=getCartExtrasIds($cartInfo->extras);
            }
            $cartItems[]=$data;
          }
          $returnArr['datas']=$datas;
          $returnArr['cart_items']=$cartItems;
          return $returnArr;
        }
      }
    }
  }

  /*
  @developer:jasmaninder
  @method:-save
  @description:-Save stripe payment into DB
  */
  public function save(Request $request){
    if($request->all()){
      $postedData = $request->all();
      $transaction_id=$this->doPayment($postedData);
      if(is_string($transaction_id)) {
        unset($postedData['token']);
        $postedTotalPrice=$postedData['total_price'];
        if($transaction_id){
          $postedData['transaction_id']=$transaction_id;
          $postedData['amount']=$postedData['total_price'];
          $payableTax=$postedData['payable_tax'];
          unset($postedData['customer_id'],$postedData['card_id'],$postedData['total_price'],$postedData['payable_tax']);
          $paymentInserted=Payment::create($postedData);
          if($paymentInserted->id>0){
            $emailData=Payment::with(['user','order.company'])->find($paymentInserted->id);
            if($emailData->has('user') && $emailData->user->email){
              Mail::to($emailData->user->email)->send(new PaymentSuccess($emailData));
            }

            //Cart::where(['user_id'=>$postedData['user_id'],'payment_id'=>0])->update(['payment_id'=>$paymentInserted->id]);
            //$paymentOrderInfo=$this->generateOrderForPayment($paymentInserted->id,$paymentInserted->order_date);
            //$cartInfo=Cart::where('payment_id',$paymentInserted->id)->first();

            $paymentOrderInfo=Order::with(['company'])->find($postedData['order_id']);
            $paymentInserted->order_number=$paymentOrderInfo->order_number;
            $paymentInserted->restaurant_name=getBusinessNameById($paymentOrderInfo->company_id);
            //$cartDetail=$this->cartDetail($postedData,$paymentOrderInfo->id);
            /*get payable tax*/
            /*$payableTax=0;
            $payableTaxDetail=getCompanyTax($cartInfo->company_id);
            if($payableTaxDetail){
              $payableTaxValue=$payableTaxDetail->tax_value;
              $payableTax=$payableTaxValue/100*$postedTotalPrice;
            }*/
            $paymentInserted['payable_tax']=number_format($payableTax,2);
            /*get payable tax*/
            return response()->json(['code'=>200,'data'=>$paymentInserted,
            'message'=>'Payment has been done successfully']);
          }else{
            return response()->json(['code'=>400,'message'=>'There is some problem in adding']);
          }
        }
      }else{
        return $transaction_id;
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }
}
