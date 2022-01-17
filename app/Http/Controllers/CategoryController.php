<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Menu;
use App\RestaurantMenu;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($menu_id)
    {
      $categories=Category::where(['company_id'=>auth()->user()->company_id,
      'deleted'=>0,'menu_id'=>$menu_id])->paginate(10);
      $menuDetail=RestaurantMenu::select('id','name')->find($menu_id);
      return view('categories.index',compact('categories','menuDetail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($menu_id)
    {
        $menuDetail=RestaurantMenu::select('id','name')->find($menu_id);
        return view('categories.create',compact('menuDetail'));
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
          ]);
          $postedData=$request->all();
          if($postedData){
            /*Add data to categories table*/
            $category = new Category;
            $category->name=$postedData['name'];
            $category->menu_id=$postedData['menu_id'];
            $category->company_id=Auth::user()->company_id;
            if(isset($postedData['image'])) {
              $imageName = time().'.'.$postedData['image']->extension();
              $uploadThumbnailPath='images/category-images/thumbnail';
              $fullPathThumbnail=public_path($uploadThumbnailPath).'/'.$imageName;
              $image = $request->file('image');
              Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnail);

              $uploadPath='images/category-images/'.Auth::user()->company_id;
              $fullPath=public_path($uploadPath);
              $postedData['image']->move($fullPath, $imageName);
              $category->image_path=$uploadPath.'/'.$imageName;
              $category->thumbnail_path=$uploadThumbnailPath.'/'.$imageName;
            }
            $category->save();
            /*Add data to categories table*/
            Session::flash('success', 'Category has been added successfully');
          }else{
            Session::flash('warning', 'There is some problem in adding');
          }
          return redirect()->route('categories.listing',[$postedData['menu_id']]);
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
      $category=Category::find($id);
      return view('categories.view',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $category=Category::find($id);
      $menuDetail=RestaurantMenu::select('id','name')->find($category->menu_id);
      return view('categories.edit',compact('category','menuDetail'));
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
      if($request->isMethod('put')){
        $postedData=$request->all();

        $category=Category::find($id);
        $category->name=$postedData['name'];
        if(isset($postedData['image'])) {
          $imageName = time().'.'.$postedData['image']->extension();
          $uploadThumbnailPath='images/category-images/thumbnail';
          $fullPathThumbnail=public_path($uploadThumbnailPath).'/'.$imageName;
          $image = $request->file('image');
          Image::make($image->getRealPath())->resize(300, 185)->save($fullPathThumbnail);

          $uploadPath='images/category-images/'.Auth::user()->company_id;
          $fullPath=public_path($uploadPath);
          $postedData['image']->move($fullPath, $imageName);

          $category->image_path=$uploadPath.'/'.$imageName;
          $category->thumbnail_path=$uploadThumbnailPath.'/'.$imageName;
        }
        $category->company_id=Auth::user()->company_id;
        $category->save();

        Session::flash('success', 'Category has been updated successfully');
      }else{
        Session::flash('warning', 'There is some problem in adding');
      }
      return redirect()->route('categories.listing',[$category->menu_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $categoryDetail=Category::find($id);
      $menu_id=$categoryDetail->menu_id;
      Category::where('id',$id)->update(['deleted'=>1]);
      Menu::where('category_id',$id)->update(['deleted'=>1]);
      Session::flash('success', 'Category has been deleted successfully');
      return redirect()->route('categories.listing',[$menu_id]);
    }
}
