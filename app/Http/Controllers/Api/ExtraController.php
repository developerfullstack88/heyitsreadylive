<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\Extra;
use App\Menu;
use App\Cart;

class ExtraController extends Controller
{

  /**
   * Create a new ExtraController instance.
   *
   * @return void
   */
  public function __construct()
  {
      auth()->setDefaultDriver('api');
  }

  /*
  @developer:jasmaninder
  @method:-listing
  @description:-This method will get listing of extras for category
  */
  public function listing(Request $request){
    $item_id=$request->query('item_id');
    $count=$request->query('count');
    $type=$request->query('type');
    $categoryId=$request->query('category_id');
    $postedData=$request->all();
    $extraItemCount=0;
    if($item_id){
      $menuInfo=Menu::select('id','name','extras','extra_free')->find($item_id);
      $menuInfo->extras_items=array();
      if($menuInfo->extras && $type==0){
        $menuInfo->extras_items=getItemExtras($menuInfo->extras,$count,$categoryId,$item_id);
      }
      return response()->json(['code'=>200,'data'=>$menuInfo]);
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }
}
