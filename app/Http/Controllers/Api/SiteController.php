<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\Category;
use App\Site;
use App\Cart;
use App\Order;
use DB;

class SiteController extends Controller
{
    private $limit=6;
    /**
     * Create a new SiteController instance.
     *
     * @return void
     */
    public function __construct()
    {
        auth()->setDefaultDriver('api');
    }

    /*This method will get all location within distance*/
    public function nearByList(Request $request){
      if($request->all()){
        $postedData=$request->all();
        extract($request->all());
        if(auth()->user()->id!=$user_id){
          return response()->json(['code'=>400,'status'=>false,'message'=>'Unauthorized user id']);
        }else if($lat && $lng && $distance && $user_id){
          updateBackground($user_id,0);
          if(auth()->user() && !auth()->user()->device_token){
            if(!updateDeviceToken($user_id,$request->get('device_token'))) {
              return response()->json(['code'=>400,'status'=>false,'message'=>'Device token is not updated']);
            }
          }
          if(isset($postedData['ios_version'])) {
            if(!updateIosVersion($user_id,$postedData['ios_version'])) {
              return response()->json(['code'=>400,'status'=>false,'message'=>'Ios version is not updated']);
            }
          }
          if(isset($postedData['background_type'])) {
            if(!updateBackground($user_id,$postedData['background_type'])) {
              return response()->json(['code'=>400,'status'=>false,'message'=>'background is not updated']);
            }
          }
          /*$rows=\DB::select("SELECT id,cover_image_thumbnail,address as business_address,lat as business_lat,lng as business_lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
					sin(radians(lat))))
					AS distance
					FROM sites WHERE ST_Within(ST_GeomFromText('POINT($lng $lat)'),polygon)");*/
          $rows=\DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
          sin(radians(lat))))
          AS distance
          FROM sites HAVING distance<=$distance");
          if(count($rows)>0){
            foreach($rows as $k=>$site){
              //unset($rows[$k]->distance);
              $businessInfo=Company::find($site->company_id);
              $rows[$k]->business_name=$businessInfo->company_name;
              $rows[$k]->distance=$site->distance;
              $rows[$k]->cover_image=url('/').'/'.$site->cover_image_thumbnail;
              $rows[$k]->business_website=$businessInfo->company_website;
              $rows[$k]->business_phone=User::where(['company_id'=>$site->company_id,'role'=>'company'])->first()['phone_number'];
              unset($rows[$k]->company_id);
            }
          }
          $data=$rows;
          $newOrder=getLatestUserOrder($user_id);
          /*Count current orders*/
          $orderInfo=Order::where(['user_id'=>$user_id,'deleted'=>0,['status','<>','complete'],'cancel'=>0]);
          /*count current orders*/
          return response()->json(['code'=>200,'status'=>true,'data'=>$rows,
          'new_order'=>$newOrder,'current_order'=>$orderInfo->count()]);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*
    @developer:jasmaninder
    @method:-restaurantList
    @description:-This method will display all restaurant distance wise
    */
    public function restaurantList(Request $request){
      $lat=$request->query('lat');
      $lng=$request->query('long');
      $distance=$request->query('distance');
      $uid=$request->query('user_id');
      $offset=$request->query('offset');
      $cartCount=0;
      $nextpage=true;
      if($uid){
        $cartCount=Cart::where(['user_id'=>$uid,'payment_id'=>0])->count();
      }

      if($lat && $lng && $distance){
        /*pagination*/
        $sitesPages = \DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
        sin(radians(lat))))
        AS distance
        FROM sites HAVING distance<=$distance");
        $totalPages=count($sitesPages)/$this->limit;
        $totalPages=ceil($totalPages);
        if($offset+1>=$totalPages){
          $nextpage=false;
        }
        $offsetPage=$offset*$this->limit;
        /*pagination*/

        /*$sites = \DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
        sin(radians(lat))))
        AS distance
        FROM sites HAVING distance<=$distance LIMIT $this->limit OFFSET $offsetPage");*/
        $sites = \DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
        sin(radians(lat))))
        AS distance
        FROM sites HAVING distance<=$distance");
        if(count($sites)>0){
          foreach ($sites as $key=>$site) {
            $sites[$key]->restaurant_name=getBusinessNameById($site->company_id);
            $sites[$key]->name=getBusinessNameById($site->company_id);
            $companyInfo=getBusinessInfo($site->company_id);
            //$sites[$key]->estimated_delivery_time=$companyInfo->estimated_delivery_time;
            if($site->cover_image){
              $sites[$key]->cover_image=url('/').'/'.$site->cover_image;
              $sites[$key]->cover_image_thumbnail=url('/').'/'.$site->cover_image_thumbnail;
            }
          }
          return response()->json(['code'=>200,'status'=>true,'data'=>$sites,
          'total_items'=>$cartCount,'next_page'=>$nextpage,'offset'=>$offset]);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'No record found in DB']);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }

    /*
    @developer:jasmaninder
    @method:-restaurantDetail
    @description:-This method will show full detail of restaurant
    */
    public function restaurantCategories(Request $request,$id){
      if($id){
        $categories=Category::where(['company_id'=>$id,'deleted'=>0])->select(['id','name','thumbnail_path'])->get();
        return response()->json(['code'=>200,'status'=>true,'data'=>$categories]);
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Id is required field']);
      }
    }

    /*
    @developer:jasmaninder
    @method:-restaurantDetail
    @description:-This method will show full detail of restaurant
    */
    public function restaurantMenus(Request $request,$id){
      if($id){
        $categories=Category::where(['company_id'=>$id,'deleted'=>0])->select(['id','name','thumbnail_path'])->get();
        return response()->json(['code'=>200,'status'=>true,'data'=>$categories]);
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Id is required field']);
      }
    }

    /*
    @developer:jasmaninder
    @method:-restaurantSearchByName
    @description:-This method will display all which will match with search string
    */
    public function restaurantSearchByName(Request $request){
      $nameString=$request->query('name');
      $lat=$request->query('lat');
      $lng=$request->query('long');
      if($nameString){
        /*$sites=Site::where('name', 'LIKE', "%{$nameString}%")
        ->select('id','name','address','cover_image','cover_image_thumbnail','lat','lng','company_id')
        ->get();*/
        $sites = \DB::select("SELECT id,name,address,cover_image,cover_image_thumbnail,lat,lng,company_id,(3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) *
        sin(radians(lat))))
        AS distance
        FROM sites WHERE name LIKE '%$nameString%'");
        if(count($sites)>0){
          foreach ($sites as $key=>$site) {
            $sites[$key]->restaurant_name=getBusinessNameById($site->company_id);
            $sites[$key]->name=getBusinessNameById($site->company_id);
            $companyInfo=getBusinessInfo($site->company_id);
            //$sites[$key]->estimated_delivery_time=$companyInfo->estimated_delivery_time;
            if($site->cover_image){
              $sites[$key]->cover_image=url('/').'/'.$site->cover_image;
              $sites[$key]->cover_image_thumbnail=url('/').'/'.$site->cover_image_thumbnail;
            }
          }
          return response()->json(['code'=>200,'status'=>true,'data'=>$sites]);
        }else{
          return response()->json(['code'=>400,'status'=>false,'message'=>'No record found in DB']);
        }
      }else{
        return response()->json(['code'=>400,'status'=>false,'message'=>'Please enter required fields']);
      }
    }
}
