<?php

namespace App\Http\Controllers;

use App\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $taxes=Tax::where(['company_id'=>auth()->user()->company_id])->paginate(10);
      return view('taxes.index',compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('taxes.create');
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
          'tax_value'=>'required',
        ]);
        if(!isset($postedData['is_default'])) {
          $postedData['is_default']=0;
        }
        $postedData['company_id']=auth()->user()->company_id;
        if(isset($postedData['is_default']) && $postedData['is_default']==1) {
          Tax::where('company_id',auth()->user()->company_id)->update(['is_default'=>0]);
        }
        $taxInserted=Tax::create($postedData);
        if($taxInserted->id>0){
          if(isset($postedData['is_default'])) {
          }
          Session::flash('success', 'Tax has been added successfully');
        }else{
          Session::flash('warning', 'There is some problem in adding');
        }
        return redirect()->route('taxes.index');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $taxDetail=Tax::find($id);
      return view('taxes.edit',compact('taxDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $taxDetail=Tax::find($id);
      if($request->isMethod('put')){
        $postedData=$request->all();
        $request->validate([
          'name'=>'required',
          'tax_value'=>'required',
        ]);
        if(!isset($postedData['is_default'])) {
          $postedData['is_default']=0;
        }
        unset($postedData['_token'],$postedData['_method']);
        if(isset($postedData['is_default']) && $taxDetail->is_default!=$postedData['is_default']) {
          Tax::where('company_id',auth()->user()->company_id)->update(['is_default'=>0]);
        }
        $taxInserted=Tax::where('id',$id)->update($postedData);
        if($taxInserted){
          Session::flash('success', 'Tax has been updated successfully');
        }else{
          Session::flash('warning', 'There is some problem in adding');
        }
        return redirect()->route('taxes.index');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Tax::where('id',$id)->delete();
      Session::flash('success', 'Tax has been deleted successfully');
      return redirect()->route('taxes.index');
    }
}
