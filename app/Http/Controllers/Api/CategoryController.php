<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\Category;
use App\Menu;
use App\Extra;
use App\Cart;

class CategoryController extends Controller
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
  @method:-detail
  @description:-This method will detail of category
  */
  public function detail(Request $request,$id){
    if($id){
      $categoryCount=Category::where(['id'=>$id,'deleted'=>0])->count();
      if($categoryCount>0){
        $categoryDetail=Category::where(['id'=>$id,'deleted'=>0])->select(['id','name','image_path','thumbnail_path'])->first();
        if($categoryDetail){
          $categoryDetail->category_items=Menu::where('category_id',$id)->get();
          $categoryDetail->extras=(Extra::where('category_id',$id)->count()>0)?1:0;
          if($categoryDetail->category_items){
            foreach ($categoryDetail->category_items as $key => $item) {
              $categoryDetail->category_items[$key]->quantity_added=checkCartByItem($item->id);
              $cartInfo=getCartInfoForItem($item->id);
              $categoryDetail->category_items[$key]->cart_id=false;
              $categoryDetail->category_items[$key]->extras_string='';
              if(!$item->thumbnail_path){
                //$categoryDetail->category_items[$key]->thumbnail_path=url('/').'/img/no-item-images.png';
              }
              if($cartInfo){
                $categoryDetail->category_items[$key]->cart_id=$cartInfo->id;
                if($cartInfo->extras){
                  $categoryDetail->category_items[$key]->extras=$cartInfo->extras;
                  $categoryItemExtras=explode(",",$cartInfo->extras);
                  $categoryItemExtrasArr=array();
                  foreach ($categoryItemExtras as $exti) {
                    $categoryItemExtrasArr[]=Extra::find($exti)->name;
                  }
                  $categoryDetail->category_items[$key]->extras_string=implode(",",$categoryItemExtrasArr);
                }
              }

            }
          }
          $categoryDetail->image_path=url('/').'/'.$categoryDetail->image_path;
          $categoryDetail->thumbnail_path=url('/').'/'.$categoryDetail->thumbnail_path;

        }
        return response()->json(['code'=>200,'data'=>$categoryDetail]);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }

  /*
  @developer:jasmaninder
  @method:-searchByName
  @description:-This method will search item by name and category
  */
  public function searchByName(Request $request){
    $nameString=$request->query('name');
    $cid=$request->query('category_id');
    if($nameString && $cid){
      $itemCount=Menu::where('name', 'LIKE', "%{$nameString}%")->where('category_id',$cid)->count();
      if($itemCount>0){
        $itemDetail=Menu::where('name', 'LIKE', "%{$nameString}%")->where('category_id',$cid)->get();
        return response()->json(['code'=>200,'data'=>$itemDetail]);
      }else{
        return response()->json(['code'=>200,'data'=>[]]);
      }
    }else{
      return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
    }
  }
}
