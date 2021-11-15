<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RestaurantMenu;
use App\Category;
use Illuminate\Support\Facades\Session;
use Auth;

class RestaurantMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $menus=RestaurantMenu::where(['company_id'=>auth()->user()->company_id,'deleted'=>0])->paginate(10);
      return view('restaurant_menus.index',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant_menus.create');
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
          'name'=>'required',
          'start_time'=>'required',
          'end_time'=>'required'
        ]);
        $postedData=$request->all();
        $postedData['start_time']=date('H:i',strtotime($postedData['start_time']));
        $postedData['end_time']=date('H:i',strtotime($postedData['end_time']));
        $menu = new RestaurantMenu;
        $menu->name=$postedData['name'];
        $menu->start_time=$postedData['start_time'];
        $menu->end_time=$postedData['end_time'];
        $menu->company_id=Auth::user()->company_id;
        $menu->save();
        Session::flash('success', 'Menu has been added successfully');
      }else{
        Session::flash('warning', 'There is some problem in adding');
      }
      return redirect()->route('menus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $menu=RestaurantMenu::find($id);
      return view('restaurant_menus.edit',compact('menu'));
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
        $request->validate([
          'name'=>'required',
          'start_time'=>'required',
          'end_time'=>'required'
        ]);
        $postedData=$request->all();
        $postedData['start_time']=date('H:i',strtotime($postedData['start_time']));
        $postedData['end_time']=date('H:i',strtotime($postedData['end_time']));
        $menuInfo=RestaurantMenu::find($id);
        $menuInfo->name=$postedData['name'];
        $menuInfo->start_time=$postedData['start_time'];
        $menuInfo->end_time=$postedData['end_time'];
        $menuInfo->company_id=Auth::user()->company_id;
        $menuInfo->save();
        Session::flash('success', 'Menu has been updated successfully');
      }else{
        Session::flash('warning', 'There is some problem in updating');
      }
      return redirect()->route('menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      RestaurantMenu::where('id',$id)->update(['deleted'=>1]);
      Category::where('menu_id',$id)->update(['deleted'=>1]);
      Session::flash('success', 'Menu has been deleted successfully');
      return redirect()->route('menus.index');
    }
}
