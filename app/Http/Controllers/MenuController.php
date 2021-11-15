<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Extra;
use App\Category;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category_id)
    {
      $menus=Menu::with(['category'])->where(['company_id'=>auth()->user()->company_id,'deleted'=>0,'category_id'=>$category_id])->paginate(10);
      $categoryDetail=Category::select('id','name')->find($category_id);
      return view('menus.index',compact('menus','categoryDetail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($category_id)
    {
      $categoryDetail=Category::select('id','name')->find($category_id);
      $extras=Extra::where('category_id',$category_id)->get();
      return view('menus.create',compact('categoryDetail','extras'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if($request->isMethod('post')){
        $request->validate([
          'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
          'name'=>'required',
          'category_id'=>'required'
        ]);
        $postedData=$request->all();
        if($postedData){
          /*Add data to menus table*/
          $menu = new Menu;
          $menu->name=$postedData['name'];
          $menu->description=$postedData['description'];
          $menu->category_id=$postedData['category_id'];
          $menu->amount=($postedData['amount'])??0;
          /*Quantity code version 2.5
          $menu->quantity=$postedData['quantity'];
          */
          if(isset($postedData['extras'])) {
            $menu->extras=$postedData['extras'];
          }
          $menu->extra_free=$postedData['extra_free'];
          $menu->company_id=Auth::user()->company_id;
          if(isset($postedData['image'])) {
            $imageName = time().'.'.$postedData['image']->extension();
            $uploadThumbnailPath='images/menu-images/thumbnail';
            $fullPathThumbnail=public_path($uploadThumbnailPath).'/'.$imageName;
            $image = $request->file('image');
            Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnail);

            $uploadPath='images/menu-images/'.Auth::user()->company_id;
            $fullPath=public_path($uploadPath);
            $postedData['image']->move($fullPath, $imageName);
            $menu->image_path=$uploadPath.'/'.$imageName;
            $menu->thumbnail_path=$uploadThumbnailPath.'/'.$imageName;
          }
          $menu->save();
          /*Add data to menus table*/
          Session::flash('success', 'Item has been added successfully');
        }else{
          Session::flash('warning', 'There is some problem in adding');
        }
        return redirect()->route('items.listing',[$postedData['category_id']]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $menu=Menu::with(['category'])->find($id);
      return view('menus.view',compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $menu=Menu::find($id);
      $extras=Extra::where('category_id',$menu->category_id)->get();
      $categoryDetail=Category::select('id','name')->find($menu->category_id);
      return view('menus.edit',compact('menu','extras','categoryDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $menuInfo=Menu::find($id);
      if($request->isMethod('put')){
        $postedData=$request->all();
        $menuInfo->name=$postedData['name'];
        $menuInfo->amount=($postedData['amount'])??0;
        $menuInfo->description=$postedData['description'];
        /*Quantity code version 2.5
        $menuInfo->quantity=$postedData['quantity'];
        */
        $menuInfo->extras=$postedData['extras'];
        $menuInfo->extra_free=$postedData['extra_free'];
        if(isset($postedData['image'])) {
          $imageName = time().'.'.$postedData['image']->extension();

          $uploadThumbnailPath='images/menu-images/thumbnail';
          $fullPathThumbnail=public_path($uploadThumbnailPath).'/'.$imageName;
          $image = $request->file('image');
          Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnail);

          $uploadPath='images/menu-images/'.Auth::user()->company_id;
          $fullPath=public_path($uploadPath);
          $postedData['image']->move($fullPath, $imageName);

          $menuInfo->image_path=$uploadPath.'/'.$imageName;
          $menuInfo->thumbnail_path=$uploadThumbnailPath.'/'.$imageName;
        }
        $menuInfo->company_id=Auth::user()->company_id;
        $menuInfo->save();

        Session::flash('success', 'Item has been updated successfully');
      }else{
        Session::flash('warning', 'There is some problem in adding');
      }
      return redirect()->route('items.listing',[$menuInfo->category_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $menuInfo=Menu::find($id);
      $categoryId=$menuInfo->category_id;
      Menu::where('id',$id)->update(['deleted'=>1]);
      Session::flash('success', 'Item has been deleted successfully');
      return redirect()->route('items.listing',[$categoryId]);
    }

    public function getCategoryExtras($cid)
    {
      $extras=Extra::where('category_id',$cid)->get();
      return json_encode($extras);
    }
}
