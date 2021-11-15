<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Hash;

/*
@developer:jasmaninder
@controller:LandlordController
@description:-This will used for landlord part
*/
class LandlordController extends Controller
{

  /*
  @developer:jasmaninder
  @method:index
  @description:-This will used for landlord listing
  */
  public function index(Request $request){
    $items = $request->items ?? 10;
    $landlords=User::where('role','admin')->paginate($items);
    return view('admin.landlord.index',compact('landlords','items'));
  }

  /*
  @developer:jasmaninder
  @method:store
  @description:-add new admin into DB
  */
  public function store(Request $request){
    if($request->isMethod('post')){
      $user=User::create([
        'email'=>$request->input('email'),
        'name'=>$request->input('name'),
        'password'=>bcrypt($request->input('password')),
        'role'=>'admin'
      ]);
      if($user->id>0){
        Session::flash('success','landlord has been created successfully');
        return redirect()->route('admin.landlord.index');
      }else{
        Session::flash('warning','There is some problem in adding');
        return redirect()->route('admin.landlord.index');
      }
    }
  }

  /*
  @developer:jasmaninder
  @method:update
  @description:-update lanlord detail
  */
  public function update(Request $request,$id){
    if($request->isMethod('put')){
      $landlordInfo=User::find($id);
      if($landlordInfo){
        $landlordInfo->email=$request->input('email');
        $landlordInfo->name=$request->input('name');
        $landlordInfo->password=bcrypt($request->input('password1'));
        if($landlordInfo->save()){
          Session::flash('success','landlord has been updated successfully');
          return redirect()->route('admin.landlord.index');
        }else{
          Session::flash('warning','There is some problem in updation');
          return redirect()->route('admin.landlord.index');
        }
      }else{
        Session::flash('warning','landlord info not found');
        return redirect()->route('admin.landlord.index');
      }
    }
  }

  /*
  @developer:jasmaninder
  @method:destroy
  @description:-delete landlord from db
  */
  public function destroy(Request $request,$id){
    User::where('id',$id)->delete();
    Session::flash('success', 'landlord has been deleted successfully');
    return redirect()->route('admin.landlord.index');
  }
}
?>
