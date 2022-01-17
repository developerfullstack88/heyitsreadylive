@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card" id="MenuListingPage">
	        <header class="card-header font-title">
            @lang('restaurant_menu.dishes_listing_for') {{$categoryDetail->name}}
            <a class="btn btn-light pull-right" href="{{route('items.add',[$categoryDetail->id])}}"><i class="fa fa-plus-circle"></i> @lang('restaurant_menu.table_th_add')</a>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('site.table_list_name')</th>
                              <th class="">@lang('restaurant_menu.dishes_listing_th_category')</th>
                              <th class="">@lang('restaurant_menu.category_listing_th_image')</th>
                              <th class="">@lang('restaurant_menu.extras_listing_th_price')</th>
              							  <th class="">@lang('common.table_list_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($menus->count()>0)
                           @foreach($menus as $menu)
   		                       <tr>
         							          <td>{{$menu->name}}</td>
                                <td>{{$menu->category->name}}</td>
         								        <td>
                                  @if($menu->image_path)
                                    <img class="menu-listing-img" src="{{asset($menu->image_path)}}"/>
                                  @endif
                                </td>
                                <td>${{number_format($menu->amount,2)}}</td>
                                 <td>
               									  <a href="{{route('items.show',[$menu->id])}}"
                                     class="btn btn-success btn-sm">
                                     <i class="fa fa-eye"></i>
                                   </a>
                                   <a href="{{route('items.edit',[$menu->id])}}"
                                     class="btn btn-primary btn-sm">
                                     <i class="fa fa-pencil"></i>
                                   </a>
                                   <a href="{{route('items.delete',[$menu->id])}}"
                                     class="btn btn-danger btn-sm"
                                     onclick="return Confirm_Delete({{$menu->id}});">
                                     <i class="fa fa-trash-o"></i>
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
    if(confirm('Do you want to delete dish:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
