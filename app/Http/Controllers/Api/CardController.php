<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\Card;

class CardController extends Controller
{

  /**
   * Create a new SiteController instance.
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
  @method:-add
  @description:-This method will add card into stripe and DB.
  */
  public function add(Request $request){
    if($request->all()){
      $postedData = $request->all();
      $cardCount=Card::where('user_id',$postedData['user_id'])->count();
      if($cardCount==0){
        $postedData['is_default']=1;
      }
        $customerStripe = \Stripe\Customer::create([
            'email' => auth()->user()->email,
            'source' => $postedData['card_token'],
        ]);
        if($customerStripe->id){
          $postedData['customer_id']=$customerStripe->id;
        }

      if($cardInfo=Card::create($postedData)) {
        return response()->json(['code'=>200,'message' => 'Card has been successfully added']);
      }else{
        return response()->json(['code'=>400,'message' => 'There is some problem in card adding']);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-listing
  @description:-This method will get card listing
  */
  public function listing($uid){
    if($uid){
      $cards=Card::where('user_id',$uid)
      ->select('id','card_id','customer_id','is_default')
      ->get();
      if($cards){
        foreach($cards as $key=>$card){
          $cardInfo=getCardInfo($card->customer_id,$card->card_id);
          $cards[$key]->card_number=$cardInfo->last4;
          $cards[$key]->card_type=$cardInfo->brand;
          $cards[$key]->exp_month=$cardInfo->exp_month;
          $cards[$key]->exp_year=$cardInfo->exp_year;
        }
      }
      return response()->json(['code'=>200,'data'=>$cards]);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-delete
  @description:-This method will delete card from DB and stripe
  */
  public function delete($id){
    if($id){
      $cardInfo=Card::find($id);
      if($cardInfo){
        $customerDetail=\Stripe\Customer::retrieve($cardInfo->customer_id);
        $customerCard=\Stripe\Customer::deleteSource($cardInfo->customer_id,$cardInfo->card_id,[]);
        if($customerCard->deleted==true){
          $cardInfo->delete();
          return response()->json(['code'=>200,'message'=>'Card has been deleted successfully']);
        }else{
          return response()->json(['code'=>400,'message'=>'There is some problem in card deletion']);
        }
      }else{
        return response()->json(['code'=>400,'message'=>'Card info not found in DB']);
      }
    }else{
      return response()->json(['code'=>400,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-setDefault
  @description:-This method will set default card for user.
  */
  public function setDefault($id){
    if($id){
      $cardInfo=Card::find($id);
      if($cardInfo){
        $cardInfo->is_default=1;
        if($cardInfo->save()){
          $postedData['is_default']=0;
          Card::where('user_id',auth()->user()->id)
          ->where('id', '!=' , $id)
          ->update($postedData);
          return response()->json(['code'=>200,'message'=>'Default card has been set successfully']);
        }else{
          return response()->json(['code'=>400,'message'=>'There is some problem in card deletion']);
        }
      }else{
        return response()->json(['code'=>400,'message'=>'Card info not found in DB']);
      }
    }else{
      return response()->json(['code'=>400,'message'=>'Please enter required fields']);
    }
  }
}
