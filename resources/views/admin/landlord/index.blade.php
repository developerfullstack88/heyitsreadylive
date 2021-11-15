@extends('layouts.superadmin')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">Landlord's</header>
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
              							  <th class="">Name</th>
              							  <th class="">Last login at</th>
              							  <th class="">Status</th>
                              @if(Auth::user()->role=='super admin')
                              <th class="">Quick Actions</th>
                              @endif
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($landlords->count()>0)
                           @foreach($landlords as $landlord)
   		                       <tr>
         							          <td>{{$landlord->name}}</td>
         								        <td>{{$landlord->updated_at->format('F d - H:i:s')}}</td>
                                <td><span class="landlord-status-td">Active</span></td>
                                @if(Auth::user()->role=='super admin')
                                <td>
                                  <a href="javascript:void(0);" class="btn btn-primary btn-sm edit-landlord-action"
                                  data-name="{{$landlord->name}}" data-email="{{$landlord->email}}" data-id="{{$landlord->id}}">
                                    <i class="fa fa-pencil"></i>
                                  </a>
                                  <a href="{{route('admin.landlord.delete',[$landlord->id])}}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return Confirm_Delete({{$landlord->id}});">
                                    <i class="fa fa-trash-o"></i>
                                  </a>
                                </td>
                                @endif
   		                       </tr>
                           @endforeach
                         @endif
		                   </tbody>
                   </table>
	             </div>
               {{$landlords->links()}}
               @if(Auth::user()->role=='super admin')
                 <div class="row ml-1">
                   <a id="addNewAdmin" data-target="#newLandlordModal" data-toggle="modal" class="btn btn-info font-title" href="javascript:void(0);">Add new landlord</a>
                 </div>
              @endif
          </div>
      </section>
  </div>
</div>
<!--Add landlord modal-->
<div class="modal fade" id="newLandlordModal" tabindex="-1" role="dialog" aria-labelledby="newLandlordLabel"
aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {{ Form::open(array('route'=> 'admin.landlord.add','id'=>'newLandlordForm')) }}
            <div class="modal-header">
                <h5 class="modal-title">New Landlord</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                {{Form::label('name', 'Name')}}
                {{Form::text('name','',
                  ['class'=>'form-control','placeholder'=>'','required'=>true]
                )}}
              </div>
              <div class="form-group">
                {{Form::label('email', 'Email')}}
                {{Form::text('email','',
                  ['class'=>'form-control required','placeholder'=>'','required'=>true]
                )}}
              </div>
              <div class="form-group">
                {{Form::label('password', 'Password')}}
                <input id="password" type="password" class="form-control" name="password" required>
              </div>
              <div class="form-group">
                {{Form::label('confirm_password', 'Confirm Password')}}
                <input id="confirm_password" type="password" class="form-control" name="confirm_password" required>
              </div>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              {{Form::button('Create',["class"=>"btn btn-primary","type"=>"submit"])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--Add landlord modal-->

<!--Edit landlord modal-->
<div class="modal fade" id="editLandlordModal" tabindex="-1" role="dialog" aria-labelledby="editLandlordLabel"
aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form method="post" action="" id="editLandlordForm"/>
            <div class="modal-header">
                <h5 class="modal-title">Edit Landlord</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              @csrf
              @method('PUT')
              <div class="form-group">
                {{Form::label('name', 'Name')}}
                {{Form::text('name','',
                  ['class'=>'form-control','placeholder'=>'','required'=>true]
                )}}
              </div>
              <div class="form-group">
                {{Form::label('email', 'Email')}}
                {{Form::text('email','',
                  ['class'=>'form-control','placeholder'=>'','required'=>true]
                )}}
              </div>
              <div class="form-group">
                {{Form::label('password', 'Password')}}
                <input id="password1" type="password" class="form-control" name="password1" required>
              </div>
              <div class="form-group">
                {{Form::label('confirm_password', 'Confirm Password')}}
                <input id="confirm_password1" type="password" class="form-control" name="confirm_password1" required>
              </div>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              {{Form::button('Update',["class"=>"btn btn-primary","type"=>"submit"])}}
            </div>
          </form>
        </div>
    </div>
</div>
<!--Edit landlord modal-->
@endsection
@section('myScripts')
<script type="text/javascript">
  /* password and confirm password validation*/
  var password = document.getElementById("password");
  var confirm_password = document.getElementById("confirm_password");

  var password1 = document.getElementById("password1");
  var confirm_password1 = document.getElementById("confirm_password1");

  function validatePassword(){
    if(password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Passwords Don't Match");
    } else {
      confirm_password.setCustomValidity('');
    }
  }

  function validatePassword1(){
    if(password1.value != confirm_password1.value) {
      confirm_password1.setCustomValidity("Passwords Don't Match");
    } else {
      confirm_password1.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
  password1.onchange = validatePassword1;
  confirm_password1.onkeyup = validatePassword1;
  /* password and confirm password validation*/

  function Confirm_Delete(id){
    if(confirm('Do you want to delete landlord:'+id)){
      return true;
    }else{
      return false;
    }
  }

  //paginate show records dropdown
  document.getElementById('pagination').onchange = function() {
    window.location = "{{ $landlords->url(1) }}&items=" + this.value;
  };

  $(document).ready(function(){
    $('.edit-landlord-action').click(function(){
      var id=$(this).attr('data-id');
      var url=APP_URL+'/admin/landlord/update/'+id
      var name=$(this).attr('data-name');
      var email=$(this).attr('data-email');
      $('#editLandlordModal form').attr('action',url);
      $('#editLandlordModal #name').val(name);
      $('#editLandlordModal #email').val(email);
      $('#editLandlordModal').modal('show');
    });

    /*landlord add and edit modal validation*/
    $('#newLandlordForm').validate({
      rules:{
        email:{
          remote:APP_URL+'/admin/email-exist',
        }
      },
      messages:{
        email:{
          remote:"Email already exist"
        }
      }
    });
    /*landlord add and edit modal validation*/
  });
</script>
@endsection
