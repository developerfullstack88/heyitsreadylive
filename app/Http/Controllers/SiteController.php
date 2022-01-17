<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;
use App\User;
use Session;
use Auth;
use Intervention\Image\Facades\Image;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(auth()->user()->role==MANAGER){
        $sites=Site::with(['manager'])
        ->where('manager_id',auth()->user()->id)->paginate(10);
      }else if(auth()->user()->role==SUPERVISOR){
        $sites=Site::with(['supervisor'])
        ->where('supervisor_id',auth()->user()->id)->paginate(10);
      }else{
        $sites=Site::with(['manager'])->where('company_id',auth()->user()->company_id)->paginate(10);
      }

      return view('sites.index',compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $siteCount=Site::where('company_id',auth()->user()->company_id)->count();
        $cityName= getaddress();
        return view('sites.create',compact('cityName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request){
         $validatedData = $request->validate([
           'name' => 'required',
           'address' => 'required',
           'category' => 'required',
           //'cover_image' => 'required|image|mimes:jpeg,png,jpg',
         ]);
         if ($request->isMethod('post')) {
           $postedData=$request->all();
           //echo "<pre>";print_r($postedData);die;
           /*upload cover full image and thumbnail image*/
           if(isset($postedData['cover_image'])) {
             $coverImageName = time().'.'.$postedData['cover_image']->extension();
             $thumbnailCoverImagePath='images/restaurant-images/thumbnail/';
             if(!file_exists($thumbnailCoverImagePath)){
               mkdir($thumbnailCoverImagePath,0775,true);
             }

             $fullPathThumbnailCoverImage=public_path($thumbnailCoverImagePath).'/'.$coverImageName;
             $image = $request->file('cover_image');
             Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnailCoverImage);
             $postedData['cover_image_thumbnail']=$thumbnailCoverImagePath.$coverImageName;

             $uploadCoverPath='images/restaurant-images/';
             $fullImageCoverPath=public_path($uploadCoverPath);
             $postedData['cover_image']->move($fullImageCoverPath, $coverImageName);
             $postedData['cover_image']=$uploadCoverPath.$coverImageName;
           }
           /*upload cover full image and thumbnail image*/
           $postedData['created_at']=date('Y-m-d h:i:s');
           //list($lat,$lng)=explode(",",getLatLng($postedData['address']));
           //$postedData['lat']=$lat;
           //$postedData['lng']=$lng;
           $postedData['company_id']=Auth::user()->company_id;
           try{
             Site::create($postedData);
             Session::flash('success', trans('site.site_add_success'));
           }catch (\Exception $e) {
             Session::flash('warning',$e->getMessage());
           }
           return redirect()->route('sites.index');
         }
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $site=Site::with(['manager'])->find($id);
        return view('sites.view',compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
      $site=Site::find($id);
      //$polygonWkt=[];
      /*if($site->polygon_wkt){
        $polygonWkt=trim(str_replace(array('POLYGON','(',')'),'',$site->polygon_wkt));
      }*/
      return view('sites.edit',compact('site'));
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
         $postedData=$request->all();
         unset($postedData['_token'],$postedData['_method']);
         $postedData['updated_at']=date('Y-m-d h:i:s');
         /*list($lat,$lng)=explode(",",getLatLng($postedData['address']));
         $postedData['lat']=$lat;
         $postedData['lng']=$lng;*/
         /*upload cover full image and thumbnail image*/

         /*assign supervisor*/
         if($postedData['manager_id']){
           $postedData['supervisor_id']=$postedData['manager_id'];
           unset($postedData['manager_id']);
         }
         /*assign supervisor*/

         if(isset($postedData['cover_image'])){
           $coverImageName = time().'.'.$postedData['cover_image']->extension();
           $thumbnailCoverImagePath='images/restaurant-images/thumbnail/';
           if(!file_exists($thumbnailCoverImagePath)){
             mkdir($thumbnailCoverImagePath,0775,true);
           }

           $fullPathThumbnailCoverImage=public_path($thumbnailCoverImagePath).'/'.$coverImageName;
           $image = $request->file('cover_image');
           Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnailCoverImage);
           $postedData['cover_image_thumbnail']=$thumbnailCoverImagePath.$coverImageName;

           $uploadCoverPath='images/restaurant-images/';
           $fullImageCoverPath=public_path($uploadCoverPath);
           $postedData['cover_image']->move($fullImageCoverPath, $coverImageName);
           $postedData['cover_image']=$uploadCoverPath.$coverImageName;
         }
         /*upload cover full image and thumbnail image*/
         Site::where('id',$id)->update($postedData);
         Session::flash('success', trans('site.site_edit_success'));
         return redirect()->route('sites.index');
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        Site::where('id',$id)->delete();
        Session::flash('success', 'Site has been deleted successfully');
        return redirect()->route('sites.index');
    }

    /*
    @developer:-jasmaninder
    @method:-setDefault
    @description:-This will set default location for logged user
    */
    public function setDefault($id){
      $userInfo=User::find(auth()->user()->id);
      $userInfo->location_id=$id;
      $userInfo->save();
      Session::flash('success', 'Default site has been set successfully');
      return redirect()->route('sites.index');
    }
}
