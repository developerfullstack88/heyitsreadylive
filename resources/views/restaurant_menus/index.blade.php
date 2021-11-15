@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card" id="MenuListingPage">
	        <header class="card-header font-title">
            @lang('restaurant_menu.listing')
            <a class="btn btn-light pull-right" href="{{route('menus.create')}}"><i class="fa fa-plus-circle"></i> @lang('restaurant_menu.table_th_add')</a>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('restaurant_menu.table_th_name')</th>
                              <th class="">@lang('restaurant_menu.table_th_time')</th>
              							  <th class="">@lang('common.table_list_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($menus->count()>0)
                           @foreach($menus as $menu)
   		                       <tr>
         							          <td>{{$menu->name}}</td>
                                <td>{{date('h:i a',strtotime($menu->start_time))}} to {{date('h:i a',strtotime($menu->end_time))}}</td>
                                 <td>
                                   <a href="{{route('menus.edit',[$menu->id])}}"
                                     class="btn btn-primary btn-sm">
                                     <i class="fa fa-pencil"></i>
                                   </a>
                                   <a href="{{route('menus.delete',[$menu->id])}}"
                                     class="btn btn-danger btn-sm"
                                     onclick="return Confirm_Delete({{$menu->id}});">
                                     <i class="fa fa-trash-o"></i>
                                   </a>
                                   <a href="{{route('categories.listing',[$menu->id])}}"
                                     class="btn btn-secondary btn-sm">
                                     @lang('restaurant_menu.table_th_sub_menus')
                                   </a>
                                 </td>
   		                       </tr>
                           @endforeach
                         @endif
		                   </tbody>
                   </table>
	             </div>
          </div>
        {{ $menus->links() }}
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function Confirm_Delete(id){
    if(confirm('Do you want to delete menu:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
