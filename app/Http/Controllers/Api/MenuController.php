<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use DB;
use App\RestaurantMenu;
use App\Category;

class MenuController extends Controller
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
  @method:-listing
  @description:-This method will display all restaurant menu listing
  */
  public function listing(Request $request){
    $type=$request->query('type');
    $cid=$request->query('cid');
    $currentTime=currentLocalTime24();
    if($type==0){
      $menus=RestaurantMenu::where('company_id',$cid)->get();
    }else if($type==1){
      $menus=RestaurantMenu::
      where('company_id',$cid)
      ->where('name', 'LIKE', "%breakfast%")->get();
    }else if($type==2){
      $menus=RestaurantMenu::
      where('company_id',$cid)
      ->where('name', 'LIKE', "%lunch%")->get();
    }else if($type==3){
      $menus=RestaurantMenu::
      where('company_id',$cid)
      ->where('name', 'LIKE', "%dinner%")->get();
    }
    if($type==0){
      $previousArray=array();
      $currentArray=array();
      if($menus->count()>0){
        foreach ($menus as $key=>$menu) {
          $startTime=date('H:i',strtotime($menu->start_time));
          $endTime=date('H:i',strtotime($menu->end_time));
          $menu->available=1;
          if($endTime<$currentTime){
            $menu->available=0;
          }
          $menu->start_time=date('h:i A',strtotime($menu->start_time));
          $menu->end_time=date('h:i A',strtotime($menu->end_time));
          $data=$menu;
          $data->categories=Category::where('menu_id',$menu->id)->get();
          if($startTime<$currentTime && $endTime<$currentTime){
            $previousArray[]=$data;
          }else{
            $currentArray[]=$data;
          }
        }
        $menus=array_merge($currentArray,$previousArray);
      }
    }else{
      if($menus->count()>0){
        foreach ($menus as $key=>$menu) {
          $startTime=date('H:i',strtotime($menu->start_time));
          $endTime=date('H:i',strtotime($menu->end_time));
          $menus[$key]->available=1;
          if($endTime<$currentTime){
            $menus[$key]->available=0;
          }
          $menus[$key]->start_time=date('h:i A',strtotime($menu->start_time));
          $menus[$key]->end_time=date('h:i A',strtotime($menu->end_time));
          $menus[$key]->categories=Category::where('menu_id',$menu->id)->get();
        }
      }
    }

    return response()->json(['code'=>200,'data'=>$menus]);
  }
}
