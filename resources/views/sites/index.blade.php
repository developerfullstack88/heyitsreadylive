@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">@lang('site.site_list_heading')</header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('site.table_list_name')</th>
              							  <th class="">@lang('site.table_list_location')</th>
              							  <th class="">@lang('common.table_list_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                        @if($sites->count()>0)
                          @foreach($sites as $site)
  		                       <tr>
        							          <td>{{$site->name}}</td>
        								        <td>{{$site->address}}</td>
                                <td>
              									  <a href="{{route('sites.show',[$site->id])}}"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-eye"></i>
                                  </a>
                                  <a href="{{route('sites.edit',[$site->id])}}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  <a href="{{route('sites.delete',[$site->id])}}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return Confirm_Delete({{$site->id}});">
                                    <i class="far fa-trash-alt"></i>
                                  </a>
                                </td>
  		                       </tr>
                          @endforeach
                        @endif
		                   </tbody>
                   </table>
	             </div>
          </div>
          {{ $sites->links() }}
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
