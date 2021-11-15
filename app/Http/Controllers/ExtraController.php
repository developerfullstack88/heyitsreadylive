<?php

namespace App\Http\Controllers;

use App\Extra;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $categoryDetail=Category::find($id);
        $extras=Extra::where(['category_id'=>$id])->paginate(10);
        return view('extras.index',compact('categoryDetail','extras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $categoryDetail=Category::find($id);
        return view('extras.create',compact('categoryDetail'));
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
        $postedData=$request->all();
        $request->validate([
          'name'=>'required',
          'category_id'=>'required'
        ]);
        if(isset($postedData['is_free']) && $postedData['is_free']==1) {
          //$postedData['price']=0;
        }else{
          $postedData['is_free']=0;
        }
        $postedData['price']=$postedData['price']??0;
        $extraInserted=Extra::create($postedData);
        if($extraInserted->id>0){
          Session::flash('success', 'Extra has been added successfully');
        }else{
          Session::flash('warning', 'There is some problem in adding');
        }
        return redirect()->route('extras.listing',[$postedData['category_id']]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function show(Extra $extra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $extraDetail=Extra::find($id);
        $categoryDetail=Category::find($extraDetail->category_id);
        return view('extras.edit',compact('extraDetail','categoryDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $extraDetail=Extra::find($id);
      if($request->isMethod('put')){
        $postedData=$request->all();
        if(isset($postedData['is_free']) && $postedData['is_free']==1) {
          //$postedData['price']=0;
        }else{
          $postedData['is_free']=0;
        }
        $request->validate([
          'name'=>'required'
        ]);
        unset($postedData['_token'],$postedData['_method']);
        $postedData['price']=$postedData['price']??0;
        $extraInserted=Extra::where('id',$id)->update($postedData);
        if($extraInserted){
          Session::flash('success', 'Extra has been updated successfully');
        }else{
          Session::flash('warning', 'There is some problem in adding');
        }
        return redirect()->route('extras.listing',[$extraDetail->category_id]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $extraDetail=Extra::find($id);
      Extra::where('id',$id)->delete();
      Session::flash('success', 'Extra has been deleted successfully');
      return redirect()->route('extras.listing',[$extraDetail->category_id]);
    }
}
