<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\TmpOrder;
use App\Order;
use App\QrItem;
use App\Cart;
use App\Site;
use App\Menu;
use App\Tax;
use App\Payment;
use Carbon\Carbon;
class OrderController extends Controller
{
    /**
     * Create a new OrderController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /*This method will add order information of user*/
    public function add(Request $request){
      if($request->all()){
        $postedData = $request->all();
        $postedData['status']='pending';
        $postedData['new_order']=1;
        $postedData['confirm']=1;
        $postedData['status']='confirm';
        if(auth()->user()->id!=$postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else if($orderInfo=Order::create($postedData)) {
          $webPushData['body']='Order Confirmed';
          sendWebPushNotification($orderInfo,$webPushData,array('type'=>'order_confirmed'));
          return response()->json(['code'=>200,'status'=>true,'message' => 'Order added successfully']);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in order add']);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*This method will update order info*/
    public function update(Request $request){
      if($request->all()){
        $postedData = $request->all();
        if(auth()->user()->id!=$postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else{
          $orderInfo=Order::find($postedData['order_id']);
          $webPushData['body']='Order Confirmed';
          sendWebPushNotification($orderInfo,$webPushData,array('type'=>'order_confirmed'));
          if($orderInfo){
            $orderInfo->confirm=1;
            $orderInfo->status='confirm';
            if($orderInfo->save()){
              return response()->json(['code'=>200,'status'=>true,'message' => 'Order confirmed successfully']);
            }else{
              return response()->json(['code'=>400,'status'=>true,'message' => 'There is some problem in updation']);
            }
          }
          else{
            return response()->json(['code'=>400,'status'=>true,'message' => 'Record not found for order id.']);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*This method will update order info*/
    public function cancel(Request $request){
      if($request->all()){
        $postedData = $request->all();
        if(auth()->user()->id!=$postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else{
          $orderInfo=Order::find($postedData['order_id']);
          if($orderInfo){
            $orderInfo->cancel=1;
            $orderInfo->confirm=1;
            if($orderInfo->save()){
              $webPushData['body']='Order Cancelled';
              sendWebPushNotification($orderInfo,$webPushData,array('type'=>'order_cancel'));
              return response()->json(['code'=>200,'status'=>true,'message' => 'Order cancel successfully']);
            }else{
              return response()->json(['code'=>400,'status'=>true,'message' => 'There is some problem in cancel']);
            }
          }else{
            return response()->json(['code'=>400,'status'=>true,'message' => 'Record not found for order id.']);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
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
              $cartDetailLatest=Cart::with(['item','company'])->where(['user_id'=>$uid,'payment_id'=>$paymentDetail->id])->latest()->first();
              $cartDetail=Cart::with(['item','company'])->where(['user_id'=>$uid,'payment_id'=>$cartDetailLatest->payment_id])->latest()->get();
              $cartItems=array();
              $cartData=array();
              $subTotal=0;
              $payableTax=0;
              if($cartDetail){
                foreach ($cartDetail as $cartInfo) {
                  $data['id']=$cartInfo->id;
                  $data['item_id']=$cartInfo->item_id;
                  $data['item_count']=$cartInfo->quantity;
                  $data['category_id']=$cartInfo->category_id;
                  $data['item_name']='';
                  $data['total_item_price']='';
                  $data['extra_price']='';
                  $data['item_price']='';
                  $data['image_path']='';
                  $data['thumbnail_path']='';
                  //$data['quantity']='';
                  if($cartInfo->has('item')){
                    $data['item_name']=$cartInfo->item->name;
                    $data['description']=$cartInfo->item->description;
                    $data['total_item_price']=number_format($cartInfo->item_price,2);
                    $data['extra_price']=number_format($cartInfo->extra_price,2);
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
                    $data['extras_string']=getExtrasStringWithQuantity($cartInfo->extras,$cartInfo->extras_quantity);
                    $menuInfo=Menu::select('id','extras')->find($cartInfo->item_id);
                    if($menuInfo->extras){
                      $data['extras']=$menuInfo->extras;
                    }
                    $data['is_extras']=getCartExtrasIds($cartInfo->extras);
                  }
                  /*extras of cart*/

                  $subTotal+=$cartInfo->total_price;
                  $cartItems[]=$data;
                }

                /*get payable tax*/
                $payableTaxDetail=getCompanyTax($cartDetail[0]->company_id);
                if($payableTaxDetail){
                  $payableTaxValue=$payableTaxDetail->tax_value;
                  $payableTax=$payableTaxValue/100*$subTotal;
                }
                /*get payable tax*/
                $totalPrice=$subTotal+$payableTax;
                $cartData['sub_total']=number_format($subTotal,2);
                $cartData['payable_tax']=number_format($payableTax,2);
                $cartData['total_price']=number_format($totalPrice,2);
                $datas['cartData']=$cartData;
                $datas['cartItems']=$cartItems;
                return $datas;
              }
            }
          }
        }
      }
    }

    /*This method will check user is locate in site or not*/
    private function checkUserIsLocate($lat,$lng,$site,$oid){
      if($lat && $lng && $site){
        if(isset($site[0])) {
          $siteId=$site[0]->id;
          $distance=75;
          /*$rows=\DB::select("SELECT id FROM sites WHERE ST_Within(ST_GeomFromText('POINT($lng $lat)'),polygon) AND id=".$siteId);*/
          $rows = \DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
          sin(radians(lat))))
          AS distance
          FROM sites WHERE id=$siteId HAVING distance<=$distance");
          if(count($rows)>0){
            Order::where(['id'=>$oid])->update(['locate'=>1]);
          }else{
            Order::where(['id'=>$oid])->update(['locate'=>0]);
          }
        }else{
          Order::where(['id'=>$oid])->update(['locate'=>0]);
        }
      }
    }

    /*This method will get user lastest order*/
    public function getUserOrder(Request $request){
      if($request->all()){
        $postedData = $request->all();
        if(auth()->user()->id!=$postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else{
          $orderInfo=Order::with(['company.site'])->where([['user_id',$postedData['user_id']],['status','!=','complete'],
        ['cancel',0],['eta','!=',null],['confirm','=',1],['deleted',0]])
          //->whereDate('created_at', '>=', Carbon::now())
          ->select('id','order_number','spot_number','user_id','company_id','eta','amount','status','confirm')
          ->orderBy('eta','asc');
          if($orderInfo->count()>0){
            $orderArr=array();
            foreach($orderInfo->get() as $order){
              /*geofence section*/
              $lat=$postedData['lat'];
              $lng=$postedData['lng'];
              if($order->status=='ready'){
                $this->checkUserIsLocate($lat,$lng,$order->company->site,$order->id);
              }
              /*geofence section*/

              $data['id']=$order->id;
              $data['amount']=number_format($order->amount,2);
              $data['order_number']=$order->order_number;
              $data['spot_number']=$order->spot_number;
              $data['user_id']=$order->user_id;
              $data['payable_tax']=0;
              $data['status']=ucfirst($order->status);
              $paymentQuery=Payment::where('order_id',$order->id);
              $data['payment_status']=$paymentQuery->count();
              $data['payment_type']='';
              if($paymentQuery->count()>0){
                $paymentInfo=$paymentQuery->first();
                $paymentPos = strpos($paymentInfo->transaction_id, 'cash');
                if($paymentPos===false){
                  $data['payment_type']='Credit Card';
                }else{
                  $data['payment_type']='Cash';
                }
              }
              if(auth()->user()->timezone && $order->status=='confirm'){
                $data['eta']=($order->eta)?convertToLocal12TimeOnly($order->eta,1):null;
                $data['eta_diff']=getTimeDifferenceMobile($order->eta);
              }else{
                $data['eta']=($order->eta)?convertToLocal12TimeOnly($order->eta,1):null;;
                $data['eta_diff']='';
              }

              $companyInfo=getBusinessInfo($order->company_id);
              $data['business_name']=$companyInfo->company_name;
              $data['business_address']=$companyInfo->address;
              $latLngInfo=getLatLng($companyInfo->address);
              if($latLngInfo){
                list($lat,$lng)=explode(",",$latLngInfo);
                $data['business_lat']=$lat;
                $data['business_lng']=$lng;
              }
              /*commented for second version
              $cartDetail=$this->cartDetail($postedData,$order->id);
              $data['cart_detail']=$cartDetail;*/
              $orderArr[]=$data;
            }
            return response()->json(['code'=>200,'status'=>true,
            'data'=>$orderArr]);
          }else{
            return response()->json(['code'=>200,'status'=>true,'data'=>[],'cart_detail'=>[]]);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*This method will get order number for company*/
    public function getOrderNumber(Request $request){
      if($request->all()){
        extract($request->all());
        $orderNumber=getRandomOrderNumber();
        if($orderNumber){
          $data['order_number']=$orderNumber;
          $data['business_name']=getBusinessNameById($company_id);
          return response()->json(['code'=>200,'status'=>true,'data'=>$data]);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*This method will all orders of users*/
    public function myOrders(Request $request,$type){
      if($request->all()){
        $postedData = $request->all();
        if(auth()->user()->id != $postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else{
          if($type==0){
            $orderInfo=Order::with(['company'])->where(['user_id'=>$postedData['user_id']])
            ->select('id','order_number','user_id','company_id','eta','amount','status','cancel','confirm',
            'actual_order_time','spot_number')
            ->where(['deleted'=>0])
            ->orderBy('created_at','desc');
          }else if($type==1){
            $orderInfo=Order::with(['company'])->where(['user_id'=>$postedData['user_id']])
            ->select('id','order_number','user_id','company_id','eta','amount','status','cancel','confirm',
            'actual_order_time','spot_number')
            ->where(['deleted'=>0,'status'=>'complete','cancel'=>0])
            ->orderBy('created_at','desc');
          }else{
            $orderInfo=Order::with(['company'])->where(['user_id'=>$postedData['user_id']])
            ->select('id','order_number','user_id','company_id','eta','amount','status','cancel','confirm',
            'actual_order_time','spot_number')
            ->where(['deleted'=>0,'cancel'=>1])
            ->orderBy('created_at','desc');
          }

          if($orderInfo->count()>0){
            $orderArr=array();
            $completedArr=array();
            $finalArr=array();
            $cancelArr=array();
            foreach($orderInfo->get() as $order){
              $data['id']=$order->id;
              $data['payable_tax']=0;
              $data['order_number']=$order->order_number;
              $data['spot_number']=$order->spot_number;
              $data['amount']=number_format($order->amount,2);
              $data['user_id']=$order->user_id;
              $data['eta']=$order->eta;
              $paymentQuery=Payment::where('order_id',$order->id);
              $data['payment_status']=$paymentQuery->count();
              $data['payment_type']='';
              if($paymentQuery->count()>0){
                $paymentInfo=$paymentQuery->first();
                $paymentPos = strpos($paymentInfo->transaction_id, 'cash');
                if($paymentPos===false){
                  $data['payment_type']='Credit Card';
                }else{
                  $data['payment_type']='Cash';
                }
              }
              $data['eta_diff']='';
              if(auth()->user()->timezone && $order->status=='confirm'){
                if($type>0){
                  $data['eta']=($order->eta)?convertToLocal($order->eta,1):null;
                }else{
                  $data['eta']=($order->eta)?convertToLocal12TimeOnly($order->eta,1):null;
                }
                $data['eta_diff']=getTimeDifferenceMobile($order->eta);
              }else if(auth()->user()->timezone && $order->status=='ready'){
                if($type>0){
                  $data['eta']=($order->eta)?convertToLocal($order->eta,1):null;
                }else{
                  $data['eta']=($order->eta)?convertToLocal12TimeOnly($order->eta,1):null;
                }
                $data['eta_diff']=getTimeDifferenceMobile($order->eta);
              }else if($order->eta){
                if($type>0){
                  $data['eta']=($order->eta)?convertToLocal($order->eta,1):null;
                }else{
                  $data['eta']=($order->eta)?convertToLocal12TimeOnly($order->eta,1):null;
                }
              }
              if($order->actual_order_time){
                $data['actual_order_time']=($order->actual_order_time)?convertToLocal($order->actual_order_time,1):null;
              }
              if($order->status=='confirm' && $order->confirm==1){
                $order->status='Confirmed';
              }else if($order->status=='confirm' && $order->confirm==0){
                $order->status='Pending';
              }
              $data['status']=ucfirst($order->status);
              $companyInfo=getBusinessInfo($order->company_id);
              $data['business_name']=$companyInfo->company_name;
              $data['business_address']=$companyInfo->address;
              $latLngInfo=getLatLng($companyInfo->address);
              if($latLngInfo){
                list($lat,$lng)=explode(",",$latLngInfo);
                $data['business_lat']=$lat;
                $data['business_lng']=$lng;
              }
              /*Commented for second version
              $cartDetail=$this->cartDetail($postedData,$order->id);
              $data['cart_detail']=$cartDetail;*/
              if($order->status=='complete' && $order->cancel==0 && $type==1){
                $completedArr[]=$data;
                $finalArr['completed']=$completedArr;
              }else if($order->cancel==1 && $type==2){
                $data['status']='Cancelled';
                $cancelArr[]=$data;
                $finalArr['cancel']=$cancelArr;
              }else if($type==0 && $order->status!='complete' && $order->cancel==0){
                $orderArr[]=$data;
                $finalArr['current']=$orderArr;
              }
            }
            return response()->json(['code'=>200,'status'=>true,'data'=>$finalArr]);
          }else{
            return response()->json(['code'=>200,'status'=>true,'data'=>[]]);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*delete particular order*/
    public function delete($id){
      if($id){
        $orderInfo=Order::find($id);
        if($orderInfo){
          $paymentInfo=Payment::where('order_id',$id)->first();
          $pid=$paymentInfo->id;
          Cart::where('payment_id',$pid)->delete();
          $paymentInfo->delete();
          $orderInfo->delete();
          return response()->json(['code'=>200,'status'=>true,'message'=>'Order deleted successfully']);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'Order info not found in DB']);
        }

      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Required field id is missing']);
      }
    }

    /*find order by order number*/
    public function findbyOrderNumber(Request $request){
      if($request->all()){
        $postedData = $request->all();
        if(auth()->user()->id != $postedData['user_id']){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else {
          $orderInfo=Order::where(['order_number'=>$postedData['order_number'],'cancel'=>0])->get();
          if($orderInfo->count()>0){
            $userInfo=getUserInfo($postedData['user_id']);
            $orderInfo=$orderInfo->first();
            if($orderInfo->eta){
              $orderInfo->eta=convertToLocal($orderInfo->eta,1);
            }
            if($userInfo->id!=$orderInfo->user_id){
              return response()->json(['code'=>400,'status'=>false,'message'=>'Your are not correct user.']);
            }else{
              return response()->json(['code'=>200,'status'=>true,'data'=>$orderInfo]);
            }
          }else{
            return response()->json(['code'=>400,'status'=>false,'message'=>'Record not found for this order number']);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*get menu images for company*/
    public function getMenuImages($id){
      if($id){
        $menuItems=QrItem::where('company_id',$id)->get();
        $routeUrl=url('/');
        return response()->json(['code'=>200,'status'=>true,'data'=>$menuItems,'route_url'=>$routeUrl]);
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Required field id is missing']);
      }
    }

    /*get latest user order*/
    public function getLatestOrder($uid){
      if($uid){
        $newOrder=getLatestUserOrder($uid);
        return response()->json(['code'=>200,'status'=>true,'data'=>$newOrder]);
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Required field user id is missing']);
      }

    }

    /*add spot number*/
    public function addSpotNumber(Request $request,$oid){
      $postedData=$request->all();
      if($oid && $postedData){
        $orderInfo=Order::find($oid);
        $orderSpotCount=Order::where(['spot_number'=>$postedData['spot_number'],'company_id'=>$orderInfo->company_id])->count();
        if($orderSpotCount>0){
          return response()->json(['code'=>400,'message'=>'Spot number already used in other order']);
        }else{
          if($orderInfo){
            $orderInfo->spot_number=$postedData['spot_number'];
            if($orderInfo->save()){
              return response()->json(['code'=>200,'message'=>'Spot number has been set successfully']);
            }else{
              return response()->json(['code'=>200,'message'=>'There is some problem in settings spot number']);
            }
          }else{
            return response()->json(['code'=>400,'message'=>'Order info not found in DB']);
          }
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Required field order id is missing']);
      }
    }
}
