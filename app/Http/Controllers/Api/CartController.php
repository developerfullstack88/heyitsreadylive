<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\Cart;
use App\Card;
use App\Site;
use App\Tax;
use App\Menu;
use App\Extra;
use App\RestaurantMenu;
class CartController extends Controller
{

  /**
   * Create a new SiteController instance.
   *
   * @return void
   */
  public function __construct()
  {
      auth()->setDefaultDriver('api');
  }

  /*
  @developer:jasmaninder
  @method:-add
  @description:-This method will add items to cart
  */
  public function add(Request $request){
    if($request->all()){
      $postedData = $request->all();
      if(OrderInProgress($postedData['user_id'])){
        if(cartAllowed($postedData['company_id'])){
          /*Quantity code version 2.5
          $allowedQuantity=checkQuantityAllowed($postedData['item_id']);
          if($postedData['quantity']>$allowedQuantity){
            return response()->json(['code'=>400,'status'=>true,'message' => 'quantity value is greater']);
          }else{*/
            $postedData['restaurant_menu_id']=getRestaurantMenuId($postedData['category_id']);
            if(MenuAllowedForCart($postedData['restaurant_menu_id'])){
              $postedData['extras_quantity']='';
              $postedData['extras_index']='';
              if($postedData['extras']){
                $postedData['extras']=explode(",",trim($postedData['extras']));
                $extrasArr=array();
                $quantityArr=array();
                foreach($postedData['extras'] as $ext) {
                  $extArr=explode('-',$ext);
                  $extrasArr[]=trim($extArr[0]);
                  $quantityArr[]=$extArr[1];
                  $extrasIndexArr[]=$extArr[2];
                }
                $postedData['extras']=implode(",",$extrasArr);
                $postedData['extras_quantity']=implode(",",$quantityArr);
                $postedData['extras_index']=implode(",",$extrasIndexArr);
              }
              if($cartInfo=Cart::create($postedData)) {
                $uid=$postedData['user_id'];
                $cartCount=0;
                if($uid){
                  $cartCount=Cart::where('user_id',$uid)->count();
                }

                return response()->json(['code'=>200,'status'=>true,'total_items'=>$cartCount,'message' => 'Items added to cart successfully']);
              }else{
                return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in cart add']);
              }
            }else{
              $cartDetail=Cart::with(['restaurant_menu'])->where(['user_id'=>auth()->user()->id,'payment_id'=>0])->first();
              return response()->json(['code'=>400,'status'=>false,'message'=>'You have already added the items in cart from '.$cartDetail->restaurant_menu->name.'. You will have to clear the cart before adding the new items from this Menu.Do you want to clear?']);
            }
          //}
        }else{
          $cartDetail=Cart::with(['company'])->where(['user_id'=>auth()->user()->id,'payment_id'=>0])->first();
          return response()->json(['code'=>400,'status'=>false,'message'=>'You have already added the items in cart from '.$cartDetail->company->company_name.'. You will have to clear the cart before adding the new items from this Menu.Do you want to clear?']);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'You cannot add the items in the cart.Because your previous order is not completed yet']);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-update
  @description:-This method will update items to cart
  */
  public function update(Request $request,$id){
    if($request->all()){
      $postedData = $request->all();
      /*Quantity code version 2.5
      $allowedQuantity=checkQuantityAllowed($postedData['item_id']);
      if($postedData['quantity']>$allowedQuantity){
        return response()->json(['code'=>400,'status'=>true,'message' => 'quantity value is greater']);
      }else{*/
        $postedData['extras_quantity']='';
        $postedData['extras_index']='';
        if($postedData['extras']){
          $postedData['extras']=explode(",",trim($postedData['extras']));
          $extrasArr=array();
          $quantityArr=array();
          foreach($postedData['extras'] as $ext) {
            $extArr=explode('-',$ext);
            $extrasArr[]=trim($extArr[0]);
            $quantityArr[]=$extArr[1];
            $extrasIndexArr[]=$extArr[2];
          }
          $postedData['extras']=implode(",",$extrasArr);
          $postedData['extras_quantity']=implode(",",$quantityArr);
          $postedData['extras_index']=implode(",",$extrasIndexArr);
        }
        if($cartInfo=Cart::where('id',$id)->update($postedData)) {
          $uid=$postedData['user_id'];
          $cartCount=0;

          if($uid){
            $cartCount=Cart::where('user_id',$uid)->count();
          }
          return response()->json(['code'=>200,'status'=>true,'total_items'=>$cartCount,'message' => 'Items updated to cart successfully']);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'There is some problem in cart add']);
        }
      //}
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-add
  @description:-This method will get detail of cart
  */
  public function detail(Request $request,$uid){
    if($uid){
      $cartCount=Cart::where(['user_id'=>$uid,'payment_id'=>0])->count();
      if($cartCount>0){
        $cartDetail=Cart::with(['item','company'])->where(['user_id'=>$uid,'payment_id'=>0])->get();
        $cartItems=array();
        $cartData=array();
        $cardDetailData=array();

        /* card detail for default*/
        $cardDetail=Card::select('card_id','customer_id','is_default')->where(['user_id'=>$uid,'is_default'=>1]);
        if($cardDetail->count()>0){
          $cardDetailData=$cardDetail->first();
        }
        /* card detail for default*/

        $subTotal=0;
        $payableTax=0;
        if($cartDetail){
          foreach ($cartDetail as $cartInfo) {
            $cartData['company_name']='';
            $cartData['address']='';
            $cartData['menu_start_time']='';
            $cartData['menu_end_time']='';
            $cartData['company_id']=$cartInfo->company_id;
            if($cartInfo->has('company')){
              $cartData['company_name']=$cartInfo->company->company_name;
              $cartData['address']=$cartInfo->company->address;

              /*$siteInfoCount=Site::where('company_id',$cartInfo->company_id)->count();
              if($siteInfoCount>0){
                $siteInfo=Site::where('company_id',$cartInfo->company_id)->first();
                $cartData['address']=$siteInfo->address;
              }*/
            }

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
            }

            /*extras of cart*/
            $data['extras_string']='';
            $data['is_extras']='';
            $data['extras']=null;
            $menuInfo=Menu::select('id','extras')->find($cartInfo->item_id);
            if($menuInfo->extras){
              $data['extras']=$menuInfo->extras;
            }
            if($cartInfo->extras){
              $data['extras_string']=getCartExtrasString($cartInfo->extras);
              $data['is_extras']=getCartExtrasIds($cartInfo->extras);
            }
            /*extras of cart*/

            $subTotal+=$cartInfo->total_price;
            $cartItems[]=$data;

            $menuInfo=RestaurantMenu::find($cartInfo->restaurant_menu_id);
            $cartData['menu_start_time']=date('H:i',strtotime($menuInfo->start_time));
            $cartData['menu_end_time']=date('H:i',strtotime($menuInfo->end_time));
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
          return response()->json(['code'=>200,'data'=>$cartData,
          'cart_items'=>$cartItems,'card_detail'=>$cardDetailData]);
        }else{
          return response()->json(['code'=>200,'data'=>[]]);
        }
      }else{
        return response()->json(['code'=>200,'data'=>[]]);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please send required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-delete
  @description:-This method will delete item from cart
  */
  public function delete($id){
    if($id){
      $cartInfo=Cart::find($id);
      $cartInfo->delete();
      return response()->json(['code'=>200,'status'=>true,'message'=>'Cart item deleted successfully']);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Required field id is missing']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-clearReastaurantItems
  @description:-This method will delete previous restaurant items from cart
  */
  public function clearRestaurantItems($uid){
    if($uid){
      $cartInfo=Cart::where(['user_id'=>$uid,'payment_id'=>0])->delete();
      return response()->json(['code'=>200,'status'=>true,'message'=>'Cart item cleared successfully']);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Required field id is missing']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-calculation
  @description:-This method will do a calculation for cart item
  */
  public function calculation(Request $request,$uid,$itemId,$freeItems,$quantity){
    if($request->all()){
      $postedData = $request->all();

      $itemQuantity=count($postedData);
      $itemPrice=menu::find($itemId)->amount;
      $itemTotalPrice=$itemPrice*$quantity;
      $extraPrice=0;
      for($i=0;$i<1;$i++){
        if($postedData[$i]['extras']){
          $postedData[$i]['extras']=explode(",",trim($postedData[$i]['extras']));
          $totalFreeItems=$freeItems;
          foreach ($postedData[$i]['extras'] as $key=>$ext) {
            list($extraId,$quantity)=explode("-",$ext);
            $extraInfo=Extra::find($extraId);
            if($extraInfo->is_free==1 && $totalFreeItems>0){
              $totalFreeItems=$totalFreeItems-$quantity;
              if($totalFreeItems<0){
                $totalFreeItemsPending=abs($totalFreeItems);
                $extraPrice+=$extraInfo->price*$totalFreeItemsPending;
                $totalFreeItems=0;
              }
            }else{
              $extraPrice+=$extraInfo->price*$quantity;
            }
          }
        }
      }
      $itemTotalAmount=$itemTotalPrice;
      $itemTotalPrice=$itemTotalPrice+$extraPrice;
      return response()->json(['code'=>200,
      'total_amount'=>$itemTotalPrice,'extra_price'=>$extraPrice,'item_price'=>$itemTotalAmount]);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please send required fields']);
    }
  }
}
