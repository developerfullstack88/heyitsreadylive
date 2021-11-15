@extends('layouts.superadmin')
@section('content')
<div class="row">
  <div class="col-lg-12 d-flex justify-content-end align-items-right">
      <section class="card w-25">
        <div class="card-body">
          <div class="">
            {{ Form::open(array('route' => 'business-users.index','method'=>'get')) }}
            <div class="form-group">
              <div class="row justify-content-end">
                <label for="inputStatus" class="mt-2 color-black">Company Status</label>
                <select id="inputStatus" name="status" class="form-control col-lg-6 ml-2">
                  <option value="">All</option>
                  <option value="1" @if(Request::get('status') == '1') selected @endif>Active</option>
                  <option value="0" @if(Request::get('status') == '0') selected @endif>Deactivated</option>
                  <option value="2" @if(Request::get('status') == '2') selected @endif>Deleted</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="row justify-content-end">
                <label for="inputName" class="mt-2 color-black">Company Name</label>
                <select id="inputName" name="company_name" class="form-control col-lg-6 ml-2">
                  <option value="">All</option>
                  @if($allCompanies->count()>0)
                    @foreach($allCompanies as $company)
                      <option value="{{$company->id}}" @if(Request::get('company_name') == $company->id) selected @endif>{{$company->company_name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group pull-right">
                {{Form::button('Filter',["class"=>"btn btn-primary btn-business-users-submit","type"=>"submit"])}}
            </div>
            {{ Form::close() }}
          </div>
        </div>
      </section>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">Business user's</header>
          <div class="card-body">
              <div class="row ml-1 mb-1">
                <label class="color-black">Show</label>
                <form>
                  <select id="pagination" class="mb-1 ml-1">
                      <option value="10" @if($items == 10) selected @endif >10</option>
                      <option value="25" @if($items == 25) selected @endif >25</option>
                      <option value="50" @if($items == 50) selected @endif >50</option>
                      <option value="100" @if($items == 100) selected @endif >100</option>
                  </select>
                </form>
                <label class="color-black ml-1">entries</label>
              </div>
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">Id</th>
                              <th class="">Company Name</th>
                              <th class="">Company Website</th>
                              <th class="">Admin Name</th>
                              <th class="">Admin Phone</th>
                              <th class="">Admin Email</th>
              							  <th class="">Signup Date</th>
              							  <th class="">Status</th>
                              @if(Auth::user()->role=='super admin')
                              <th class="">Actions</th>
                              @endif
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($businessUsers->count()>0)
                           @foreach($businessUsers as $user)
                            @if((isset($user->company)))
     		                       <tr>
                                 <td>{{$user->id}}</td>
                                 <td>{{(isset($user->company))?$user->company->company_name:''}}</td>
                                 <td>{{(isset($user->company))?$user->company->company_website:''}}</td>
                                 <td>{{$user->name}}</td>
                                 <td>{{$user->phone_number}}</td>
           							         <td>{{$user->email}}</td>
           								        <td>{{$user->created_at->format('F d - H:i:s')}}</td>
                                  <td>
                                    @if($user->active==1)
                                      <span class="landlord-status-td">Active</span>
                                    @else
                                      <span class="landlord-status-td-red">Inactive</span>
                                    @endif
                                  </td>
                                  @if(Auth::user()->role=='super admin')
                                  <td>
                                    <a href="{{route('business-users.edit',$user->id)}}" class="btn btn-primary btn-sm">
                                      <i class="fa fa-pencil"></i>
                                    </a>
                                    @if(userValidForDelete($user->created_at))
                                      <a href="{{route('business-users.delete',['id'=>$user->id,'type'=>1])}}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return Confirm_Delete({{$user->id}});">
                                        <i class="fa fa-trash-o"></i>
                                      </a>
                                    @else
                                    <a href="{{route('business-users.delete',['id'=>$user->id,'type'=>0])}}"
                                      class="btn btn-danger btn-sm"
                                      onclick="return Confirm_Delete({{$user->id}});">
                                      <i class="fa fa-trash-o"></i>
                                    </a>
                                    @endif
                                  </td>
                                  @endif
     		                       </tr>
                              @endif
                           @endforeach
                         @endif
		                   </tbody>
                   </table>
	             </div>
               {{$businessUsers->links()}}
          </div>
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  //paginate show records dropdown
  document.getElementById('pagination').onchange = function() {
    window.location = "{{ $businessUsers->url(1) }}&items=" + this.value;
  };

  function Confirm_Delete(id){
    if(confirm('Do you want to delete user:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
