@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">
            @lang('site.site_list_heading')
            <i class="fas fa-question-circle ml-1 mt-1"
            data-toggle="tooltip" data-placement="right" title="This table will show all non deleted sites which are added by you.You can use three option under `Actions` column. If You want to see detailed info about particular site then click on `eye` icon. If You want to update particular site then click on `pencil` icon. If You want to remove particular site then click on `Dustbin` icon." data-container="body"></i>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('site.table_list_name')</th>
                              <th class="">@lang('site.table_list_location')</th>
              							  <th class="">@lang('site.site_manager_label')</th>
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
                                  @if($site->manager_id)
                                    {{$site->manager->name}}
                                  @endif
                                </td>
                                <td>
              									  <a href="{{route('sites.show',[$site->id])}}"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-eye"></i>
                                  </a>
                                  <a href="{{route('sites.edit',[$site->id])}}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                  @if(auth()->user()->role==COMPANY)
                                    <a href="{{route('sites.delete',[$site->id])}}"
                                      class="btn btn-danger btn-sm"
                                      onclick="return Confirm_Delete({{$site->id}});">
                                      <i class="far fa-trash-alt"></i>
                                    </a>
                                  @endif

                                  @if(getDefaultLocationLoggedUser()!=$site->id)
                                    <a href="{{route('sites.set-default',[$site->id])}}"
                                      class="btn btn-info btn-sm">
                                      @lang('site.site_set_default_label')
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
