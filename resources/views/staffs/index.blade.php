@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">
            @lang('staff.staff_list_heading')
            <i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="This table will show all non deleted staff members which are added by you.You can use three option under `Actions` column. If You want to see detailed info about particular member then click on `eye` icon. If You want to update particular member info then click on `pencil` icon. If You want to remove particular member then click on `Dustbin` icon." data-container="body"></i>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
                             <th class="">@lang('staff.staff_list_name_label')</th>
              							  <th class="">@lang('staff.staff_list_first_name_label')</th>
                              <th class="">@lang('staff.staff_list_last_name_label')</th>
                              <th class="">@lang('staff.staff_list_email_label')</th>
                              <th class="">@lang('staff.staff_list_phone_number_label')</th>
              							  <th class="">@lang('staff.staff_list_profile_image_label')</th>
              							  <th class="">@lang('common.table_list_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                        @if($users->count()>0)
                          @foreach($users as $user)
  		                       <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->first_name}}</td>
                                <td>{{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone_number}}</td>
        							          <td>
                                  @if($user->profile_photo_thumbnail)
                                    <img src="{{asset($user->profile_photo_thumbnail)}}" class="rounded"/>
                                  @endif
                                </td>
                                <td>
              									  <a href="{{route('staffs.show',[$user->id])}}"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-eye"></i>
                                  </a>
                                  <a href="{{route('staffs.edit',[$user->id])}}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  <a href="{{route('staffs.delete',[$user->id])}}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return Confirm_Delete({{$user->id}});">
                                    <i class="far fa-trash-alt"></i>
                                  </a>
                                  @if(!$user->password)
                                  <a href="{{route('staffs.resend-email',[$user->id])}}"
                                    class="btn btn-primary btn-sm">
                                    Resend Email
                                  </a>
                                  @endif
                                </td>
  		                       </tr>
                          @endforeach
                        @endif
		                   </tbody>
                   </table>
	             </div>
          </div>
          {{ $users->links() }}
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function Confirm_Delete(id){
    if(confirm('Do you want to delete site:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
